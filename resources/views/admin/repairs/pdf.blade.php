<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Servicio #{{ $repair->code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            width: 100%;
            margin-bottom: 20px;
        }
        .header img {
            width: 90px;
            height: 90px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
        }
        .service-number {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
        }
        th {
            background-color: #333;
            color: white;
            font-size: 10px;
            text-align: center;
        }
        .totals {
            margin-top: 20px;
        }
        .notes {
            margin-top: 20px;
            font-size: 10px;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: justify;
        }
    </style>
</head>
<body> 
    <div class="header">
        <table>
            <tr>
                <td width="20%">
                    <img src="{{ public_path('img/logo.png') }}" alt="Logo">
                </td>
                <td width="80%" style="text-align: center;">
                    <h2>Servicio # {{ $repair->code }}</h2>
                </td>
            </tr>
        </table>
        <div class="company-name">{{ config('app.name') }}</div>
    </div>

    <table>
        <tr>
            <th>R.U.C. / D.N.I.</th>
            <th>CLIENTE</th>
            <th>TELÉFONO</th>
        </tr>
        <tr>
            <td style="text-align: center;">{{ $repair->client->documentid }}</td>
            <td style="text-align: center;">{{ $repair->client->firstname }} {{ $repair->client->middlename }} {{ $repair->client->lastname }}</td>
            <td style="text-align: center;">{{ $repair->client->contact }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <th>#</th>
            <th>SERVICIO</th>
            <th>COSTO</th>
        </tr>
        @foreach($repair->services as $index => $service)
        <tr>
            <td style="text-align: center;">{{ $index + 1 }}</td>
            <td style="text-align: center;">{{ $service->service }}</td>
            <td style="text-align: center;">{{ number_format($service->pivot->fee, 2) }}</td>
        </tr>
        @endforeach
    </table>

    <table>
        <tr>
            <td rowspan="3" width="70%">
                <strong>Observaciones:</strong><br>
                {{ $repair->remarks }}
            </td>
            <td style="background-color: #333; color: white; text-align: center;" colspan="2">TOTALES</td>
        </tr>
        <tr>
            <td style="background-color: #333; color: white; text-align: center;">Total Abonado:</td>
            <td style="text-align: center;">{{ number_format($repair->advance, 2) }}</td>
        </tr>
        <tr>
            <td style="background-color: #333; color: white; text-align: center;">Total a Pagar:</td>
            <td style="text-align: center;">{{ number_format($repair->total_amount - $repair->advance, 2) }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td>
                <strong>Estado:</strong><br>
                {{ $repair->notes }}
            </td>
        </tr>
    </table>

    <div class="footer">
        <strong>NOTAS:</strong> El equipo deberá ser recogido en un plazo máximo de 90 días. Luego de haberse emitido el diagnostico y/o reparación, de otro modo según lo estipulado en el código civil Art. 1333, 1123: será considerada mercadería en abandono, liberando a {{ config('app.name') }} de su pérdida o deterioro.
    </div>
</body>
</html>