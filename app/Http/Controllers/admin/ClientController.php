<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    private $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6InN5c3RlbWNyYWZ0LnBlQGdtYWlsLmNvbSJ9.yuNS5hRaC0hCwymX_PjXRoSZJWLNNBeOdlLRSUGlHGA';

    public function index()
    {
        $clients = Client::orderBy('date_created', 'desc')->get();
        return view('admin.clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'documentid' => 'required|string|max:8',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $client = new Client();
        $client->documentid = $request->documentid;
        $client->firstname = $request->firstname;
        $client->middlename = $request->middlename;
        $client->lastname = $request->lastname;
        $client->contact = $request->contact;
        $client->email = $request->email;
        $client->address = $request->address;
        $client->date_created = now();
        $client->save();

        return response()->json([
            'message' => 'Cliente agregado correctamente',
            'client' => $client
        ]);
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('admin.clients._edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'documentid' => 'required|string|max:8',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $client = Client::findOrFail($id);
        $client->documentid = $request->documentid;
        $client->firstname = $request->firstname;
        $client->middlename = $request->middlename;
        $client->lastname = $request->lastname;
        $client->contact = $request->contact;
        $client->email = $request->email;
        $client->address = $request->address;
        $client->date_updated = now();
        $client->save();

        return response()->json([
            'message' => 'Cliente actualizado correctamente',
            'client' => $client
        ]);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete_flag = 1;
        $client->date_updated = now();
        $client->save();

        return redirect()->route('admin.clients.index')
            ->with('success', 'Cliente eliminado correctamente');
    }

    public function searchDNI($dni)
    {
        try {
            $url = 'https://dniruc.apisperu.com/api/v1/dni/' . $dni . '?token=' . $this->token;
        
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false
            ]);
        
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                throw new \Exception('Error en la petición cURL: ' . $err);
            }

            $data = json_decode($response, true);
        
            if (isset($data['error'])) {
                return response()->json(['error' => $data['error']], 404);
            }

            return response()->json($data);
        
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al consultar el DNI: ' . $e->getMessage()], 500);
        }
    }
}