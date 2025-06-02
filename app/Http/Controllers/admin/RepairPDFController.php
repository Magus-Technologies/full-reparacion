<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Repair;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RepairPDFController extends Controller
{
    public function generatePDF($id)
    {
        $repair = Repair::with(['client', 'services'])->findOrFail($id);
        
        $pdf =PDF::loadView('admin.repairs.pdf', compact('repair'));
        
        return $pdf->stream('repair-' . $repair->code . '.pdf');
    }

    public function shareWhatsApp($id)
    {
        $repair = Repair::with('client')->findOrFail($id);
        
        if (!$repair->client->contact) {
            return response()->json([
                'status' => 'error',
                'message' => 'El cliente no tiene número de teléfono registrado'
            ]);
        }

        $pdfUrl = route('admin.repairs.pdf', $repair->id);
        $phone = $repair->client->contact;
        
        // Asegurarse que el número tenga el formato correcto
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) <= 9) {
            $phone = '51' . $phone; // Agregar código de país para Perú
        }
        
        $message = "Descarga tu comprobante de servicio en el siguiente enlace: " . $pdfUrl;
        $whatsappUrl = "https://api.whatsapp.com/send?phone=" . $phone . "&text=" . urlencode($message);
        
        return response()->json([
            'status' => 'success',
            'url' => $whatsappUrl
        ]);
    }
}