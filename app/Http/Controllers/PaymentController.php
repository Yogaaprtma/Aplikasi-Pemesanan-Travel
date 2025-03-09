<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function storePayment(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('booking.page')
                ->withErrors($validator)
                ->withInput();
        }

        $booking = Booking::where('id', $request->booking_id)
                          ->where('user_id', Auth::id())
                          ->firstOrFail();

        if ($booking->status == 'confirmed') {
            return redirect()->back()->with('error', 'Tiket sudah dibayar.');
        }

        Payment::create([
            'booking_id' => $booking->id,
            'payment_method' => $request->payment_method,
            'status' => 'paid',
        ]);

        $booking->update(['status' => 'confirmed']);

        return redirect()->route('booking.page')->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function generateInvoice($id)
    {
        $payment = Payment::where('id', $id)
                        ->with(['booking.user', 'booking.travelSchedule'])
                        ->firstOrFail();

        return view('penumpang.payment.invoice', compact('payment'));
    }
}