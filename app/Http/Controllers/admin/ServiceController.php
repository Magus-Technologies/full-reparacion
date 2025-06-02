<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('date_created', 'desc')->get();
        return view('admin.services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $service = new Service();
        $service->service = $request->service;
        $service->description = $request->description;
        $service->cost = $request->cost;
        $service->date_created = now();
        $service->save();

        return redirect()->route('admin.services.index')
            ->with('success', 'Servicio creado correctamente');
    }

    public function show($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services._show', compact('service'));
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services._edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'service' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $service = Service::findOrFail($id);
        $service->service = $request->service;
        $service->description = $request->description;
        $service->cost = $request->cost;
        $service->date_updated = now();
        $service->save();

        return response()->json(['message' => 'Servicio actualizado correctamente']);
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete_flag = 1;
        $service->date_updated = now();
        $service->save();

        return redirect()->route('admin.services.index')
            ->with('success', 'Servicio eliminado correctamente');
    }
}