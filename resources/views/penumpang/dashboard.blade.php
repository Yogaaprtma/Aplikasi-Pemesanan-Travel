@extends('penumpang.layouts.main')

@section('title', 'Dashboard Penumpang')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/penumpang/dashboard.css') }}">
@endsection

@section('content')

    <div class="container">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="hero-section text-center">
            <h1 class="display-4 fw-bold mb-3">Perjalanan Terbaik Untuk Anda</h1>
            <p class="lead mb-4">Temukan jadwal travel terbaik untuk perjalanan anda</p>
        </div>

        <div class="filter-section mb-4">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchInput" class="form-control" placeholder="Cari tujuan atau tanggal keberangkatan...">
                <i class="fas fa-times clear-search" id="clearSearch"></i>
            </div>
            
            <div id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="destination" class="form-label">Tujuan</label>
                        <select class="form-select" id="destination" name="destination">
                            <option value="">Semua Tujuan</option>
                            @foreach($travelSchedules->pluck('destination')->unique() as $destination)
                                <option value="{{ $destination }}" {{ request('destination') == $destination ? 'selected' : '' }}>
                                    {{ $destination }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="departure_date" class="form-label">Tanggal Keberangkatan</label>
                        <input type="date" class="form-control" id="departure_date" name="departure_date" value="{{ request('departure_date') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" id="applyFilter" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </div>
                <button type="button" id="resetFilter" class="btn btn-outline-secondary mt-3 w-100">
                    <i class="fas fa-undo me-2"></i>Reset Filter
                </button>
            </div>
        </div>

        <ul class="nav nav-tabs" id="travel-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-travels" type="button" role="tab" aria-controls="all-travels" aria-selected="true">
                    Semua Jadwal
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="available-tab" data-bs-toggle="tab" data-bs-target="#available-travels" type="button" role="tab" aria-controls="available-travels" aria-selected="false">
                    Tersedia
                </button>
            </li>
        </ul>

        <div class="tab-content" id="travel-tabs-content">
            <div class="tab-pane fade show active" id="all-travels" role="tabpanel" aria-labelledby="all-tab">
                @if($travelSchedules->count() > 0)
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="allTravelsContainer">
                        @foreach($travelSchedules as $schedule)
                            <div class="col travel-card-wrapper" data-destination="{{ $schedule->destination }}" data-departure="{{ \Carbon\Carbon::parse($schedule->departure_time)->format('Y-m-d') }}">
                                <div class="travel-card card h-100">
                                    <div class="card-header bg-transparent border-0 p-0">
                                        <div class="position-relative">
                                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                                                style="height: 240px;">
                                                <i class="fas fa-bus fa-3x text-secondary"></i>
                                            </div>
                                            <div class="destination-badge">
                                                <i class="fas fa-map-marker-alt me-1"></i><span class="destination-text">{{ $schedule->destination }}</span>
                                            </div>
                                            
                                            @if($schedule->quota == 0)
                                                <div class="status-badge status-soldout">
                                                    <i class="fas fa-times-circle me-1"></i>Habis
                                                </div>
                                            @elseif($schedule->quota <= 3)
                                                <div class="status-badge status-limited">
                                                    <i class="fas fa-exclamation-circle me-1"></i>Hampir Habis
                                                </div>
                                            @else
                                                <div class="status-badge status-available">
                                                    <i class="fas fa-check-circle me-1"></i>Tersedia
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold destination-title">{{ $schedule->destination }}</h5>
                                        
                                        <div class="date-badge">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            <span class="departure-text">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('d M Y, H:i') }}</span>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <div>
                                                <p class="card-text mb-0">
                                                    <i class="fas fa-users me-1 text-secondary"></i>
                                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Jumlah kursi tersedia">
                                                        {{ $schedule->quota }} Kursi
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="price-tag">
                                                Rp{{ number_format($schedule->price, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        
                                        <div class="quota-indicator mt-2">
                                            @php
                                                $totalBookings = $schedule->bookings->sum('seats');
                                                $totalQuota = $schedule->quota + $totalBookings;
                                                $remainingPercentage = ($schedule->quota / $totalQuota) * 100;

                                                if($remainingPercentage > 50) {
                                                    $quotaColor = 'var(--secondary-color)';
                                                } elseif($remainingPercentage > 20) {
                                                    $quotaColor = 'var(--accent-color)';
                                                } else {
                                                    $quotaColor = 'var(--danger-color)';
                                                }
                                            @endphp
                                            <div class="quota-fill" style="width: {{ $remainingPercentage }}%; background-color: {{ $quotaColor }};"></div>
                                        </div>

                                        @if($schedule->quota > 0)
                                            <div class="booking-form">
                                                <form action="{{ route('booking.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="travel_schedule_id" value="{{ $schedule->id }}">
                                                    <div class="mb-3">
                                                        <label for="seats_{{ $schedule->id }}" class="form-label d-flex justify-content-between">
                                                            <span>Jumlah Kursi:</span>
                                                            <span class="text-muted">Max: {{ $schedule->quota }}</span>
                                                        </label>
                                                        <div class="input-group">
                                                            <button type="button" class="btn btn-outline-secondary decrease-seats">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="number" id="seats_{{ $schedule->id }}" name="seats" class="form-control text-center" value="1" min="1" max="{{ $schedule->quota }}" required>
                                                            <button type="button" class="btn btn-outline-secondary increase-seats">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary btn-book w-100">
                                                        <i class="fas fa-ticket-alt me-2"></i>Pesan Tiket
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="text-center mt-3">
                                                <button class="btn btn-danger btn-book w-100" disabled>
                                                    <i class="fas fa-times-circle me-2"></i>Tiket Habis
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div id="noResultsAll" class="empty-state" style="display: none;">
                        <i class="fas fa-search"></i>
                        <h3>Tidak Ada Hasil</h3>
                        <p class="text-muted">Tidak ada jadwal travel yang sesuai dengan pencarian Anda</p>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h3>Tidak Ada Jadwal Travel</h3>
                        <p class="text-muted">Tidak ada jadwal travel yang tersedia saat ini</p>
                    </div>
                @endif
            </div>
            
            <div class="tab-pane fade" id="available-travels" role="tabpanel" aria-labelledby="available-tab">
                @php
                    $availableSchedules = $travelSchedules->where('quota', '>', 0);
                @endphp
                
                @if($availableSchedules->count() > 0)
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="availableTravelsContainer">
                        @foreach($availableSchedules as $schedule)
                            <div class="col travel-card-wrapper" data-destination="{{ $schedule->destination }}" data-departure="{{ \Carbon\Carbon::parse($schedule->departure_time)->format('Y-m-d') }}">
                                <div class="travel-card card h-100">
                                    <div class="card-header bg-transparent border-0 p-0">
                                        <div class="position-relative">
                                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                                                style="height: 140px;">
                                                <i class="fas fa-bus fa-3x text-secondary"></i>
                                            </div>
                                            <div class="destination-badge">
                                                <i class="fas fa-map-marker-alt me-1"></i><span class="destination-text">{{ $schedule->destination }}</span>
                                            </div>
                                            
                                            @if($schedule->quota <= 3)
                                                <div class="status-badge status-limited">
                                                    <i class="fas fa-exclamation-circle me-1"></i>Hampir Habis
                                                </div>
                                            @else
                                                <div class="status-badge status-available">
                                                    <i class="fas fa-check-circle me-1"></i>Tersedia
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold destination-title">{{ $schedule->destination }}</h5>
                                        
                                        <div class="date-badge">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            <span class="departure-text">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('d M Y, H:i') }}</span>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <div>
                                                <p class="card-text mb-0">
                                                    <i class="fas fa-users me-1 text-secondary"></i>
                                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Jumlah kursi tersedia">
                                                        {{ $schedule->quota }} Kursi
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="price-tag">
                                                Rp{{ number_format($schedule->price, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        
                                        <div class="quota-indicator mt-2">
                                            @php
                                                $totalBookings = $schedule->bookings->sum('seats');
                                                $totalQuota = $schedule->quota + $totalBookings;
                                                $remainingPercentage = ($schedule->quota / $totalQuota) * 100;
                                                
                                                if($remainingPercentage > 50) {
                                                    $quotaColor = 'var(--secondary-color)';
                                                } elseif($remainingPercentage > 20) {
                                                    $quotaColor = 'var(--accent-color)';
                                                } else {
                                                    $quotaColor = 'var(--danger-color)';
                                                }
                                            @endphp
                                            <div class="quota-fill" style="width: {{ $remainingPercentage }}%; background-color: {{ $quotaColor }};"></div>
                                        </div>

                                        <div class="booking-form">
                                            <form action="{{ route('booking.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="travel_schedule_id" value="{{ $schedule->id }}">
                                                <div class="mb-3">
                                                    <label for="seats_avail_{{ $schedule->id }}" class="form-label d-flex justify-content-between">
                                                        <span>Jumlah Kursi:</span>
                                                        <span class="text-muted">Max: {{ $schedule->quota }}</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <button type="button" class="btn btn-outline-secondary decrease-seats">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <input type="number" id="seats_avail_{{ $schedule->id }}" name="seats" class="form-control text-center" value="1" min="1" max="{{ $schedule->quota }}" required>
                                                        <button type="button" class="btn btn-outline-secondary increase-seats">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-book w-100">
                                                    <i class="fas fa-ticket-alt me-2"></i>Pesan Tiket
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div id="noResultsAvailable" class="empty-state" style="display: none;">
                        <i class="fas fa-search"></i>
                        <h3>Tidak Ada Hasil</h3>
                        <p class="text-muted">Tidak ada jadwal travel yang sesuai dengan pencarian Anda</p>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h3>Tidak Ada Jadwal Tersedia</h3>
                        <p class="text-muted">Semua jadwal travel saat ini sudah habis terjual</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/penumpang/dashboard.js') }}"></script>
@endsection