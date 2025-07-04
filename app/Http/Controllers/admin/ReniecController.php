<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class ReniecController extends Controller
{
    private $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6InN5c3RlbWNyYWZ0LnBlQGdtYWlsLmNvbSJ9.yuNS5hRaC0hCwymX_PjXRoSZJWLNNBeOdlLRSUGlHGA';

    public function buscarDocumento(Request $request)
    {
        // Validar la entrada
        $validator = Validator::make($request->all(), [
            'documento' => 'required|string|min:8|max:11'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Documento inválido',
                'errors' => $validator->errors()
            ], 400);
        }

        $documento = trim($request->input('documento'));
        
        try {
            // Determinar si es DNI (8 dígitos) o RUC (11 dígitos)
            if (strlen($documento) == 8) {
                $url = "https://dniruc.apisperu.com/api/v1/dni/{$documento}?token={$this->token}";
            } elseif (strlen($documento) == 11) {
                $url = "https://dniruc.apisperu.com/api/v1/ruc/{$documento}?token={$this->token}";
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'El documento debe tener 8 dígitos (DNI) o 11 dígitos (RUC)'
                ], 400);
            }

            // Realizar la petición a la API
            $response = Http::timeout(10)->get($url);

            if ($response->successful()) {
                $data = $response->json();
                
                // Verificar si la respuesta tiene datos válidos
                // Para DNI: verifica success = true
                // Para RUC: verifica que tenga razonSocial
                $esValidoDNI = isset($data['success']) && $data['success'] === true;
                $esValidoRUC = strlen($documento) == 11 && isset($data['razonSocial']) && !empty($data['razonSocial']);

                if ($esValidoDNI || $esValidoRUC) {
                    $resultado = $data;
                    
                    // Formatear el nombre dependiendo del tipo de documento
                    if (strlen($documento) == 8) {
                        // Para DNI: concatenar nombres y apellidos
                        $nombre = trim(
                            ($resultado['nombres'] ?? '') . ' ' . 
                            ($resultado['apellidoPaterno'] ?? '') . ' ' . 
                            ($resultado['apellidoMaterno'] ?? '')
                        );
                    } else {
                        // Para RUC: usar razón social
                        $nombre = $resultado['razonSocial'] ?? '';
                    }

                    // Validar que se obtuvo un nombre
                    if (empty($nombre)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No se encontraron datos para el documento ingresado'
                        ], 404);
                    }

                    return response()->json([
                        'success' => true,
                        'data' => [
                            'documento' => $documento,
                            'nombre' => $nombre,
                            'tipo' => strlen($documento) == 8 ? 'DNI' : 'RUC',
                            'datos_completos' => $resultado
                        ]
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se encontraron datos para el documento ingresado'
                    ], 404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al consultar la API externa'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }
}
