<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    public function index()
    {
        return view('admin.categoria.index');

    }

    // NUEVOS MÉTODOS - Agregar después de index()
    public function getCategorias()
    {
        $categorias = DB::table('categorias')->select('id', 'nombre')->get();
        return response()->json($categorias);
    }

    public function saveCategoria(Request $request)
    {
        DB::table('categorias')->insert([
            'nombre' => $request->nombre,
        ]);
        
        return response()->json(['success' => true]);
    }

    public function getOneCategoria(Request $request)
    {
        $categoria = DB::table('categorias')
            ->where('id', $request->id)
            ->select('id', 'nombre')
            ->get();
            
        return response()->json($categoria);
    }

    public function updateCategoria(Request $request)
    {
        DB::table('categorias')
            ->where('id', $request->id)
            ->update([
                'nombre' => $request->nombre,
            ]);
            
        return response()->json(['success' => true]);
    }

    public function deleteCategoria(Request $request)
    {
        DB::table('categorias')->where('id', $request->id)->delete();
        return response()->json(['success' => true]);
    }

}
