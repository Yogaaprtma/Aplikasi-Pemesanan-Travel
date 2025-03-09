@extends('penumpang.layouts.main')

@section('title', 'Riwayat Pemesanan')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold text-primary mb-0">Riwayat Pemesanan Tiket</h2>
                        <p class="text-muted mb-0">Daftar semua tiket yang telah Anda pesan</p>
                    </div>
                </div>

                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home.penumpang') }}" class="text-decoration-none">
                                <i class="bi bi-house-fill"></i> Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Riwayat Pemesanan</li>
                    </ol>
                </nav>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white p-4 border-bottom border-light">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h5 class="mb-0">
                                    <i class="bi bi-clock-history text-primary me-2"></i>Semua Transaksi
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" id="searchBooking" class="form-control bg-light border-0" 
                                        placeholder="Cari riwayat pemesanan...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @if($bookings->isEmpty())
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h4 class="text-muted">Belum Ada Pemesanan</h4>
                                <p class="text-muted">Anda belum memiliki riwayat pemesanan tiket</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('penumpang.home') }}" class="btn btn-outline-secondary mt-2">
                                        <i class="bi bi-house-fill me-1"></i>Kembali ke Home
                                    </a>
                                    <a href="{{ route('booking.page') }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-circle me-2"></i>Pesan Tiket Sekarang
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive d-none d-lg-block">
                                <table class="table table-hover booking-table mb-0" id="bookingTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="px-4 py-3">No</th>
                                            <th class="px-4 py-3">Tujuan</th>
                                            <th class="px-4 py-3">Keberangkatan</th>
                                            <th class="px-4 py-3">Jumlah Kursi</th>
                                            <th class="px-4 py-3">Total Harga</th>
                                            <th class="px-4 py-3">Status</th>
                                            <th class="px-4 py-3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings as $index => $booking)
                                            <tr class="align-middle booking-row">
                                                <td class="px-4 py-3 text-muted">{{ $index + 1 }}</td>
                                                <td class="px-4 py-3 fw-medium">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                                                        {{ $booking->travelSchedule->destination }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-medium">{{ date('d M Y', strtotime($booking->travelSchedule->departure_time)) }}</span>
                                                        <small class="text-muted">{{ date('H:i', strtotime($booking->travelSchedule->departure_time)) }} WIB</small>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                                        <i class="bi bi-person-fill me-1"></i>{{ $booking->seats }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 fw-medium">
                                                    Rp {{ number_format($booking->travelSchedule->price * $booking->seats, 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    @if($booking->status == 'pending')
                                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                            <i class="bi bi-hourglass-split me-1"></i>Menunggu Pembayaran
                                                        </span>
                                                    @elseif($booking->status == 'confirmed')
                                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                                            <i class="bi bi-check-circle me-1"></i>Sudah Dibayar
                                                        </span>
                                                    @elseif($booking->status == 'cancelled')
                                                        <span class="badge bg-danger rounded-pill px-3 py-2">
                                                            <i class="bi bi-x-circle me-1"></i>Dibatalkan
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    @if($booking->status == 'pending')
                                                        <div class="d-flex gap-2">
                                                            <a href="{{ route('booking.page', $booking->id) }}" 
                                                            class="btn btn-success btn-sm rounded-3" target="_blank">
                                                                <i class="bi bi-credit-card me-1"></i>Bayar
                                                            </a>
                                                        </div>
                                                    @elseif($booking->status == 'confirmed')
                                                        <div class="d-flex gap-2">
                                                            <a href="{{ route('invoice.generate', $booking->payment->id) }}" 
                                                            class="btn btn-success btn-sm rounded-3" target="_blank">
                                                                <i class="bi bi-file-earmark-text me-1"></i>Invoice
                                                            </a>
                                                        </div>
                                                    @else
                                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-3" disabled>
                                                            <i class="bi bi-x-circle me-1"></i>Dibatalkan
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-lg-none">
                                <div class="booking-cards">
                                    @foreach($bookings as $index => $booking)
                                        <div class="card border-0 border-bottom rounded-0 booking-mobile-card" data-search-content="{{ $booking->travelSchedule->destination }}">
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                            <i class="bi bi-geo-alt-fill"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-0 fw-bold">{{ $booking->travelSchedule->destination }}</h5>
                                                            <div class="d-flex align-items-center mt-1">
                                                                <i class="bi bi-calendar-event text-primary me-1"></i>
                                                                <span class="small">{{ date('d M Y', strtotime($booking->travelSchedule->departure_time)) }} · {{ date('H:i', strtotime($booking->travelSchedule->departure_time)) }} WIB</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="ms-3">
                                                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                                            <i class="bi bi-person-fill me-1"></i>{{ $booking->seats }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="row mb-3">
                                                    <div class="col-6">
                                                        <span class="d-block text-muted small">Total Harga</span>
                                                        <span class="fw-bold">Rp {{ number_format($booking->travelSchedule->price * $booking->seats, 0, ',', '.') }}</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <span class="d-block text-muted small">Status</span>
                                                        @if($booking->status == 'pending')
                                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 w-100">
                                                                <i class="bi bi-hourglass-split me-1"></i>Menunggu Pembayaran
                                                            </span>
                                                        @elseif($booking->status == 'confirmed')
                                                            <span class="badge bg-success rounded-pill px-3 py-2 w-100">
                                                                <i class="bi bi-check-circle me-1"></i>Sudah Dibayar
                                                            </span>
                                                        @elseif($booking->status == 'cancelled')
                                                            <span class="badge bg-danger rounded-pill px-3 py-2 w-100">
                                                                <i class="bi bi-x-circle me-1"></i>Dibatalkan
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="d-grid">
                                                    @if($booking->status == 'pending')
                                                        <a href="{{ route('booking.page', $booking->id) }}" 
                                                            class="btn btn-success rounded-3" target="_blank">
                                                            <i class="bi bi-credit-card me-1"></i>Bayar
                                                        </a>
                                                    @elseif($booking->status == 'confirmed')
                                                        <a href="{{ route('invoice.generate', $booking->payment->id) }}" 
                                                        class="btn btn-success rounded-3" target="_blank">
                                                            <i class="bi bi-file-earmark-text me-1"></i>Lihat Invoice
                                                        </a>
                                                    @else
                                                        <button type="button" class="btn btn-outline-secondary rounded-3" disabled>
                                                            <i class="bi bi-x-circle me-1"></i>Dibatalkan
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/penumpang/booking/history.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/penumpang/booking/history.js') }}"></script>
@endsection