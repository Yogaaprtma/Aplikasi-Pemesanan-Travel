<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pemesanan Tiket Travel Terkonfirmasi</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; text-align: center; margin-bottom: 20px;">
        <h2 style="color: #0d6efd; margin: 0;">Tiket Terkonfirmasi!</h2>
    </div>

    <p>Halo, <strong>{{ $booking->user->nama ?? 'Penumpang' }}</strong>!</p>
    
    <p>Pembayaran tiket travel Anda telah kami terima dan konfirmasi. Berikut rincian perjalanan Anda:</p>

    <div style="background-color: #fff; border: 1px solid #dee2e6; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <p style="margin: 5px 0;"><strong>Kode Booking:</strong> {{ $booking->booking_code }}</p>
        <p style="margin: 5px 0;"><strong>Rute:</strong> {{ $booking->travelSchedule->origin }} &rarr; {{ $booking->travelSchedule->destination }}</p>
        <p style="margin: 5px 0;"><strong>Keberangkatan:</strong> {{ \Carbon\Carbon::parse($booking->travelSchedule->departure_time)->format('d M Y, H:i') }} WIB</p>
        <p style="margin: 5px 0;"><strong>Jumlah Kursi:</strong> {{ $booking->seats }}</p>
    </div>

    <p>Anda dapat mengunduh E-Ticket (berisi QR Code) melalui dashboard penumpang, atau dengan menekan tombol di bawah ini:</p>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('ticket.download', $booking->payment->id) }}" style="background-color: #0d6efd; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Download E-Ticket</a>
    </div>

    <p>Terima kasih telah mempercayakan perjalanan Anda kepada kami!</p>

    <div style="margin-top: 30px; font-size: 12px; color: #6c757d; text-align: center;">
        <p>&copy; {{ date('Y') }} Travel Booking System. All rights reserved.</p>
    </div>
</body>
</html>
