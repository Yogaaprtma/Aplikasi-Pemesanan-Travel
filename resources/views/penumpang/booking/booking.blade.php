@extends('penumpang.layouts.main')

@section('title', 'Booking Tiket')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/penumpang/booking/booking.css') }}">

    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home.penumpang') }}" class="text-decoration-none">
                                <i class="bi bi-house-fill"></i> Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Booking Tiket</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <h2 class="text-center fw-bold mb-4">Booking Tiket</h2>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>

        @if(count($bookings) > 0)
            <div class="row justify-content-center">
                @foreach($bookings as $booking)
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                        <div class="card border-0 shadow-sm rounded-3 h-100 transition-hover">
                            <div class="card-header bg-primary bg-gradient text-white py-3 rounded-top">
                                <h5 class="card-title mb-0 fw-bold">
                                    <i class="bi bi-geo-alt-fill me-1"></i> {{ $booking->travelSchedule->destination }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar-event text-primary me-2 fs-5"></i>
                                            <div>
                                                <span class="text-muted small">Keberangkatan</span>
                                                <p class="mb-0 fw-medium">{{ \Carbon\Carbon::parse($booking->travelSchedule->departure_time)->format('d M Y, H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-seat text-primary me-2 fs-5"></i>
                                            <div>
                                                <span class="text-muted small">Jumlah Kursi</span>
                                                <p class="mb-0 fw-medium">{{ $booking->seats }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center mb-4">
                                    <i class="bi bi-tag-fill text-primary me-2 fs-5"></i>
                                    <div>
                                        <span class="text-muted small">Status</span>
                                        <div>
                                            @if($booking->status == 'pending')
                                                <span class="badge rounded-pill bg-warning px-3 py-2">
                                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Pembayaran
                                                </span>
                                            @elseif($booking->status == 'confirmed')
                                                <span class="badge rounded-pill bg-success px-3 py-2">
                                                    <i class="bi bi-check-circle me-1"></i> Tiket Terbayar
                                                </span>
                                            @elseif($booking->status == 'cancelled')
                                                <span class="badge rounded-pill bg-danger px-3 py-2">
                                                    <i class="bi bi-x-circle me-1"></i> Dibatalkan
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($booking->status == 'pending')
                                    <form action="{{ route('payment.store') }}" method="POST" class="mt-3">
                                        @csrf
                                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                        <div class="mb-3">
                                            <label for="payment_method_{{ $booking->id }}" class="form-label fw-medium">
                                                <i class="bi bi-credit-card me-1"></i> Metode Pembayaran
                                            </label>
                                            <select name="payment_method" id="payment_method_{{ $booking->id }}" class="form-select form-select-lg shadow-sm" required>
                                                <option value="" selected disabled>Pilih metode pembayaran</option>
                                                <option value="Transfer Bank">Transfer Bank</option>
                                                <option value="E-Wallet">E-Wallet</option>
                                                <option value="Kartu Kredit">Kartu Kredit</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">
                                            <i class="bi bi-check2-circle me-1"></i> Konfirmasi Pembayaran
                                        </button>
                                    </form>
                                @elseif($booking->status == 'confirmed')
                                    <a href="{{ route('booking.history') }}" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-ticket-perforated me-1"></i> Lihat Tiket
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-3 text-center py-5">
                        <div class="card-body">
                            <i class="bi bi-ticket-perforated text-muted" style="font-size: 5rem;"></i>
                            <h3 class="mt-4">Belum Ada Booking Tiket</h3>
                            <p class="text-muted">Anda belum memiliki tiket yang dibooking saat ini.</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('penumpang.home') }}" class="btn btn-outline-secondary mt-2">
                                    <i class="bi bi-house-fill me-1"></i> Kembali ke Home
                                </a>
                                <a href="{{ route('schedules.index') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-search me-1"></i> Cari Jadwal Perjalanan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection