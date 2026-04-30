<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\TravelSchedule;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalSchedules    = TravelSchedule::count();
        $totalPassengers   = Booking::where('status', '!=', 'cancelled')->sum('seats');
        $totalTicketsSold  = Booking::where('status', 'confirmed')->sum('seats');
        $totalRevenue      = Payment::where('payments.status', 'paid')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('travel_schedules', 'bookings.travel_schedule_id', '=', 'travel_schedules.id')
            ->sum(DB::raw('bookings.seats * travel_schedules.price'));

        $pendingPayments   = Payment::where('status', 'pending')->count();
        $totalUsers        = User::role('user')->count();

        $recentSchedules   = TravelSchedule::withCount('bookings')->latest()->take(5)->get();

        $passengerReport   = Booking::selectRaw('travel_schedules.destination, SUM(bookings.seats) as total_passengers')
            ->join('travel_schedules', 'bookings.travel_schedule_id', '=', 'travel_schedules.id')
            ->where('bookings.status', '!=', 'cancelled')
            ->groupBy('travel_schedules.destination')
            ->orderByDesc('total_passengers')
            ->get();

        // Revenue per bulan (6 bulan terakhir)
        $revenueMonthly    = Payment::where('status', 'paid')
            ->selectRaw("TO_CHAR(paid_at, 'Mon YY') as month, SUM(amount) as total")
            ->where('paid_at', '>=', now()->subMonths(6))
            ->groupByRaw("TO_CHAR(paid_at, 'Mon YY'), DATE_TRUNC('month', paid_at)")
            ->orderByRaw("DATE_TRUNC('month', paid_at)")
            ->get();

        $latestPassengers  = Booking::with(['user', 'travelSchedule'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSchedules',
            'totalPassengers',
            'totalTicketsSold',
            'totalRevenue',
            'pendingPayments',
            'totalUsers',
            'recentSchedules',
            'passengerReport',
            'revenueMonthly',
            'latestPassengers'
        ));
    }

    public function jadwalTravel()
    {
        $travelSchedules = TravelSchedule::withCount('bookings')->latest()->get();
        return view('admin.travel.index', compact('travelSchedules'));
    }

    public function report()
    {
        $reports = TravelSchedule::with(['bookings'])->withCount('bookings')->latest()->get();
        return view('admin.report.report', compact('reports'));
    }

    public function travelReport($id)
    {
        $travel = TravelSchedule::with(['bookings.user', 'bookings.payment'])->findOrFail($id);
        return view('admin.report.travel_report', compact('travel'));
    }
}