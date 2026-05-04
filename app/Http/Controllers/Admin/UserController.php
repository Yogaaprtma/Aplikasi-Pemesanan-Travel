<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Daftar semua penumpang
     */
    public function index()
    {
        $users = User::role('user')
            ->withCount('bookings')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Detail penumpang + history booking
     */
    public function show($id)
    {
        $user = User::with([
            'bookings.travelSchedule',
            'bookings.payment',
        ])->findOrFail($id);

        // Pastikan hanya user role yang bisa dilihat
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Data admin tidak dapat dilihat di sini.');
        }

        $totalSpent = $user->bookings()
            ->where('status', 'confirmed')
            ->with('travelSchedule')
            ->get()
            ->sum(fn($b) => $b->seats * ($b->travelSchedule->price ?? 0));

        return view('admin.users.show', compact('user', 'totalSpent'));
    }
}
