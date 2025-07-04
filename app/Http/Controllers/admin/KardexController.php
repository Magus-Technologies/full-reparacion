<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ProductoStoreRequest;
use App\Http\Requests\ProductoUpdateRequest;
use App\Models\Producto;
use App\Models\ProductoPrecio;
use App\Models\Categoria;
use App\Models\UnidadMedida;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
// Agrega esta línea después de: use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductosExport;
use App\Exports\ProductosImportTemplate;
use Illuminate\Support\Facades\Log;
use App\Imports\ProductosImport;



class KardexController extends Controller
{
    public function index()
    {
        // Aquí puedes pasar datos a la vista, por ahora solo carga la vista
        return view('admin.kardex.index');
    }

        // Obtener datos para DataTables
        public function getProductos(Request $request)
        {
            $productos = Producto::with(['categoriaRelacion', 'unidadRelacion'])
                ->activos()
                ->almacenPrincipal()
                ->select([
                    'id_producto',
                    'codigo',
                    'cod_barra', // <-- AÑADIDO AQUÍ
                    'nombre', 
                    'unidad',
                    'precio_unidad',
                    'cantidad',
                    'categoria'
                ]);

            // En la función getProductos(), REEMPLAZA toda la configuración de DataTables::of()
            return DataTables::of($productos)
                ->addColumn('codigo', function ($producto) {
                    // Usar cod_barra si existe, sino usar codigo como fallback
                    $codigoBarras = !empty($producto->cod_barra) ? $producto->cod_barra : $producto->codigo;
                    
                    return '<a href="#" onclick="event.preventDefault(); mostrarCodigoBarras(
                                \'' . $producto->id_producto . '\',
                                \'' . addslashes($producto->nombre) . '\',
                                \'' . $producto->codigo . '\',
                                \'' . $codigoBarras . '\',
                                \'' . $producto->precio_unidad . '\'
                            )" style="color: #0d6efd; text-decoration: underline;">
                            ' . $producto->codigo . '
                        </a>';
                })
                ->addColumn('unidades', function ($producto) {
                    return $producto->unidadRelacion ? $producto->unidadRelacion->nombre : 'Sin unidad';
                })
                ->addColumn('precios', function ($producto) {
                    return '<button
                                class="text-white text-sm px-3 py-1 hover:opacity-90 btn-ver-precios"  
                                style="background-color: #0d6efd; border-radius: 5px;" 
                                data-item="' . $producto->id_producto . '">
                                <i class="fa fa-eye"></i> Ver
                            </button>';
                })
                ->addColumn('stock', function ($producto) {
                    $clase = $producto->cantidad <= 5 ? 'text-danger' : 'text-success';
                    return '<span class="' . $clase . '">' . $producto->cantidad . '</span>';
                })
                ->addColumn('editar', function ($producto) {
                    return '<button class="btn btn-sm btn-info btn-edt" data-item="' . $producto->id_producto . '">
                                <i class="fa fa-edit"></i>
                            </button>';
                })
                ->addColumn('eliminar', function ($producto) {
                    return '<input type="checkbox" class="btnCheckEliminar" style="margin-left: 30px;" data-id="' . $producto->id_producto . '">';
                })
                ->rawColumns(['codigo', 'precios', 'stock', 'editar', 'eliminar']) // Agregué 'codigo' aquí
                ->make(true);

        }

    // Agregar nuevo producto
    public function store(ProductoStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            
            // Procesar imagen si existe
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
               // ¡IMPORTANTE! Forzamos a usar el disco 'public'
                Storage::disk('public')->putFileAs('productos', $imagen, $nombreImagen);
                $data['imagen'] = $nombreImagen;
            }

            // Configurar valores por defecto
            $data['id_empresa'] = 1;
            $data['sucursal'] = 1;
            $data['almacen'] = '1'; // Siempre almacén único
            $data['estado'] = '1';
            $data['ultima_salida'] = '1000-01-01';
            $data['precio_unidad'] = $data['precio'];
            // Corregir mapeo: precio1 (Distribuidor) → precio_menor, precio2 (Mayorista) → precio_mayor
            // Mapeo correcto de precios
            $data['precio_menor'] = $request->get('precio1', 0);  // Precio Distribuidor → precio_menor
            $data['precio_mayor'] = $request->get('precio2', 0);  // Precio Mayorista → precio_mayor
            $data['iscbp'] = $request->get('afecto', 0);
            $data['usar_multiprecio'] = $request->boolean('usar_multiprecio');

            $tipoControl = $request->get('tipo_producto_control', 'codigo_unico');
            $data['tipo_control'] = $tipoControl === 'codigo_unico' ? 'UNICO' : 'CANTIDAD';

            // Asegurar que codSunat y unidad se guarden correctamente
            $data['codsunat'] = $request->get('codSunat', ''); // codSunat del form → codsunat en BD
            $data['unidad'] = $request->get('unidades', '');   // unidades del form → unidad en BD

            // Generar código de barras - corregido
            if ($request->get('usar_barra') == '1' || $request->boolean('usar_barra')) {
                $data['cod_barra'] = $data['codigo'];
                $data['usar_barra'] = '1';
            } else {
                // Si no está habilitado, usar código manual
                $data['cod_barra'] = $request->get('cod_barra', ''); // Viene del FormData como 'cod_barra'
                $data['usar_barra'] = '0';
            }

            $producto = Producto::create($data);

            // Guardar precios múltiples si está habilitado
            if ($request->boolean('usar_multiprecio') && $request->has('precios')) {
                foreach ($request->precios as $precio) {
                    ProductoPrecio::create([
                        'id_producto' => $producto->id_producto,
                        'nombre' => $precio['nombre'],
                        'precio' => $precio['precio']
                    ]);
                }
            }

            DB::commit();
            return response()->json(['res' => true, 'message' => 'Producto agregado exitosamente']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['res' => false, 'error' => $e->getMessage()], 500);
        }
    }

   // Reemplazar el método show actual con este:
    public function show($id)
    {
        try {
            $producto = Producto::with(['precios', 'categoriaRelacion', 'unidadRelacion'])
                ->findOrFail($id);

            // Asegurar que los precios estén correctamente mapeados
            $producto->precio1 = $producto->precio_menor;
            $producto->precio2 = $producto->precio_mayor;

            // NUEVO: Asegurar que tipo_control esté disponible
            if (!$producto->tipo_control) {
                $producto->tipo_control = 'UNICO'; // Valor por defecto
            }

            return response()->json([
                'res' => true,
                'data' => $producto
            ]);
        } catch (\Exception $e) {
            return response()->json(['res' => false, 'error' => 'Producto no encontrado'], 404);
        }
    }

   // Modificar el método update para asegurar que se guarden correctamente los precios
    public function update(ProductoUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $producto = Producto::findOrFail($id);
            $data = $request->validated();

            // Procesar imagen si existe
            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior si existe
                if ($producto->imagen) {
                    Storage::disk('public')->delete('productos/' . $producto->imagen);
                }

                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                Storage::disk('public')->putFileAs('productos', $imagen, $nombreImagen);
                $data['imagen'] = $nombreImagen;
            } else if ($request->has('eliminar_imagen') && $request->eliminar_imagen) {
                // Si se solicita eliminar la imagen
                if ($producto->imagen) {
                    Storage::disk('public')->delete('productos/' . $producto->imagen);
                }
                $data['imagen'] = null;
            }

            // Configurar valores
            $data['precio_unidad'] = $data['precio'];
            $data['iscbp'] = $request->get('afecto', 0);
            $data['usar_multiprecio'] = $request->boolean('usar_multiprecio');
            $data['usar_barra'] = $request->boolean('usar_barra');

            // Mapear tipo_control basado en el switch del frontend
            $data['tipo_control'] = $request->boolean('es_codigo_unico_edit') ? 'UNICO' : 'CANTIDAD';
            
            // Mapeo correcto de precios
            $data['precio_menor'] = $request->get('precio1', 0);  // Precio Distribuidor → precio_menor
            $data['precio_mayor'] = $request->get('precio2', 0);  // Precio Mayorista → precio_mayor

            // Asegurar que codSunat y unidad se actualicen correctamente  
            $data['codsunat'] = $request->get('codSunat', '');
            $data['unidad'] = $request->get('unidades', '');
            
            // Siempre guardar almacén como 1
            $data['almacen'] = '1';

            // Buscar esta línea: // Generar código de barras
            // Y reemplazar esa sección por:
            // Manejar código de barras - MODIFICADO
            if ($request->boolean('usar_barra')) {
                $data['cod_barra'] = $data['codigo'];
            } else {
                $data['cod_barra'] = $request->get('cod_barra', '');
            }

            $producto->update($data);

            DB::commit();
            return response()->json(['res' => true, 'message' => 'Producto actualizado exitosamente']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['res' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // Eliminar productos (soft delete)
    public function destroy(Request $request)
    {
        try {
            $ids = collect($request->arrayId)->pluck('id');
            
            Producto::whereIn('id_producto', $ids)->update(['estado' => '0']);

            return response()->json(['res' => true, 'message' => 'Productos eliminados exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['res' => false, 'error' => $e->getMessage()], 500);
        }
    }

   // Reemplazar el método getPrecios actual con este:
    public function getPrecios($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $multiprecios = ProductoPrecio::where('id_producto', $id)->get();
            
            return response()->json([
                'precio' => $producto->precio,
                'precio_menor' => $producto->precio_menor,
                'precio_mayor' => $producto->precio_mayor,
                'precio_unidad' => $producto->precio_unidad,
                'multiprecios' => $multiprecios
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }
    }

    // Actualizar precios
    public function updatePrecios(Request $request)
    {
        try {
            $producto = Producto::findOrFail($request->cod_prod);
            
            $producto->update([
                'precio' => $request->precio,
                'precio_unidad' => $request->precio_unidad,
                'precio2' => $request->precio2,
                'precio3' => $request->precio3,
                'precio4' => $request->precio4
            ]);

            return response()->json(['res' => true, 'message' => 'Precios actualizados']);
        } catch (\Exception $e) {
            return response()->json(['res' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // Agregar stock
    public function addStock(Request $request)
    {
        try {
            $producto = Producto::findOrFail($request->cod);
            $producto->increment('cantidad', $request->cantidad);

            return response()->json(['res' => true, 'message' => 'Stock actualizado']);
        } catch (\Exception $e) {
            return response()->json(['res' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // Guardar precios múltiples
    public function savePrecios(Request $request)
    {
        try {
            DB::beginTransaction();

            $idProducto = $request->id_producto;
            
            // Eliminar precios existentes
            ProductoPrecio::where('id_producto', $idProducto)->delete();

            // Insertar nuevos precios
            foreach ($request->precios as $precio) {
                ProductoPrecio::create([
                    'id_producto' => $idProducto,
                    'nombre' => $precio['nombre'],
                    'precio' => $precio['precio']
                ]);
            }

            // Actualizar flag de multiprecio
            Producto::where('id_producto', $idProducto)->update(['usar_multiprecio' => '1']);

            DB::commit();
            return response()->json(['res' => true, 'message' => 'Precios guardados exitosamente']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['res' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // Obtener precios múltiples
    public function getMultiPrecios($id)
    {
        try {
            $precios = ProductoPrecio::where('id_producto', $id)->get();
            
            return response()->json([
                'res' => true,
                'precios' => $precios
            ]);
        } catch (\Exception $e) {
            return response()->json(['res' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // Exportar productos a Excel
    public function exportExcel()
    {
        try {
            return Excel::download(new ProductosExport, 'productos_reporte.xlsx');
        } catch (\Exception $e) {
            return response()->json(['res' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Descargar plantilla Excel para importación
     */
    public function downloadTemplate()
    {
        try {
            return Excel::download(new ProductosImportTemplate, 'plantilla_productos.xlsx');
        } catch (\Exception $e) {
            return response()->json(['res' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Procesar archivo Excel importado
     */

    public function processImport(Request $request)
    {
        try {
            $request->validate([
                'archivo' => 'required|file|mimes:xlsx,xls,csv'
            ]);

            $archivo = $request->file('archivo');
            $datos = Excel::toArray(new ProductosImport, $archivo);
            
            if (empty($datos) || empty($datos[0])) {
                return response()->json(['res' => false, 'error' => 'El archivo está vacío o no tiene el formato correcto'], 400);
            }

            // Procesar datos del Excel
            $productos = [];
            $errores = [];
            $filas = $datos[0];
            
            // Saltar la primera fila (headers)
            array_shift($filas);

            foreach ($filas as $index => $fila) {
                $fila_num = $index + 2; // +2 porque saltamos header y empezamos desde 1
                
                // Validar que la fila no esté vacía
                if (empty(array_filter($fila))) {
                    continue;
                }

                // Mapear columnas del Excel
                $producto = [
                    'nombre' => $fila[0] ?? '',
                    'precio' => floatval($fila[1] ?? 0),
                    'costo' => floatval($fila[2] ?? 0), 
                    'cantidad' => intval($fila[3] ?? 0),
                    'iscbp' => $this->procesarIscbp($fila[4] ?? ''),
                    'codsunat' => $fila[5] ?? '',
                    'precio_mayor' => floatval($fila[6] ?? 0), // Precio Mayorista
                    'precio_menor' => floatval($fila[7] ?? 0), // Precio Distribuidor
                    'precio_unidad' => floatval($fila[8] ?? 0),
                    'codigo' => $fila[9] ?? '',
                    'detalle' => $fila[10] ?? '',
                    'categoria_nombre' => $fila[11] ?? '',
                    'descripcion' => $fila[12] ?? '',
                    'unidad_nombre' => $fila[13] ?? '',
                    'fila' => $fila_num
                ];

                // Validaciones básicas
                if (empty($producto['nombre'])) {
                    $errores[] = "Fila {$fila_num}: El nombre es obligatorio";
                    continue;
                }

                if (empty($producto['codigo'])) {
                    $errores[] = "Fila {$fila_num}: El código es obligatorio";
                    continue;
                }

                $productos[] = $producto;
            }

            if (!empty($errores)) {
                return response()->json(['res' => false, 'errores' => $errores], 400);
            }

            return response()->json([
                'res' => true, 
                'productos' => $productos,
                'total' => count($productos)
            ]);

        } catch (\Exception $e) {
            return response()->json(['res' => false, 'error' => $e->getMessage()], 500);
        }
    }
  

    /**
     * Confirmar importación de productos
     */
    public function confirmImport(Request $request)
    {
        try {
            DB::beginTransaction();

            $productos = $request->productos;
            $resultados = [
                'insertados' => 0,
                'actualizados' => 0,
                'errores' => []
            ];

            foreach ($productos as $producto) {
                // Verificar si el producto ya existe por código
                $existente = Producto::where('codigo', $producto['codigo'])->first();

                // Procesar categoría
                $categoria_id = null;
                if (!empty($producto['categoria_nombre'])) {
                    $categoria = Categoria::where('nombre', $producto['categoria_nombre'])->first();
                    if (!$categoria) {
                        $resultados['errores'][] = "Fila {$producto['fila']}: Categoría '{$producto['categoria_nombre']}' no existe";
                        continue;
                    }
                    $categoria_id = $categoria->id;
                }

                // Procesar unidad de medida
                $unidad_id = null;
                if (!empty($producto['unidad_nombre'])) {
                    $unidad = UnidadMedida::where('nombre', $producto['unidad_nombre'])->first();
                    if (!$unidad) {
                        $resultados['errores'][] = "Fila {$producto['fila']}: Unidad '{$producto['unidad_nombre']}' no existe";
                        continue;
                    }
                    $unidad_id = $unidad->id;
                }

                // Preparar datos para inserción/actualización
                $datos = [
                    'nombre' => $producto['nombre'],
                    'precio' => $producto['precio'],
                    'costo' => $producto['costo'],
                    'cantidad' => $producto['cantidad'],
                    'iscbp' => $producto['iscbp'],
                    'codsunat' => $producto['codsunat'],
                    'precio_mayor' => $producto['precio_mayor'],
                    'precio_menor' => $producto['precio_menor'], 
                    'precio_unidad' => $producto['precio_unidad'],
                    'codigo' => $producto['codigo'],
                    'detalle' => $producto['detalle'],
                    'descripcion' => $producto['descripcion'],
                    'categoria' => $categoria_id,
                    'unidad' => $unidad_id,
                    // Valores por defecto
                    'id_empresa' => 1,
                    'sucursal' => 1,
                    'almacen' => '1',
                    'estado' => '1',
                    'ultima_salida' => '1000-01-01',
                    'cod_barra' => $producto['codigo'], // Usar código como código de barras por defecto
                    'usar_barra' => '1',
                    'usar_multiprecio' => '0'
                ];

                if ($existente) {
                    // Actualizar producto existente
                    $existente->update($datos);
                    $resultados['actualizados']++;
                } else {
                    // Crear nuevo producto
                    Producto::create($datos);
                    $resultados['insertados']++;
                }
            }

            DB::commit();

            return response()->json([
                'res' => true,
                'mensaje' => 'Importación completada exitosamente',
                'resultados' => $resultados
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['res' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Procesar valor de ISCBP desde Excel
     */
    private function procesarIscbp($valor)
    {
        if (empty($valor)) {
            return 0;
        }

        $valor = strtolower(trim($valor));
        
        // Eliminar tildes y espacios
        $valor = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $valor);
        
        if (in_array($valor, ['si', 'sí', '1', 'true', 'yes'])) {
            return 1;
        }
        
        return 0;
    }

    /**
     * Obtener categorías para la plantilla
     */
    public function getCategorias()
    {
        try {
            $categorias = Categoria::select('id', 'nombre')->get();
            return response()->json(['res' => true, 'categorias' => $categorias]);
        } catch (\Exception $e) {
            return response()->json(['res' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtener unidades de medida para la plantilla
     */
    public function getUnidades()
    {
        try {
            $unidades = UnidadMedida::select('id', 'nombre')->get();
            return response()->json(['res' => true, 'unidades' => $unidades]);
        } catch (\Exception $e) {
            return response()->json(['res' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
