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
                'nombre', 
                'unidad',
                'precio_unidad',
                'cantidad',
                'categoria'
            ]);

        return DataTables::of($productos)
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
            ->rawColumns(['precios', 'stock', 'editar', 'eliminar'])
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
                $imagen->storeAs('public/productos', $nombreImagen);
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

    // Obtener información de un producto
    public function show($id)
    {
        try {
            $producto = Producto::with(['precios', 'categoriaRelacion', 'unidadRelacion'])
                ->findOrFail($id);

            return response()->json([
                'res' => true,
                'data' => $producto
            ]);
        } catch (\Exception $e) {
            return response()->json(['res' => false, 'error' => 'Producto no encontrado'], 404);
        }
    }

    // Actualizar producto
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
                    Storage::delete('public/productos/' . $producto->imagen);
                }

                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                $imagen->storeAs('productos', $nombreImagen, 'public');
                $data['imagen'] = $nombreImagen;
            }

            // Configurar valores
            $data['precio_unidad'] = $data['precio'];
            $data['iscbp'] = $request->get('afecto', 0);
            $data['usar_multiprecio'] = $request->boolean('usar_multiprecio');
            $data['usar_barra'] = $request->boolean('usar_barra');
            // Mapeo correcto de precios - con debug
            $precio1 = $request->get('precio1', 0);
            $precio2 = $request->get('precio2', 0);

            // Debug para verificar qué llega
            \Log::info('Precios recibidos:', [
                'precio1' => $precio1,
                'precio2' => $precio2,
                'request_all' => $request->all()
            ]);

            $data['precio_menor'] = floatval($precio1);  // Precio Distribuidor → precio_menor
            $data['precio_mayor'] = floatval($precio2);  // Precio Mayorista → precio_mayor

            // Asegurar que codSunat y unidad se actualicen correctamente  
            $data['codsunat'] = $request->get('codSunat', '');
            $data['unidad'] = $request->get('unidad', '');

            // Generar código de barras - corregido para update
            if ($request->get('usar_barra') == '1' || $request->boolean('usar_barra')) {
                $data['cod_barra'] = $data['codigo'];
            } else {
                // Si no está habilitado, usar código manual
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

    // Obtener precios de un producto
    public function getPrecios($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            
            return response()->json([
                'precio' => $producto->precio,
                'precio2' => $producto->precio2,
                'precio3' => $producto->precio3,
                'precio4' => $producto->precio4,
                'precio_unidad' => $producto->precio_unidad
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
}
