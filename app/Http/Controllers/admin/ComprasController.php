<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto; 
use App\Models\ProductoPrecio;  
use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\ProductoCompra;
use App\Models\DiaCompra;
use App\Models\SerieProducto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ComprasController extends Controller
{
    public function index()
    {
        return view('admin.Compras.index');
    }

    public function create()
    {
        return view('admin.compras.create');
    }

    // Agrega estos métodos al final de la clase ComprasController, antes del último }

    /**
     * Buscar productos para autocompletado
     */
    public function buscarProductos(Request $request)
    {
        $termino = $request->get('q', '');
        
        if (strlen($termino) < 2) {
            return response()->json([]);
        }
        
        $productos = Producto::where('estado', '1')
            ->where('almacen', '1')
            ->where(function($query) use ($termino) {
                $query->where('nombre', 'LIKE', "%{$termino}%")
                    ->orWhere('cod_barra', 'LIKE', "%{$termino}%")
                    ->orWhere('codigo', 'LIKE', "%{$termino}%");
            })
            ->select([
                'id_producto',
                'cod_barra',
                'codigo', 
                'nombre',
                'precio',
                'cantidad',
                'tipo_control'
            ])
            ->limit(10)
            ->get();
        
        $resultados = $productos->map(function($producto) {
            return [
                'id' => $producto->id_producto,
                'codigo' => $producto->cod_barra ?: $producto->codigo,
                'nombre' => $producto->nombre,
                'precio' => number_format($producto->precio, 2),
                'stock' => $producto->cantidad,
                'tipo_control' => $producto->tipo_control,
                'label' => $producto->cod_barra ?: $producto->codigo . ' | ' . $producto->nombre . ' | P.Venta S/. ' . number_format($producto->precio, 2) . ' | Stock: ' . $producto->cantidad,
                'value' => $producto->nombre
            ];
        });
        
        return response()->json($resultados);
    }

    /**
     * Obtener precios de un producto específico
     */
    public function obtenerPreciosProducto($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $multiprecios = ProductoPrecio::where('id_producto', $id)->get();
            
            $precios = [
                'precio_base' => [
                    'nombre' => 'Precio base',
                    'precio' => $producto->precio
                ],
                'precio_mayorista' => [
                    'nombre' => 'Precio mayorista',
                    'precio' => $producto->precio_mayor
                ],
                'precio_distribuidor' => [
                    'nombre' => 'Precio distribuidor', 
                    'precio' => $producto->precio_menor
                ],
                'precio_unidad' => [
                    'nombre' => 'Precio por unidad',
                    'precio' => $producto->precio_unidad
                ]
            ];
            
            // Agregar precios adicionales
            foreach ($multiprecios as $multiprecio) {
                $precios['multiprecio_' . $multiprecio->id] = [
                    'nombre' => $multiprecio->nombre,
                    'precio' => $multiprecio->precio
                ];
            }
            
            return response()->json([
                'success' => true,
                'precios' => $precios
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Producto no encontrado'
            ], 404);
        }
    }

    /**
     * Guardar nueva compra
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validaciones básicas
            $this->validarDatosCompra($request);

            // 1. Gestionar proveedor
            $proveedor = $this->gestionarProveedor($request);

            // 2. Crear la compra
            $compra = $this->crearCompra($request, $proveedor->proveedor_id);

            // 3. Procesar productos
            $this->procesarProductosCompra($request, $compra->id_compra);

            $this->actualizarStockProductos($request);

            // 4. Procesar cuotas si es crédito
            if ($request->id_tipo_pago == 2) {
                $this->procesarCuotasCredito($request, $compra->id_compra);
            }

            // 5. Procesar códigos únicos si corresponde
            // Reemplázala por:
            if ($request->tipo_registro === 'completo' && $request->has('codigos_unicos') && !empty($request->codigos_unicos)) {
                $this->procesarCodigosUnicos($request->codigos_unicos, $compra->id_compra);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Compra guardada correctamente',
                'compra_id' => $compra->id_compra
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la compra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validar datos de la compra
     */
    private function validarDatosCompra(Request $request)
    {
        $request->validate([
            'id_tido' => 'required|integer',
            'id_tipo_pago' => 'required|integer',
            'num_doc' => 'required|string|max:11',
            'nom_cli' => 'required|string|max:200',
            'dir_cli' => 'required|string|max:100',
            'serie' => 'required|string|max:50',
            'numero' => 'required|string|max:50',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'required|date',
            'moneda' => 'required|in:S,D',
            'total' => 'required|numeric|min:0',
            // REEMPLAZAR la línea de validación de productos por estas:
            'productos' => 'required|array|min:1',
            'productos.*.id_producto' => 'required|integer|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|numeric|min:0.01',
            'productos.*.precio' => 'required|numeric|min:0',
            'productos.*.subtotal' => 'required|numeric|min:0'
        ]);

        // Validar tasa de cambio si es en dólares
        if ($request->moneda === 'D') {
            $request->validate([
                'tasa_cambio' => 'required|numeric|min:0.001',
                'total_soles' => 'required|numeric|min:0'
            ]);
        }

        // Validar cuotas si es crédito
        if ($request->id_tipo_pago == 2) {
            $request->validate([
                'cuotas' => 'required|array|min:1',
                'cuotas.*.monto' => 'required|numeric|min:0',
                'cuotas.*.fecha' => 'required|date'
            ]);
        }

        // Busca esta parte en validarDatosCompra() y reemplázala:

        // Busca esta sección y reemplázala:
        // Validar que el total corresponda a la suma de subtotales
        $sumaSubtotales = collect($request->productos)->sum('subtotal');
        $totalEsperado = floatval($request->total);

        // NUEVA LÓGICA: Validar según la moneda
        if ($request->moneda === 'D') {
            // Para dólares: validar que los subtotales estén en dólares
            $sumaSubtotalesDolares = 0;
            foreach ($request->productos as $producto) {
                // Los subtotales ya vienen convertidos a dólares desde el frontend
                $sumaSubtotalesDolares += floatval($producto['subtotal']);
            }
            $diferencia = abs($sumaSubtotalesDolares - $totalEsperado);
        } else {
            // Para soles: validación normal
            $diferencia = abs($sumaSubtotales - $totalEsperado);
        }

        $tolerancia = 0.50; // Aumentar tolerancia para conversiones

        if ($diferencia > $tolerancia) {
            Log::error('Error de validación de totales', [
                'suma_subtotales' => $sumaSubtotales,
                'total_esperado' => $totalEsperado,
                'diferencia' => $diferencia,
                'moneda' => $request->moneda,
                'productos' => $request->productos
            ]);
            
            throw new \Exception("Error de validación: Total esperado {$totalEsperado}, suma calculada {$sumaSubtotales}, diferencia {$diferencia}");
        }

        // Busca el final de las validaciones en validarDatosCompra() y agrega:

        // Validar IGV
        if ($request->has('tiene_igv')) {
            $request->validate([
                'tiene_igv' => 'in:S,N'
            ]);
        }

        // Busca las validaciones de IGV que agregaste antes y agrega después:
        if ($request->has('total_igv')) {
            $request->validate([
                'total_igv' => 'numeric|min:0'
            ]);
        }
        
    }

    /**
     * Gestionar proveedor (crear o reutilizar)
     */
        /**
     * Gestionar proveedor (crear o reutilizar)
     */
    private function gestionarProveedor(Request $request)
    {
        $documento = $request->num_doc;
        
        // Buscar proveedor existente
        $proveedor = Proveedor::where('ruc', $documento)->first();
        
        if (!$proveedor) {
            // Obtener id_empresa válido
            $idEmpresa = $this->obtenerIdEmpresaValido();
            
            // Crear nuevo proveedor
            $proveedor = Proveedor::create([
                'ruc' => $documento,
                'razon_social' => $request->nom_cli,
                'direccion' => $request->dir_cli,
                'estado' => 1,
                'id_empresa' => $idEmpresa
            ]);
        } else {
            // Actualizar datos si han cambiado
            $proveedor->update([
                'razon_social' => $request->nom_cli,
                'direccion' => $request->dir_cli
            ]);
        }
        
        return $proveedor;
    }

        /**
     * Obtener ID de empresa válido
     */
    private function obtenerIdEmpresaValido()
    {
        // Primero intentar obtener del usuario autenticado
        $idEmpresaUsuario = Auth::user()->id_empresa ?? null;
        
        if ($idEmpresaUsuario) {
            // Verificar si existe en la tabla empresas
            $existeEmpresa = DB::table('empresas')
                ->where('id_empresa', $idEmpresaUsuario)
                ->exists();
                
            if ($existeEmpresa) {
                return $idEmpresaUsuario;
            }
        }
        
        // Si no existe o no hay usuario, buscar la primera empresa disponible
        $primeraEmpresa = DB::table('empresas')
            ->select('id_empresa')
            ->first();
            
        if ($primeraEmpresa) {
            return $primeraEmpresa->id_empresa;
        }
        
        // Si no hay empresas, crear una empresa por defecto
        $empresaDefecto = DB::table('empresas')->insertGetId([
            'nombre' => 'Empresa Principal',
            'ruc' => '00000000000',
            'direccion' => 'Sin dirección',
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return $empresaDefecto;
    }

    /**
     * Crear registro de compra
     */
        /**
     * Crear registro de compra
     */
    private function crearCompra(Request $request, $proveedorId)
    {
        $datosCompra = [
            'id_tido' => $request->id_tido,
            'id_tipo_pago' => $request->id_tipo_pago,
            'id_proveedor' => $proveedorId,
            'fecha_emision' => $request->fecha_emision,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'serie' => $request->serie,
            'numero' => $request->numero,
            'total' => $request->total,
            'estado_recepcion' => $request->tipo_registro === 'completo' ? 'completo' : 'pendiente',
            'moneda' => $request->moneda,
            'id_usuario' => Auth::id(),
            'id_empresa' => $this->obtenerIdEmpresaValido(), // Cambiado: usar función para obtener ID válido
            'sucursal' => 1, // Ajustar según tu sistema
            'observaciones' => $request->observaciones,
            'tiene_igv' => $request->tiene_igv ?? 'N',
            'igv' => $request->total_igv ?? 0,
        ];

        // Agregar campos específicos para dólares
        if ($request->moneda === 'D') {
            $datosCompra['tasa_cambio'] = $request->tasa_cambio;
            $datosCompra['total_soles'] = $request->total_soles;
        } else {
            $datosCompra['total_soles'] = $request->total;
        }

        // Agregar días de pago si es crédito
        if ($request->id_tipo_pago == 2 && $request->has('dias_pago')) {
            $datosCompra['dias_pagos'] = $request->dias_pago;
        }

        return Compra::create($datosCompra);
    }

    /**
     * Procesar productos de la compra
     */
    private function procesarProductosCompra(Request $request, $compraId)
    {
        $totalIGVCompra = 0;
        
        foreach ($request->productos as $producto) {
            
            $igvProducto = 0;

            $datosProducto = [
                'id_producto' => $producto['id_producto'],
                'id_compra' => $compraId,
                'cantidad' => $producto['cantidad'],
                'precio' => $producto['precio'],
                'costo' => $producto['precio'],
                'subtotal' => $producto['subtotal'],
                'igv_producto' => $igvProducto
            ];

            // Si es compra en dólares, guardar también precio en soles
            if ($request->moneda === 'D') {
                $datosProducto['precio_soles'] = $producto['precio_soles'] ?? ($producto['precio'] * $request->tasa_cambio);
                $datosProducto['moneda_original'] = 'D';
            } else {
                $datosProducto['precio_soles'] = $producto['precio'];
                $datosProducto['moneda_original'] = 'S';
            }

            ProductoCompra::create($datosProducto);
        }
        
        // El IGV total ya viene calculado desde el frontend
        if ($request->tiene_igv === 'S' && $request->has('total_igv')) {
            Compra::where('id_compra', $compraId)->update([
                'igv' => $request->total_igv
            ]);
        }
    }

    /**
    * Actualizar stock de productos después de la compra
    */
    private function actualizarStockProductos(Request $request)
    {
        // Solo actualizar stock si es registro completo
        if ($request->tipo_registro !== 'completo') {
            return;
        }

        foreach ($request->productos as $producto) {
            $productoModel = Producto::find($producto['id_producto']);
            
            if ($productoModel) {
                // Aumentar la cantidad (es una compra, se suma al stock)
                $nuevaCantidad = $productoModel->cantidad + $producto['cantidad'];
                
                $productoModel->update([
                    'cantidad' => $nuevaCantidad,
                    'ultima_salida' => now() // Actualizar fecha de última modificación
                ]);
                
                Log::info("Stock actualizado para producto {$productoModel->nombre}: {$productoModel->cantidad} -> {$nuevaCantidad}");
            }
        }
    }

    /**
     * Procesar cuotas de crédito
     */
    private function procesarCuotasCredito(Request $request, $compraId)
    {
        if (!$request->has('cuotas') || !is_array($request->cuotas)) {
            return;
        }

        foreach ($request->cuotas as $cuota) {
            DiaCompra::create([
                'id_compra' => $compraId,
                'monto' => $cuota['monto'],
                'fecha' => $cuota['fecha'],
                'estado' => $cuota['estado'] ?? 'P' // P = Pendiente
            ]);
        }
    }

    /**
     * Procesar códigos únicos escaneados
     */
    private function procesarCodigosUnicos($codigosUnicos, $compraId)
    {
        if (!is_array($codigosUnicos) || empty($codigosUnicos)) {
            return;
        }

        foreach ($codigosUnicos as $codigoData) {
            // Validar que tenga los campos necesarios
            if (!isset($codigoData['id_producto']) || !isset($codigoData['codigo'])) {
                continue;
            }

            SerieProducto::create([
                'id_producto' => $codigoData['id_producto'],
                'codigo_unico' => $codigoData['codigo'],
                'estado' => 'disponible',
                'fecha_ingreso' => now(),
                'id_compra' => $compraId // Agregar referencia a la compra
            ]);
        }
    }

    // MODIFICADO - Agregar filtros de fecha y estado
    public function obtenerCompras(Request $request)
    {
        $query = Compra::with(['proveedor', 'usuario'])
            ->select([
                'id_compra',
                'fecha_emision',
                'fecha_vencimiento', 
                'serie',
                'numero',
                'id_proveedor',
                'id_usuario',
                'total',
                'moneda',
                'estado_recepcion'
            ]);

        // NUEVO - Filtro por fecha de emisión
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_emision', '>=', $request->fecha_desde);
        }
        
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_emision', '<=', $request->fecha_hasta);
        }

        // NUEVO - Filtro por estado de recepción
        if ($request->filled('estado')) {
            $query->where('estado_recepcion', $request->estado);
        }

        $compras = $query->orderBy('id_compra', 'desc')->get();

        $data = [];
        foreach ($compras as $compra) {
            $data[] = [
                'id_compra' => $compra->id_compra,
                'fecha_emision' => $compra->fecha_emision ? $compra->fecha_emision->format('d/m/Y') : '',
                'fecha_vencimiento' => $compra->fecha_vencimiento ? $compra->fecha_vencimiento->format('d/m/Y') : '',
                'serie' => $compra->serie,
                'numero' => $compra->numero,
                'razon_social' => $compra->proveedor ? $compra->proveedor->razon_social : 'Sin proveedor',
                'usuario' => $compra->usuario ? $compra->usuario->first_name . ' ' . $compra->usuario->last_name : 'Sin usuario',
                'total' => ($compra->moneda === 'D' ? 'USD ' : 'S/ ') . number_format($compra->total, 2),
                'estado_recepcion' => $compra->estado_recepcion,
                'acciones' => $compra->id_compra
            ];
        }

        return response()->json(['data' => $data]);
    }


    // NUEVO - Método para obtener detalle de compra
    public function obtenerDetalle($id)
    {
        try {
            $compra = Compra::with(['proveedor', 'productosCompras.producto', 'diasCompras'])
                ->findOrFail($id);

            // Productos
            $productos = $compra->productosCompras->map(function($pc) {
                return [
                    'codigo' => $pc->producto->codigo ?: $pc->producto->cod_barra,
                    'nombre' => $pc->producto->nombre,
                    'cantidad' => $pc->cantidad,
                    'precio' => number_format($pc->precio, 2),
                    'subtotal' => number_format($pc->subtotal, 2)
                ];
            });

            // Pagos/Cuotas
            $pagos = $compra->diasCompras->map(function($dia) {
                return [
                    'fecha' => $dia->fecha->format('d/m/Y'),
                    'monto' => number_format($dia->monto, 2),
                    'estado' => $dia->estado === 'C' ? 'Pagado' : 'Pendiente',
                    'estado_codigo' => $dia->estado,
                    'id' => $dia->dias_compra_id
                ];
            });

            return response()->json([
                'success' => true,
                'compra' => [
                    'id' => $compra->id_compra,
                    'serie' => $compra->serie,
                    'numero' => $compra->numero,
                    'fecha_emision' => $compra->fecha_emision->format('d/m/Y'),
                    'proveedor' => $compra->proveedor->razon_social ?? 'Sin proveedor',
                    'total' => number_format($compra->total, 2),
                    'moneda' => $compra->moneda,
                    'tipo_pago' => $compra->id_tipo_pago == 1 ? 'Contado' : 'Crédito'
                ],
                'productos' => $productos,
                'pagos' => $pagos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el detalle: ' . $e->getMessage()
            ], 500);
        }
    }

    // MODIFICADO - Generar PDF real con Dompdf
    public function generarReportePDF($id)
    {
        try {
            $compra = Compra::with(['proveedor', 'productosCompras.producto', 'diasCompras', 'usuario'])
                ->findOrFail($id);

            $html = $this->generarHTMLReporte($compra);
            
            // NUEVO - Usar Dompdf para generar PDF
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            return $dompdf->stream("compra_{$compra->id_compra}.pdf", [
                'Attachment' => false // Para abrir en nueva pestaña
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte: ' . $e->getMessage()
            ], 500);
        }
    }


    // NUEVO - Método para obtener productos pendientes de recepción
    public function obtenerProductosRecepcion($id)
    {
        try {
            $compra = Compra::with(['productosCompras.producto'])->findOrFail($id);
            
            if ($compra->estado_recepcion === 'completo') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta compra ya fue recepcionada completamente'
                ]);
            }

            $productos = $compra->productosCompras->map(function($pc) {
                return [
                    'id_producto' => $pc->id_producto,
                    'codigo' => $pc->producto->codigo ?: $pc->producto->cod_barra,
                    'nombre' => $pc->producto->nombre,
                    'cantidad' => $pc->cantidad,
                    'tipo_control' => $pc->producto->tipo_control,
                    'cantidad_recepcionada' => 0 // Aquí podrías calcular lo ya recepcionado
                ];
            });

            return response()->json([
                'success' => true,
                'productos' => $productos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener productos: ' . $e->getMessage()
            ], 500);
        }
    }

    // NUEVO - Método para recepcionar productos
    public function recepcionarProductos(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $compra = Compra::findOrFail($id);
            
            foreach ($request->productos as $producto) {
                // Registrar en recepcion_productos
                DB::table('recepcion_productos')->insert([
                    'id_compra' => $id,
                    'id_producto' => $producto['id_producto'],
                    'cantidad' => $producto['cantidad'],
                    'fecha' => now()
                ]);

                // Si tiene códigos únicos, registrarlos
                if (isset($producto['codigos_unicos']) && !empty($producto['codigos_unicos'])) {
                    foreach ($producto['codigos_unicos'] as $codigo) {
                        SerieProducto::create([
                            'id_producto' => $producto['id_producto'],
                            'codigo_unico' => $codigo,
                            'estado' => 'disponible',
                            'fecha_ingreso' => now(),
                            'id_compra' => $id
                        ]);
                    }
                }

                // Actualizar stock
                $productoModel = Producto::find($producto['id_producto']);
                if ($productoModel) {
                    $productoModel->increment('cantidad', $producto['cantidad']);
                }
            }

            // Marcar compra como completa
            $compra->update(['estado_recepcion' => 'completo']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Productos recepcionados correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al recepcionar productos: ' . $e->getMessage()
            ], 500);
        }
    }

    // NUEVO - Método para marcar pago como completado
    public function marcarPagoPagado(Request $request, $id)
    {
        try {
            $pago = DiaCompra::findOrFail($id);
            $pago->update(['estado' => 'C']);

            return response()->json([
                'success' => true,
                'message' => 'Pago marcado como pagado'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el pago: ' . $e->getMessage()
            ], 500);
        }
    }

 private function generarHTMLReporte($compra)
{
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Reporte de Compra #' . $compra->id_compra . '</title>
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap");
            
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body { 
                font-family: "Roboto", Arial, sans-serif;
                background: #f8f9fa;
                color: #2d2d2d;
                line-height: 1.4;
                font-size: 13px;
                padding: 15px;
            }
            
            .container {
                max-width: 800px;
                margin: 0 auto;
                background: white;
                border-radius: 10px;
                border: 2px solid #e0e0e0;
                box-shadow: 0 8px 32px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            
            /* HEADER COMPACTO */
            .header {
                background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
                color: #212529;
                padding: 20px;
                text-align: center;
                border-bottom: 3px solid #0066cc;
            }
            
            .header h1 {
                font-size: 24px;
                font-weight: 700;
                margin-bottom: 5px;
                color: #212529;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            }
            
            .header .subtitle {
                font-size: 14px;
                color: #ccddff;
                font-weight: 500;
            }
            
            .header .date-info {
                margin-top: 10px;
                font-size: 12px;
                color: #aaccff;
                border-top: 1px solid rgba(255,255,255,0.2);
                padding-top: 10px;
            }
            
            /* CONTENIDO COMPACTO */
            .content {
                padding: 20px;
                background: white;
            }
            
            /* GRID COMPACTO */
            .info-section {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px;
                margin-bottom: 20px;
            }
            
            .info-box {
                background: #f8f9fa;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                padding: 15px;
                border-left: 4px solid #0066cc;
            }
            
            .info-box h3 {
                color: #2d2d2d;
                font-size: 14px;
                font-weight: 600;
                margin-bottom: 10px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            .info-item {
                display: flex;
                justify-content: space-between;
                margin-bottom: 8px;
                padding: 5px 0;
                border-bottom: 1px solid #e0e0e0;
            }
            
            .info-item:last-child {
                border-bottom: none;
                margin-bottom: 0;
            }
            
            .info-label {
                color: #666666;
                font-size: 12px;
                font-weight: 500;
            }
            
            .info-value {
                color: #2d2d2d;
                font-size: 12px;
                font-weight: 600;
                text-align: right;
            }
            
            .status-badge {
                padding: 4px 8px;
                border-radius: 12px;
                font-size: 10px;
                font-weight: 600;
                text-transform: uppercase;
            }
            
            .status-completo {
                background: #22c55e;
                color: #ffffff;
            }
            
            .status-pendiente {
                background: #f59e0b;
                color: #ffffff;
            }
            
            /* TABLA COMPACTA */
            .table-section {
                margin: 20px 0;
            }
            
            .section-title {
                background: #0066cc;
                color: #ffffff;
                padding: 10px 15px;
                font-size: 14px;
                font-weight: 600;
                text-transform: uppercase;
                border-radius: 6px 6px 0 0;
                margin-bottom: 0;
            }
            
            .table-container {
                background: white;
                border: 1px solid #e0e0e0;
                border-radius: 0 0 6px 6px;
                overflow: hidden;
            }
            
            .compact-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 12px;
                background: white;
            }
            
            .compact-table th {
                background: #f8f9fa;
                color: #2d2d2d;
                padding: 10px 8px;
                font-weight: 600;
                text-align: left;
                font-size: 11px;
                text-transform: uppercase;
                border-bottom: 2px solid #0066cc;
            }
            
            .compact-table td {
                padding: 8px;
                border-bottom: 1px solid #e0e0e0;
                color: #2d2d2d;
                background: white;
                vertical-align: middle;
            }
            
            .compact-table tbody tr:nth-child(even) {
                background: #f8f9fa;
            }
            
            .compact-table tbody tr:nth-child(even) td {
                background: #f8f9fa;
                color: #2d2d2d;
            }
            
            .compact-table tbody tr:hover {
                background: #e3f2fd;
            }
            
            .compact-table tbody tr:hover td {
                background: #e3f2fd;
                color: #2d2d2d;
            }
            
            /* TOTALES COMPACTOS */
            .totals-section {
                background: #f8f9fa;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                margin-top: 20px;
                overflow: hidden;
            }
            
            .totals-header {
                background: #0066cc;
                color: #ffffff;
                padding: 10px 15px;
                font-size: 14px;
                font-weight: 600;
                text-transform: uppercase;
            }
            
            .totals-body {
                padding: 15px;
                background: white;
            }
            
            .total-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 0;
                border-bottom: 1px solid #e0e0e0;
            }
            
            .total-row:last-child {
                border-bottom: none;
            }
            
            .total-label {
                color: #666666;
                font-size: 13px;
                font-weight: 500;
            }
            
            .total-value {
                color: #2d2d2d;
                font-size: 13px;
                font-weight: 600;
                font-family: "Courier New", monospace;
            }
            
            .total-final {
                background: #0066cc;
                color: #ffffff;
                padding: 12px;
                margin-top: 10px;
                border-radius: 6px;
                border: 2px solid #0088ff;
            }
            
            .total-final .total-label {
                color: #ffffff;
                font-size: 15px;
                font-weight: 700;
                text-transform: uppercase;
            }
            
            .total-final .total-value {
                color: #ffffff;
                font-size: 18px;
                font-weight: 700;
            }
            
            /* OBSERVACIONES */
            .observations {
                background: #f0f9ff;
                border: 1px solid #bfdbfe;
                border-radius: 8px;
                padding: 15px;
                margin-top: 20px;
                border-left: 4px solid #22c55e;
            }
            
            .observations-title {
                color: #16a34a;
                font-weight: 600;
                margin-bottom: 8px;
                font-size: 13px;
                text-transform: uppercase;
            }
            
            .observations div {
                color: #374151;
                font-size: 12px;
                line-height: 1.5;
            }
            
            /* FOOTER */
            .footer {
                background: #f8f9fa;
                color: #6b7280;
                padding: 15px;
                text-align: center;
                font-size: 11px;
                border-top: 1px solid #e0e0e0;
            }
            
            .footer p {
                margin: 3px 0;
                color: #6b7280;
            }
            
            .footer strong {
                color: #374151;
            }
            
            /* UTILITIES */
            .text-right { text-align: right; }
            .text-center { text-align: center; }
            .font-mono { 
                font-family: "Courier New", monospace;
                color: #2d2d2d;
            }
            .fw-bold { 
                font-weight: 600;
                color: #2d2d2d;
            }
            
            /* RESPONSIVE */
            @media (max-width: 768px) {
                body {
                    padding: 10px;
                }
                
                .info-section {
                    grid-template-columns: 1fr;
                    gap: 10px;
                }
                
                .compact-table {
                    font-size: 11px;
                }
                
                .compact-table th,
                .compact-table td {
                    padding: 6px 4px;
                }
                
                .header h1 {
                    font-size: 20px;
                }
                
                .container {
                    margin: 0;
                }
            }
            
            /* PRINT */
            @media print {
                body {
                    background: white;
                    color: black;
                    padding: 0;
                }
                
                .container {
                    background: white;
                    border: 1px solid #333;
                    box-shadow: none;
                }
                
                .header {
                    background: #0066cc !important;
                    color: white !important;
                    -webkit-print-color-adjust: exact;
                }
                
                .content,
                .info-box,
                .table-container,
                .totals-body {
                    background: white !important;
                    color: black !important;
                }
                
                .compact-table th,
                .compact-table td {
                    background: white !important;
                    color: black !important;
                    border: 1px solid #333 !important;
                }
                
                .section-title,
                .totals-header {
                    background: #0066cc !important;
                    color: white !important;
                    -webkit-print-color-adjust: exact;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- HEADER -->
            <div class="header">
                <h1>REPORTE DE COMPRA</h1>
                <div class="subtitle">Documento #' . $compra->id_compra . ' • Serie: ' . $compra->serie . '-' . $compra->numero . '</div>
                <div class="date-info">
                    <strong>Generado:</strong> ' . now()->format('d/m/Y') . ' • 
                    <strong>Hora:</strong> ' . now()->format('H:i:s') . '
                </div>
            </div>

            <div class="content">
                <!-- INFORMACIÓN GENERAL -->
                <div class="info-section">
                    <div class="info-box">
                        <h3>» Proveedor</h3>
                        <div class="info-item">
                            <span class="info-label">RUC:</span>
                            <span class="info-value">' . ($compra->proveedor->ruc ?? 'N/A') . '</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Razón Social:</span>
                            <span class="info-value">' . ($compra->proveedor->razon_social ?? 'N/A') . '</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Dirección:</span>
                            <span class="info-value">' . ($compra->proveedor->direccion ?? 'N/A') . '</span>
                        </div>
                    </div>
                    
                    <div class="info-box">
                        <h3>» Compra</h3>
                        <div class="info-item">
                            <span class="info-label">F. Emisión:</span>
                            <span class="info-value">' . $compra->fecha_emision->format('d/m/Y') . '</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">F. Vencimiento:</span>
                            <span class="info-value">' . $compra->fecha_vencimiento->format('d/m/Y') . '</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Moneda:</span>
                            <span class="info-value">' . ($compra->moneda === 'S' ? 'Soles (PEN)' : 'Dólares (USD)') . '</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Usuario:</span>
                            <span class="info-value">' . ($compra->usuario->first_name ?? 'N/A') . ' ' . ($compra->usuario->last_name ?? '') . '</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Estado:</span>
                            <span class="info-value">
                                <span class="status-badge ' . ($compra->estado_recepcion === 'completo' ? 'status-completo' : 'status-pendiente') . '">
                                    ' . ($compra->estado_recepcion === 'completo' ? 'Recepcionado' : 'Pendiente') . '
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- PRODUCTOS -->
                <div class="table-section">
                    <div class="section-title">» Productos Adquiridos</div>
                    <div class="table-container">
                        <table class="compact-table">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-right">P. Unit.</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>';

    $subtotalGeneral = 0;
    foreach ($compra->productosCompras as $pc) {
        $subtotalGeneral += $pc->subtotal;
        $html .= '
                                <tr>
                                    <td class="fw-bold">' . ($pc->producto->codigo ?: $pc->producto->cod_barra) . '</td>
                                    <td>' . $pc->producto->nombre . '</td>
                                    <td class="text-center">' . number_format($pc->cantidad, 2) . '</td>
                                    <td class="text-right font-mono">' . ($compra->moneda === 'S' ? 'S/ ' : 'USD ') . number_format($pc->precio, 2) . '</td>
                                    <td class="text-right font-mono fw-bold">' . ($compra->moneda === 'S' ? 'S/ ' : 'USD ') . number_format($pc->subtotal, 2) . '</td>
                                </tr>';
    }

    $html .= '
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TOTALES -->
                <div class="totals-section">
                    <div class="totals-header">$ Totales</div>
                    <div class="totals-body">
                        <div class="total-row">
                            <span class="total-label">Subtotal:</span>
                            <span class="total-value">' . ($compra->moneda === 'S' ? 'S/ ' : 'USD ') . number_format($subtotalGeneral, 2) . '</span>
                        </div>';
    
    if ($compra->tiene_igv === 'S' && $compra->igv > 0) {
        $html .= '
                        <div class="total-row">
                            <span class="total-label">IGV (18%):</span>
                            <span class="total-value">' . ($compra->moneda === 'S' ? 'S/ ' : 'USD ') . number_format($compra->igv, 2) . '</span>
                        </div>';
    }
    
    $html .= '
                        <div class="total-final">
                            <div class="total-row">
                                <span class="total-label">Total General</span>
                                <span class="total-value">' . ($compra->moneda === 'S' ? 'S/ ' : 'USD ') . number_format($compra->total, 2) . '</span>
                            </div>
                        </div>';
    
    if ($compra->moneda === 'D' && $compra->total_soles) {
        $html .= '
                        <div class="total-row">
                            <span class="total-label">Equivalente en Soles:</span>
                            <span class="total-value">S/ ' . number_format($compra->total_soles, 2) . '</span>
                        </div>';
    }
    
    $html .= '
                    </div>
                </div>';

    // CRONOGRAMA DE PAGOS
    if ($compra->diasCompras->count() > 0) {
        $html .= '
                <div class="table-section">
                    <div class="section-title">» Cronograma de Pagos</div>
                    <div class="table-container">
                        <table class="compact-table">
                            <thead>
                                <tr>
                                    <th>Fecha de Pago</th>
                                    <th class="text-right">Monto</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>';

        foreach ($compra->diasCompras as $dia) {
            $html .= '
                                <tr>
                                    <td>' . $dia->fecha->format('d/m/Y') . '</td>
                                    <td class="text-right font-mono">' . ($compra->moneda === 'S' ? 'S/ ' : 'USD ') . number_format($dia->monto, 2) . '</td>
                                    <td class="text-center">
                                        <span class="status-badge ' . ($dia->estado === 'C' ? 'status-completo' : 'status-pendiente') . '">
                                            ' . ($dia->estado === 'C' ? 'Pagado' : 'Pendiente') . '
                                        </span>
                                    </td>
                                </tr>';
        }

        $html .= '
                            </tbody>
                        </table>
                    </div>
                </div>';
    }

    // OBSERVACIONES
    if ($compra->observaciones) {
        $html .= '
                <div class="observations">
                    <div class="observations-title">» Observaciones</div>
                    <div>' . $compra->observaciones . '</div>
                </div>';
    }

    $html .= '
            </div>
            
            <!-- FOOTER -->
            <div class="footer">
                <p><strong>Documento generado automáticamente • Sistema de Gestión de Compras</strong></p>
                <p>Válido sin firma • ' . now()->format('d/m/Y H:i:s') . '</p>
            </div>
        </div>
    </body>
    </html>';

    return $html;
}

}
