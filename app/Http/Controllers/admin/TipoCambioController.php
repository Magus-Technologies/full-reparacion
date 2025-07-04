<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipoCambioController extends Controller
{
    public function obtenerTipoCambio()
    {
        $token = 'apis-token-12676.06vC22lNLuV4uUGX4CsxHcdKf2tT92T8';
        $url = 'https://api.apis.net.pe/v2/sunat/tipo-cambio';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => array(
                'Referer: https://apis.net.pe/tipo-de-cambio-sunat-api',
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);

        if (!$data || !isset($data['precioVenta'])) {
            return response()->json(['error' => 'No se pudo obtener el tipo de cambio'], 500);
        }

        return response()->json(['tipo_cambio' => $data['precioVenta']]);
    }
}
