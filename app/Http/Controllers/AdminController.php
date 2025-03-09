<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\TravelSchedule;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalSchedules = TravelSchedule::count();
        $totalPassengers = Booking::sum('seats');
        $totalTicketsSold = Booking::where('status', 'confirmed')->sum('seats');
        $totalRevenue = Payment::where('payments.status', 'paid')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('travel_schedules', 'bookings.travel_schedule_id', '=', 'travel_schedules.id')
            ->sum(DB::raw('bookings.seats * travel_schedules.price'));

        $recentSchedules = TravelSchedule::withCount('bookings')->latest()->take(5)->get();

        $passengerReport = Booking::selectRaw('travel_schedules.destination, SUM(bookings.seats) as total_passengers')
            ->join('travel_schedules', 'bookings.travel_schedule_id', '=', 'travel_schedules.id')
            ->groupBy('travel_schedules.destination')
            ->orderByDesc('total_passengers')
            ->get();

        $latestPassengers = Booking::with(['user', 'travelSchedule'])
            ->latest()
            ->take(4)
            ->get();

        return view('admin.dashboard', compact(
            'totalSchedules',
            'totalPassengers',
            'totalTicketsSold',
            'totalRevenue',
            'recentSchedules',
            'passengerReport',
            'latestPassengers'
        ));
    }

    public function jadwalTravel()
    {
        $travelSchedules = TravelSchedule::all();
        return view('admin.travel.index', compact('travelSchedules'));
    }

    public function report()
    {
        $reports = TravelSchedule::withCount('bookings')->get();
        return view('admin.report.report', compact('reports'));
    }

    public function travelReport($id)
    {
        $travel = TravelSchedule::with('bookings.user')->findOrFail($id);
        return view('admin.report.travel_report', compact('travel'));
    }
}