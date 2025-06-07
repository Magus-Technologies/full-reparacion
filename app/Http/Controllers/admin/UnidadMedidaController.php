<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnidadMedidaController extends Controller
{
    public function index()
    {
        $unidades = DB::table('unidades_medida')->get();
        return view('admin.unidades.index', compact('unidades'));
    }

    // Métodos AJAX para Unidades de Medida
    public function getUnidades()
    {
        try {
            $unidades = DB::table('unidades_medida')->select('id', 'nombre')->get();
            return response()->json($unidades);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener unidades'], 500);
        }
    }

    public function getOneUnidad(Request $request)
    {
        try {
            $unidad = DB::table('unidades_medida')
                ->where('id', $request->id)
                ->select('id', 'nombre')
                ->get();
            
            return response()->json($unidad);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener la unidad'], 500);
        }
    }

    public function saveUnidad(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255'
            ]);

            DB::table('unidades_medida')->insert([
                'nombre' => $request->nombre,
                'creado_el' => now()
            ]);

            return response()->json(['success' => 'Unidad guardada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al guardar la unidad'], 500);
        }
    }

    public function updateUnidad(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer',
                'nombre' => 'required|string|max:255'
            ]);

            DB::table('unidades_medida')
                ->where('id', $request->id)
                ->update([
                    'nombre' => $request->nombre
                ]);

            return response()->json(['success' => 'Unidad actualizada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la unidad'], 500);
        }
    }

    public function deleteUnidad(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer'
            ]);

            DB::table('unidades_medida')
                ->where('id', $request->id)
                ->delete();

            return response()->json(['success' => 'Unidad eliminada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la unidad'], 500);
        }
    }

    // Métodos para Unidades de Repuestos
    public function getUnidadesRepuesto()
    {
        try {
            $unidades = DB::table('unidades_repuestos')->select('id', 'nombre')->get();
            return response()->json($unidades);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener unidades de repuesto'], 500);
        }
    }

    public function getOneUnidadRepuesto(Request $request)
    {
        try {
            $unidad = DB::table('unidades_repuestos')
                ->where('id', $request->id)
                ->select('id', 'nombre')
                ->get();
            
            return response()->json($unidad);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener la unidad de repuesto'], 500);
        }
    }

    public function saveUnidadRepuesto(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255'
            ]);

            DB::table('unidades_repuestos')->insert([
                'nombre' => $request->nombre
            ]);

            return response()->json(['success' => 'Unidad de repuesto guardada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al guardar la unidad de repuesto'], 500);
        }
    }

    public function updateUnidadRepuesto(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer',
                'nombre' => 'required|string|max:255'
            ]);

            DB::table('unidades_repuestos')
                ->where('id', $request->id)
                ->update([
                    'nombre' => $request->nombre
                ]);

            return response()->json(['success' => 'Unidad de repuesto actualizada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la unidad de repuesto'], 500);
        }
    }

    public function deleteUnidadRepuesto(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer'
            ]);

            DB::table('unidades_repuestos')
                ->where('id', $request->id)
                ->delete();

            return response()->json(['success' => 'Unidad de repuesto eliminada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la unidad de repuesto'], 500);
        }
    }
}
