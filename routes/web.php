<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PenumpangController;
use App\Http\Controllers\TravelScheduleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PaymentConfirmController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ─── Autentikasi ────────────────────────────────────────────────────────────
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login.page');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.page');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Admin ───────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('home');

    // Jadwal Travel (CRUD)
    Route::get('/jadwal-travel', [AdminController::class, 'jadwalTravel'])->name('jadwal.page');
    Route::get('/jadwal-travel/create', [TravelScheduleController::class, 'createJadwal'])->name('jadwal.create');
    Route::post('/jadwal-travel', [TravelScheduleController::class, 'storeJadwal'])->name('jadwal.store');
    Route::get('/jadwal-travel/{id}/edit', [TravelScheduleController::class, 'editJadwal'])->name('jadwal.edit');
    Route::put('/jadwal-travel/{id}', [TravelScheduleController::class, 'updateJadwal'])->name('jadwal.update');
    Route::delete('/jadwal-travel/{id}', [TravelScheduleController::class, 'destroyJadwal'])->name('jadwal.destroy');

    // Laporan
    Route::get('/report', [AdminController::class, 'report'])->name('report');
    Route::get('/reports/travel/{id}', [AdminController::class, 'travelReport'])->name('travel.report');
    Route::get('/report/export-pdf', [AdminController::class, 'exportPdf'])->name('report.export.pdf');

    // Manajemen Penumpang
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');

    // Konfirmasi Pembayaran
    Route::get('/payments', [PaymentConfirmController::class, 'index'])->name('payments.index');
    Route::put('/payments/{id}/confirm', [PaymentConfirmController::class, 'confirm'])->name('payments.confirm');
    Route::put('/payments/{id}/reject', [PaymentConfirmController::class, 'reject'])->name('payments.reject');
});

// ─── Alias lama untuk AuthController (redirect setelah login) ────────────────
// AuthController masih pakai route('home.admin'), jadi kita buat alias ini
// yang langsung render dashboard (bukan redirect) agar tidak loop
Route::middleware(['auth', 'role:admin'])
    ->get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->name('home.admin');

// ─── Penumpang ───────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:user'])->prefix('customer')->group(function () {

    // Dashboard
    Route::get('/home', [PenumpangController::class, 'index'])->name('home.penumpang');

    // Booking
    Route::get('/booking', [BookingController::class, 'booking'])->name('booking.page');
    Route::post('/booking', [BookingController::class, 'storeBoking'])->name('booking.store');
    Route::get('/history', [BookingController::class, 'historyBooking'])->name('booking.history');
    Route::delete('/booking/{id}/cancel', [BookingController::class, 'cancelBooking'])->name('booking.cancel');
    Route::post('/booking/{id}/review', [BookingController::class, 'submitReview'])->name('booking.review');

    // Payment
    Route::post('/payment', [PaymentController::class, 'storePayment'])->name('payment.store');

    // Profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
});

// Invoice & Ticket (auth saja, cek ownership di controller)
Route::middleware('auth')->get('/invoice/{id}', [PaymentController::class, 'generateInvoice'])->name('invoice.generate');
Route::middleware('auth')->get('/ticket/{id}', [PaymentController::class, 'downloadTicket'])->name('ticket.download');