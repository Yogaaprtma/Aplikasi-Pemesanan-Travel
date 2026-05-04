<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmedMail;

class PaymentConfirmController extends Controller
{
    /**
     * Daftar pembayaran yang menunggu konfirmasi
     */
    public function index()
    {
        $pendingPayments = Payment::where('status', 'pending')
            ->with(['booking.user', 'booking.travelSchedule'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $confirmedPayments = Payment::where('status', 'paid')
            ->with(['booking.user', 'booking.travelSchedule'])
            ->orderByDesc('paid_at')
            ->paginate(5);

        return view('admin.payments.index', compact('pendingPayments', 'confirmedPayments'));
    }

    /**
     * Konfirmasi pembayaran → status jadi paid, booking jadi confirmed
     */
    public function confirm($id)
    {
        $payment = Payment::with('booking')->findOrFail($id);

        if ($payment->status !== 'pending') {
            return redirect()->back()->with('error', 'Pembayaran ini sudah diproses sebelumnya.');
        }

        $payment->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        $payment->booking->update(['status' => 'confirmed']);

        try {
            if ($payment->booking->user && $payment->booking->user->email) {
                Mail::to($payment->booking->user->email)->send(new BookingConfirmedMail($payment->booking));
            }
        } catch (\Exception $e) {
            // Jika email gagal, abaikan saja agar tidak memblokir konfirmasi
            \Log::error('Gagal mengirim email konfirmasi: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Pembayaran #' . $payment->invoice_number . ' berhasil dikonfirmasi.');
    }

    /**
     * Tolak / batalkan pembayaran
     */
    public function reject($id)
    {
        $payment = Payment::with('booking')->findOrFail($id);

        if ($payment->status !== 'pending') {
            return redirect()->back()->with('error', 'Pembayaran ini sudah diproses sebelumnya.');
        }

        $payment->update(['status' => 'failed']);

        // Kembalikan kuota
        $booking = $payment->booking;
        $booking->travelSchedule->increment('quota', $booking->seats);
        $booking->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pembayaran ditolak. Kuota telah dikembalikan.');
    }
}
