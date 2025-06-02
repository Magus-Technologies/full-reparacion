<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Repair;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RepairController extends Controller
{
    public function index()
    {
        $repairs = Repair::with('client')->orderBy('date_created', 'desc')->get();
        return view('admin.repairs.index', compact('repairs'));
    }

    public function create()
    {
        $clients = Client::where('delete_flag', 0)->get();
        $services = Service::where('delete_flag', 0)->get();
        return view('admin.repairs.create', compact('clients', 'services'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Generar código único
            $code = 'R-' . date('Ym') . sprintf('%04d', Repair::count() + 1);

            $repair = new Repair();
            $repair->code = $code;
            $repair->client_id = $request->client_id;
            $repair->remarks = $request->remarks;
            $repair->notes = $request->notes;
            $repair->total_amount = $request->total_amount;
            $repair->discount = $request->discount ?? 0;
            $repair->payment_status = $request->payment_status;
            $repair->status = $request->status;
            $repair->advance = $request->advance;
            $repair->date_created = now();
            $repair->save();

            // Guardar servicios
            if ($request->has('service_id')) {
                foreach ($request->service_id as $key => $service_id) {
                    $repair->services()->attach($service_id, [
                        'fee' => $request->fee[$key],
                        'status' => 0
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Reparación creada correctamente',
                'id' => $repair->id
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear la reparación: ' . $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        $repair = Repair::with(['client', 'services'])->findOrFail($id);
        return view('admin.repairs.show', compact('repair'));
    }

    public function edit($id)
    {
        $repair = Repair::with(['client', 'services'])->findOrFail($id);
        $clients = Client::where('delete_flag', 0)->get();
        $services = Service::where('delete_flag', 0)->get();
        return view('admin.repairs.edit', compact('repair', 'clients', 'services'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $repair = Repair::findOrFail($id);
            $repair->client_id = $request->client_id;
            $repair->remarks = $request->remarks;
            $repair->notes = $request->notes;
            $repair->total_amount = $request->total_amount;
            $repair->discount = $request->discount ?? 0;
            $repair->payment_status = $request->payment_status;
            $repair->status = $request->status;
            $repair->advance = $request->advance;
            $repair->save();

            // Actualizar servicios
            $repair->services()->detach();
            if ($request->has('service_id')) {
                foreach ($request->service_id as $key => $service_id) {
                    $repair->services()->attach($service_id, [
                        'fee' => $request->fee[$key],
                        'status' => 0
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Reparación actualizada correctamente',
                'id' => $repair->id
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar la reparación: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $repair = Repair::findOrFail($id);
            $repair->services()->detach();
            $repair->delete();

            return redirect()->route('admin.repairs.index')
                ->with('success', 'Reparación eliminada correctamente');
        } catch (\Exception $e) {
            return redirect()->route('admin.repairs.index')
                ->with('error', 'Error al eliminar la reparación: ' . $e->getMessage());
        }
    }

    public function getClientDNI($id)
    {
        $client = Client::findOrFail($id);
        return response()->json(['dni' => $client->documentid]);
    }

    public function getServiceDetails($id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }
}