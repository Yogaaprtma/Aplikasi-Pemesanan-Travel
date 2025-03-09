<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\TravelSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function booking()
    {
        $bookings = Booking::where('user_id', Auth::id())->get();
        return view('penumpang.booking.booking', compact('bookings'));
    }

    public function storeBoking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'travel_schedule_id' => 'required|exists:travel_schedules,id',
            'seats' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->route('booking.page')
                ->withErrors($validator)
                ->withInput();
        }

        $schedule = TravelSchedule::findOrFail($request->travel_schedule_id);

        if ($schedule->quota < $request->seats) {
            return redirect()->back()->with('error', 'Tiket tidak tersedia.');
        }

        Booking::create([
            'user_id' => Auth::id(),
            'travel_schedule_id' => $schedule->id,
            'seats' => $request->seats,
            'status' => 'pending',
        ]);

        $schedule->decrement('quota', $request->seats);

        return redirect()->route('booking.page')->with('success', 'Tiket berhasil dipesan.');
    }

    public function historyBooking()
    {
        $hasSchedules = TravelSchedule::exists(); 
        $hasBookings = Booking::where('user_id', Auth::id())->exists();

        if (!$hasSchedules || !$hasBookings) {
            return redirect()->route('home.penumpang')->with('error', 'Belum ada jadwal tiket atau riwayat pemesanan.');
        }

        $bookings = Booking::where('user_id', Auth::id())->with('travelSchedule')->get();
        return view('penumpang.booking.history', compact('bookings'));
    }
}