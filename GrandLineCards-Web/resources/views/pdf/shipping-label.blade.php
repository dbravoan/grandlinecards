<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #d4af37; /* Grand Gold approx */
            text-transform: uppercase;
        }
        .meta {
            margin-top: 5px;
            font-size: 10px;
            color: #555;
        }
        .addresses {
            width: 100%;
            margin-bottom: 20px;
        }
        .address-box {
            width: 45%;
            float: left;
            border: 1px solid #ccc;
            padding: 10px;
            min-height: 100px;
        }
        .address-box.right {
            float: right;
        }
        .title {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            margin-bottom: 5px;
            color: #888;
        }
        .tracking {
            clear: both;
            text-align: center;
            padding: 20px 0;
            border: 2px dashed #000;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: bold;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
        }
        .items th, .items td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items th {
            background-color: #f2f2f2;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Grand Line Cards</div>
        <div class="meta">Documento Oficial de Envío | {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <div class="addresses">
        <div class="address-box">
            <div class="title">Remitente (Sender)</div>
            @if($sender)
                <strong>{{ $sender['name'] }}</strong><br>
                {{ $sender['address'] ?? 'Dirección desconocida' }}<br>
                {{ $sender['city'] ?? '' }} {{ $sender['postal_code'] ?? '' }}
            @else
                <strong>Grand Line Cards - The Vault</strong><br>
                Puerto Central de Logística<br>
                Grand Line, New World
            @endif
        </div>
        <div class="address-box right">
            <div class="title">Destinatario (Receiver)</div>
             @if($receiver)
                <strong>{{ $receiver['name'] }}</strong><br>
                {{ $receiver['address'] ?? 'Dirección desconocida' }}<br>
                {{ $receiver['city'] ?? '' }} {{ $receiver['postal_code'] ?? '' }}
            @else
                 <strong>Grand Line Cards - The Vault</strong><br>
                Puerto Central de Logística<br>
                Grand Line, New World
            @endif
        </div>
    </div>

    <div class="tracking">
        TRACKING / ID: {{ $tracking_number }}
        <div style="font-size: 10px; font-weight: normal; margin-top: 5px;">{{ $carrier }}</div>
    </div>

    <h3>Contenido del Paquete</h3>
    <table class="items">
        <thead>
            <tr>
                <th>Item ID</th>
                <th>Carta</th>
                <th>Estado/Condición</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->marketListing->card->card_id }}</td>
                <td>{{ $item->marketListing->card->name }}</td>
                <td>{{ $item->marketListing->condition }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Grand Line Cards S.L. - La plataforma #1 de One Piece TCG | Gracias por tu confianza.
    </div>
</body>
</html>
