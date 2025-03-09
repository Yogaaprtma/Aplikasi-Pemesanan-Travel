<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TravelScheduleController;
use App\Http\Controllers\PenumpangController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentikasi
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login.page');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.page');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('home.admin');
    Route::get('/admin/jadwal-travel', [AdminController::class, 'jadwalTravel'])->name('jadwal.page');

    // CRUD Jadwal Travel
    Route::get('/admin/jadwal-travel/create', [TravelScheduleController::class, 'createJadwal'])->name('jadwal.create');
    Route::post('/admin/jadwal-travel', [TravelScheduleController::class, 'storeJadwal'])->name('jadwal.store');
    Route::get('/admin/jadwal-travel/{id}/edit', [TravelScheduleController::class, 'editJadwal'])->name('jadwal.edit');
    Route::put('/admin/jadwal-travel/{id}', [TravelScheduleController::class, 'updateJadwal'])->name('jadwal.update');
    Route::delete('/admin/jadwal-travel/{id}', [TravelScheduleController::class, 'destroyJadwal'])->name('jadwal.destroy');

    Route::get('/admin/report', [AdminController::class, 'report'])->name('admin.report');
    Route::get('/admin/reports/travel/{id}', [AdminController::class, 'travelReport'])->name('admin.travel.report');
});

// Penumpang
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/customer/home', [PenumpangController::class, 'index'])->name('home.penumpang');

    Route::get('/customer/booking', [BookingController::class, 'booking'])->name('booking.page');
    Route::post('/customer/booking', [BookingController::class, 'storeBoking'])->name('booking.store');
    Route::get('/customer/history', [BookingController::class, 'historyBooking'])->name('booking.history');

    Route::post('/customer/payment', [PaymentController::class, 'storePayment'])->name('payment.store');
    Route::get('/invoice/{id}', [PaymentController::class, 'generateInvoice'])->name('invoice.generate');
});