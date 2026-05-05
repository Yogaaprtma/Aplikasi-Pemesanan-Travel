<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keberangkatan Travel</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 10px;
        }
        .header h2 {
            color: #0056b3;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            color: #555;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
        }
        table tr:nth-child(even) {
            background-color: #fcfcfc;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge-success {
            color: #155724;
            background-color: #d4edda;
            padding: 2px 5px;
            border-radius: 3px;
        }
        .badge-warning {
            color: #856404;
            background-color: #fff3cd;
            padding: 2px 5px;
            border-radius: 3px;
        }
        .badge-danger {
            color: #721c24;
            background-color: #f8d7da;
            padding: 2px 5px;
            border-radius: 3px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Keberangkatan Travel</h2>
        <p>Ringkasan Jadwal dan Penumpang | Dicetak pada: {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="20%">Rute Perjalanan</th>
                <th width="15%">Tanggal & Waktu</th>
                <th width="15%">Kendaraan</th>
                <th width="15%">Harga Tiket</th>
                <th width="15%" class="text-center">Kapasitas Terisi</th>
                <th width="15%" class="text-center">Status Kapasitas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $index => $report)
                @php
                    $bookedSeats = $report->bookings->where('status', '!=', 'cancelled')->sum('seats');
                    $totalCapacity = $report->quota + $bookedSeats;
                    $percentage = $totalCapacity > 0 ? round(($bookedSeats / $totalCapacity) * 100) : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $report->origin }}</strong> &rarr; <strong>{{ $report->destination }}</strong>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($report->departure_time)->format('d/m/Y') }}<br>
                        {{ \Carbon\Carbon::parse($report->departure_time)->format('H:i') }} WIB
                    </td>
                    <td>
                        {{ ucfirst($report->vehicle_type) }}
                    </td>
                    <td>
                        Rp {{ number_format($report->price, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        {{ $bookedSeats }} / {{ $totalCapacity }} Kursi
                    </td>
                    <td class="text-center">
                        @if($percentage >= 80)
                            <span class="badge-danger">Penuh ({{ $percentage }}%)</span>
                        @elseif($percentage >= 50)
                            <span class="badge-warning">Sedang ({{ $percentage }}%)</span>
                        @else
                            <span class="badge-success">Tersedia ({{ $percentage }}%)</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini di-generate secara otomatis oleh sistem Travel Booking.</p>
    </div>

</body>
</html>
