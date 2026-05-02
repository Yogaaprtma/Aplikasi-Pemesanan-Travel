<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\TravelSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Halaman daftar booking milik user
     */
    public function booking()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['travelSchedule', 'payment'])
            ->orderByDesc('created_at')
            ->get();

        return view('penumpang.booking.booking', compact('bookings'));
    }

    /**
     * Simpan booking baru
     */
    public function storeBoking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'travel_schedule_id' => 'required|exists:travel_schedules,id',
            'seats'              => 'required|integer|min:1|max:10',
            'passenger_name'     => 'required|string|max:255',
            'passenger_phone'    => 'required|string|max:20',
        ], [
            'travel_schedule_id.required' => 'Jadwal travel harus dipilih.',
            'seats.required'              => 'Jumlah kursi harus diisi.',
            'seats.min'                   => 'Minimal 1 kursi.',
            'seats.max'                   => 'Maksimal 10 kursi per pemesanan.',
            'passenger_name.required'     => 'Nama penumpang harus diisi.',
            'passenger_phone.required'    => 'Nomor telepon penumpang harus diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('home.penumpang')
                ->withErrors($validator)
                ->withInput();
        }

        $schedule = TravelSchedule::findOrFail($request->travel_schedule_id);

        // Cek kuota
        if ($schedule->quota < $request->seats) {
            return redirect()->back()->with('error', 'Kursi tidak tersedia. Sisa kursi: ' . $schedule->quota);
        }

        // Cek apakah jadwal sudah lewat
        if ($schedule->departure_time->isPast()) {
            return redirect()->back()->with('error', 'Jadwal ini sudah berlalu dan tidak bisa dipesan.');
        }

        Booking::create([
            'user_id'            => Auth::id(),
            'travel_schedule_id' => $schedule->id,
            'seats'              => $request->seats,
            'passenger_name'     => $request->passenger_name,
            'passenger_phone'    => $request->passenger_phone,
            'status'             => 'pending',
        ]);

        $schedule->decrement('quota', $request->seats);

        return redirect()->route('booking.page')->with('success', 'Tiket berhasil dipesan! Segera lakukan pembayaran.');
    }

    /**
     * Riwayat booking user
     */
    public function historyBooking()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['travelSchedule', 'payment'])
            ->orderByDesc('created_at')
            ->get();

        return view('penumpang.booking.history', compact('bookings'));
    }

    /**
     * Batalkan booking (hanya yang masih pending)
     */
    public function cancelBooking($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Booking ini tidak bisa dibatalkan.');
        }

        // Kembalikan kuota
        $booking->travelSchedule->increment('quota', $booking->seats);

        // Update status
        $booking->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return redirect()->route('booking.history')->with('success', 'Booking berhasil dibatalkan. Kuota telah dikembalikan.');
    }

    /**
     * Submit Ulasan (Review) Perjalanan
     */
    public function submitReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($booking->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Hanya tiket terkonfirmasi yang dapat diulas.');
        }

        if (!$booking->travelSchedule->departure_time->isPast()) {
            return redirect()->back()->with('error', 'Anda baru bisa memberikan ulasan setelah perjalanan selesai.');
        }

        $booking->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}