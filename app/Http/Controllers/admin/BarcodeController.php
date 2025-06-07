<?php

namespace App\Http\Controllers\admin;
use Milon\Barcode\DNS1D;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    public function generate(Request $request)
    {
        $code = $request->get('code', '');

        if (empty($code)) {
            return response('Código requerido', 400);
        }

        try {
            $barcode = new DNS1D();

            // Generar código de barras PNG en base64 (C128, ancho 2, alto 60)
            $pngData = $barcode->getBarcodePNG($code, 'C128', 2, 60);

            // Decodificar base64 a binario
            $png = base64_decode($pngData);

            // Devolver respuesta con contenido tipo imagen PNG
            return response($png)->header('Content-Type', 'image/png');

        } catch (\Exception $e) {
            return response('Error generando código de barras', 500);
        }
    }

}
