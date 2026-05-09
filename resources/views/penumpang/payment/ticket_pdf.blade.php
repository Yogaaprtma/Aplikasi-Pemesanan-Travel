<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket: {{ $payment->booking->booking_code }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .ticket-container {
            border: 2px dashed #0056b3;
            border-radius: 10px;
            padding: 30px;
            max-width: 700px;
            margin: 0 auto;
            position: relative;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #0056b3;
            margin: 0 0 5px 0;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header p {
            margin: 0;
            color: #777;
            font-size: 14px;
        }
        .ticket-body {
            width: 100%;
            border-collapse: collapse;
        }
        .ticket-body td {
            vertical-align: top;
        }
        .info-section {
            width: 70%;
        }
        .qr-section {
            width: 30%;
            text-align: right;
        }
        .info-box {
            margin-bottom: 15px;
        }
        .info-label {
            font-size: 12px;
            color: #777;
            text-transform: uppercase;
            margin-bottom: 3px;
            display: block;
        }
        .info-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        .route-box {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .route-title {
            font-size: 20px;
            font-weight: bold;
            color: #0056b3;
            margin: 0;
        }
        .route-time {
            font-size: 14px;
            color: #555;
            margin-top: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .badge {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 10px;
        }
        .vehicle-info {
            display: inline-block;
            background: #e9ecef;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 13px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="ticket-container">
    <div class="header">
        <h1>Travel Booking</h1>
        <p>Official Electronic Ticket</p>
    </div>

    <table class="ticket-body">
        <tr>
            <td class="info-section">
                <div class="badge">PAID / LUNAS</div>
                
                <div class="info-box">
                    <span class="info-label">Kode Booking</span>
                    <p class="info-value" style="font-size: 20px; color: #0056b3;">{{ $payment->booking->booking_code }}</p>
                </div>

                <div class="info-box">
                    <span class="info-label">Nama Penumpang</span>
                    <p class="info-value">{{ $payment->booking->passenger_name }}</p>
                </div>

                <div class="info-box">
                    <span class="info-label">Nomor Telepon</span>
                    <p class="info-value">{{ $payment->booking->passenger_phone }}</p>
                </div>

                <div class="info-box">
                    <span class="info-label">Jumlah Kursi</span>
                    <p class="info-value">{{ $payment->booking->seats }} Kursi</p>
                </div>
            </td>
            <td class="qr-section">
                <!-- SVG base64 render -->
                <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" style="width: 150px; height: 150px;">
                <p style="font-size: 10px; color: #777; margin-top: 5px;">Scan untuk verifikasi</p>
            </td>
        </tr>
    </table>

    <div class="route-box">
        <h2 class="route-title">{{ $payment->booking->travelSchedule->origin }} &rarr; {{ $payment->booking->travelSchedule->destination }}</h2>
        <p class="route-time">
            <strong>Keberangkatan:</strong> {{ \Carbon\Carbon::parse($payment->booking->travelSchedule->departure_time)->translatedFormat('l, d F Y') }} <br>
            <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($payment->booking->travelSchedule->departure_time)->format('H:i') }} WIB
        </p>
        <div class="vehicle-info">
            Kendaraan: <strong>{{ ucfirst($payment->booking->travelSchedule->vehicle_type) }}</strong> 
            @if($payment->booking->travelSchedule->description)
                ({{ $payment->booking->travelSchedule->description }})
            @endif
        </div>
    </div>

    <div class="footer">
        <p>Tiket ini adalah bukti pembayaran yang sah dan berlaku sebagai tiket elektronik.<br>
        Harap tunjukkan tiket ini beserta kartu identitas Anda saat keberangkatan.<br>
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</div>

</body>
</html>
