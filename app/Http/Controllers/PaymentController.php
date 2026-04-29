<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Simpan pembayaran + upload bukti
     */
    public function storePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id'     => 'required|exists:bookings,id',
            'payment_method' => 'required|string',
            'payment_proof'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'payment_method.required' => 'Metode pembayaran harus dipilih.',
            'payment_proof.image'     => 'Bukti pembayaran harus berupa gambar.',
            'payment_proof.max'       => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('booking.page')
                ->withErrors($validator)
                ->withInput();
        }

        $booking = Booking::where('id', $request->booking_id)
                          ->where('user_id', Auth::id())
                          ->with('travelSchedule')
                          ->firstOrFail();

        if ($booking->status === 'confirmed') {
            return redirect()->back()->with('error', 'Tiket sudah dibayar sebelumnya.');
        }

        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('error', 'Booking sudah dibatalkan, tidak bisa melakukan pembayaran.');
        }

        // Handle upload bukti
        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        // Hitung total
        $amount = $booking->seats * $booking->travelSchedule->price;

        Payment::create([
            'booking_id'     => $booking->id,
            'payment_method' => $request->payment_method,
            'amount'         => $amount,
            'payment_proof'  => $proofPath,
            'status'         => 'pending', // menunggu konfirmasi admin
        ]);

        // Booking tetap pending sampai admin konfirmasi
        return redirect()->route('booking.page')
            ->with('success', 'Bukti pembayaran berhasil dikirim! Menunggu konfirmasi dari admin.');
    }

    /**
     * Generate & tampilkan invoice
     */
    public function generateInvoice($id)
    {
        $payment = Payment::where('id', $id)
                        ->with(['booking.user', 'booking.travelSchedule'])
                        ->firstOrFail();

        // Hanya pemilik booking yang bisa lihat invoice
        if ($payment->booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }

        return view('penumpang.payment.invoice', compact('payment'));
    }
}