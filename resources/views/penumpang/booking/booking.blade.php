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
                <h2 class="text-center fw-bold mb-4">Booking Tiket Saya</h2>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-geo-alt-fill me-1"></i>
                                        {{ $booking->travelSchedule->origin ?? '' }}
                                        @if($booking->travelSchedule->origin) → @endif
                                        {{ $booking->travelSchedule->destination }}
                                    </h5>
                                    <span class="badge bg-white bg-opacity-25 text-white rounded-pill small">
                                        {{ $booking->booking_code ?? '#' . $booking->id }}
                                    </span>
                                </div>
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
                                                <span class="text-muted small">Penumpang</span>
                                                <p class="mb-0 fw-medium">{{ $booking->passenger_name ?? Auth::user()->nama }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-people-fill text-primary me-2 fs-5"></i>
                                            <div>
                                                <span class="text-muted small">Jumlah Kursi</span>
                                                <p class="mb-0 fw-medium">{{ $booking->seats }} kursi</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-cash-coin text-primary me-2 fs-5"></i>
                                            <div>
                                                <span class="text-muted small">Total Bayar</span>
                                                <p class="mb-0 fw-medium text-success">
                                                    Rp {{ number_format($booking->seats * $booking->travelSchedule->price, 0, ',', '.') }}
                                                </p>
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
                                                    <i class="bi bi-check-circle me-1"></i> Tiket Terkonfirmasi
                                                </span>
                                            @elseif($booking->status == 'cancelled')
                                                <span class="badge rounded-pill bg-danger px-3 py-2">
                                                    <i class="bi bi-x-circle me-1"></i> Dibatalkan
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Aksi berdasarkan status --}}
                                @if($booking->status == 'pending')
                                    {{-- Form Upload Bukti Pembayaran --}}
                                    @if($booking->payment)
                                        <div class="alert alert-info rounded-3 small">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Bukti pembayaran sudah dikirim via <strong>{{ $booking->payment->payment_method }}</strong>. 
                                            Menunggu konfirmasi admin.
                                        </div>
                                    @else
                                        <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                            @csrf
                                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                            
                                            <div class="mb-3">
                                                <label for="payment_method_{{ $booking->id }}" class="form-label fw-medium">
                                                    <i class="bi bi-credit-card me-1"></i> Metode Pembayaran
                                                </label>
                                                <select name="payment_method" id="payment_method_{{ $booking->id }}" class="form-select shadow-sm" required>
                                                    <option value="" selected disabled>Pilih metode pembayaran</option>
                                                    <option value="Transfer Bank BCA">🏦 Transfer Bank BCA</option>
                                                    <option value="Transfer Bank BRI">🏦 Transfer Bank BRI</option>
                                                    <option value="Transfer Bank Mandiri">🏦 Transfer Bank Mandiri</option>
                                                    <option value="GoPay">📱 GoPay</option>
                                                    <option value="OVO">📱 OVO</option>
                                                    <option value="Dana">📱 Dana</option>
                                                    <option value="Tunai (Bayar di Tempat)">💵 Tunai (Bayar di Tempat)</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="payment_proof_{{ $booking->id }}" class="form-label fw-medium">
                                                    <i class="bi bi-image me-1"></i> Upload Bukti Bayar 
                                                    <span class="text-muted small">(Opsional, maks. 2MB)</span>
                                                </label>
                                                <input type="file" name="payment_proof" id="payment_proof_{{ $booking->id }}"
                                                    class="form-control" accept="image/jpg,image/jpeg,image/png">
                                            </div>

                                            <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">
                                                <i class="bi bi-send-check me-1"></i> Kirim Pembayaran
                                            </button>
                                        </form>
                                    @endif

                                @elseif($booking->status == 'confirmed')
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('invoice.generate', $booking->payment->id) }}" 
                                           class="btn btn-success btn-lg" target="_blank">
                                            <i class="bi bi-file-earmark-pdf me-1"></i> Download Invoice
                                        </a>
                                        <a href="{{ route('booking.history') }}" class="btn btn-outline-primary">
                                            <i class="bi bi-clock-history me-1"></i> Lihat Riwayat
                                        </a>
                                    </div>
                                @elseif($booking->status == 'cancelled')
                                    <div class="alert alert-danger rounded-3 mb-0 text-center">
                                        <i class="bi bi-x-circle me-2"></i>
                                        Booking dibatalkan pada {{ $booking->cancelled_at ? \Carbon\Carbon::parse($booking->cancelled_at)->format('d M Y, H:i') : '-' }}
                                    </div>
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
                                <a href="{{ route('home.penumpang') }}" class="btn btn-outline-secondary mt-2">
                                    <i class="bi bi-house-fill me-1"></i> Kembali ke Home
                                </a>
                                <a href="{{ route('home.penumpang') }}" class="btn btn-primary mt-2">
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