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

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                    <div class="col-md-3">
                        <label for="filterOrigin" class="form-label">Asal</label>
                        <select class="form-select" id="filterOrigin">
                            <option value="">Semua Asal</option>
                            @foreach($travelSchedules->pluck('origin')->filter()->unique()->sort() as $origin)
                                <option value="{{ $origin }}">{{ $origin }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="destination" class="form-label">Tujuan</label>
                        <select class="form-select" id="destination" name="destination">
                            <option value="">Semua Tujuan</option>
                            @foreach($travelSchedules->pluck('destination')->unique()->sort() as $destination)
                                <option value="{{ $destination }}">{{ $destination }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="departure_date" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="departure_date" name="departure_date" value="{{ request('departure_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Harga (Rp)</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" class="form-control" id="min_price" placeholder="Min" min="0">
                            <span>-</span>
                            <input type="number" class="form-control" id="max_price" placeholder="Max" min="0">
                            <button type="button" id="applyFilter" class="btn btn-primary px-4">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" id="resetFilter" class="btn btn-outline-secondary mt-3 w-100">
                    <i class="fas fa-undo me-2"></i>Reset Filter
                </button>
            </div>
        </div>

        <ul class="nav nav-tabs" id="travel-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-travels" type="button" role="tab">
                    Semua Jadwal
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="available-tab" data-bs-toggle="tab" data-bs-target="#available-travels" type="button" role="tab">
                    Tersedia
                </button>
            </li>
        </ul>

        <div class="tab-content" id="travel-tabs-content">
            {{-- Tab: Semua Jadwal --}}
            <div class="tab-pane fade show active" id="all-travels" role="tabpanel">
                @if($travelSchedules->count() > 0)
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="allTravelsContainer">
                        @foreach($travelSchedules as $schedule)
                            <div class="col travel-card-wrapper" 
                                data-origin="{{ $schedule->origin }}"
                                data-destination="{{ $schedule->destination }}" 
                                data-departure="{{ \Carbon\Carbon::parse($schedule->departure_time)->format('Y-m-d') }}"
                                data-price="{{ $schedule->price }}">
                                <div class="travel-card card h-100">
                                    <div class="card-header bg-transparent border-0 p-0">
                                        <div class="position-relative">
                                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                                <i class="fas {{ $schedule->vehicle_icon }} fa-3x text-secondary"></i>
                                            </div>
                                            <div class="destination-badge">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                <span class="destination-text">{{ $schedule->destination }}</span>
                                            </div>
                                            @if($schedule->quota == 0)
                                                <div class="status-badge status-soldout"><i class="fas fa-times-circle me-1"></i>Habis</div>
                                            @elseif($schedule->quota <= 3)
                                                <div class="status-badge status-limited"><i class="fas fa-exclamation-circle me-1"></i>Hampir Habis</div>
                                            @else
                                                <div class="status-badge status-available"><i class="fas fa-check-circle me-1"></i>Tersedia</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        {{-- Rute --}}
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="badge bg-light text-dark border">
                                                <i class="fas fa-location-arrow text-primary me-1"></i>{{ $schedule->origin ?: '—' }}
                                            </span>
                                            <i class="fas fa-arrow-right text-muted"></i>
                                            <span class="badge bg-primary">
                                                <i class="fas fa-map-marker-alt me-1"></i>{{ $schedule->destination }}
                                            </span>
                                        </div>

                                        <h5 class="card-title fw-bold destination-title">{{ $schedule->destination }}</h5>
                                        
                                        <div class="date-badge">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            <span class="departure-text">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('d M Y, H:i') }}</span>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <div>
                                                <p class="card-text mb-0">
                                                    <i class="fas fa-users me-1 text-secondary"></i>
                                                    {{ $schedule->quota }} Kursi
                                                </p>
                                                <small class="text-muted">
                                                    <i class="fas {{ $schedule->vehicle_icon }} me-1"></i>{{ $schedule->vehicle_label }}
                                                </small>
                                            </div>
                                            <div class="price-tag">Rp{{ number_format($schedule->price, 0, ',', '.') }}</div>
                                        </div>
                                        
                                        @php
                                            $totalBookings = $schedule->bookings->sum('seats');
                                            $totalQuota = $schedule->quota + $totalBookings;
                                            $remainingPercentage = $totalQuota > 0 ? ($schedule->quota / $totalQuota) * 100 : 0;
                                            $quotaColor = $remainingPercentage > 50 ? 'var(--secondary-color)' : ($remainingPercentage > 20 ? 'var(--accent-color)' : 'var(--danger-color)');
                                        @endphp
                                        <div class="quota-indicator mt-2">
                                            <div class="quota-fill" style="width: {{ $remainingPercentage }}%; background-color: {{ $quotaColor }};"></div>
                                        </div>

                                        @if($schedule->description)
                                            <p class="text-muted small mt-2 mb-0"><i class="fas fa-info-circle me-1"></i>{{ $schedule->description }}</p>
                                        @endif

                                        @if($schedule->quota > 0)
                                            <div class="booking-form mt-3">
                                                <button type="button" class="btn btn-primary btn-book w-100"
                                                    data-bs-toggle="modal" data-bs-target="#bookingModal"
                                                    data-schedule-id="{{ $schedule->id }}"
                                                    data-schedule-name="{{ $schedule->origin }} → {{ $schedule->destination }}"
                                                    data-schedule-date="{{ \Carbon\Carbon::parse($schedule->departure_time)->format('d M Y, H:i') }}"
                                                    data-schedule-price="{{ $schedule->price }}"
                                                    data-schedule-quota="{{ $schedule->quota }}">
                                                    <i class="fas fa-ticket-alt me-2"></i>Pesan Tiket
                                                </button>
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

            {{-- Tab: Tersedia --}}
            <div class="tab-pane fade" id="available-travels" role="tabpanel">
                @php $availableSchedules = $travelSchedules->where('quota', '>', 0); @endphp
                @if($availableSchedules->count() > 0)
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="availableTravelsContainer">
                        @foreach($availableSchedules as $schedule)
                            <div class="col travel-card-wrapper"
                                data-origin="{{ $schedule->origin }}"
                                data-destination="{{ $schedule->destination }}"
                                data-departure="{{ \Carbon\Carbon::parse($schedule->departure_time)->format('Y-m-d') }}"
                                data-price="{{ $schedule->price }}">
                                <div class="travel-card card h-100">
                                    <div class="card-header bg-transparent border-0 p-0">
                                        <div class="position-relative">
                                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 140px;">
                                                <i class="fas {{ $schedule->vehicle_icon }} fa-3x text-secondary"></i>
                                            </div>
                                            <div class="destination-badge"><i class="fas fa-map-marker-alt me-1"></i>{{ $schedule->destination }}</div>
                                            @if($schedule->quota <= 3)
                                                <div class="status-badge status-limited"><i class="fas fa-exclamation-circle me-1"></i>Hampir Habis</div>
                                            @else
                                                <div class="status-badge status-available"><i class="fas fa-check-circle me-1"></i>Tersedia</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="badge bg-light text-dark border"><i class="fas fa-location-arrow text-primary me-1"></i>{{ $schedule->origin ?: '—' }}</span>
                                            <i class="fas fa-arrow-right text-muted"></i>
                                            <span class="badge bg-primary"><i class="fas fa-map-marker-alt me-1"></i>{{ $schedule->destination }}</span>
                                        </div>
                                        <h5 class="card-title fw-bold destination-title">{{ $schedule->destination }}</h5>
                                        <div class="date-badge">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($schedule->departure_time)->format('d M Y, H:i') }}
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <p class="card-text mb-0"><i class="fas fa-users me-1 text-secondary"></i>{{ $schedule->quota }} Kursi</p>
                                            <div class="price-tag">Rp{{ number_format($schedule->price, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="booking-form mt-3">
                                            <button type="button" class="btn btn-primary btn-book w-100"
                                                data-bs-toggle="modal" data-bs-target="#bookingModal"
                                                data-schedule-id="{{ $schedule->id }}"
                                                data-schedule-name="{{ $schedule->origin }} → {{ $schedule->destination }}"
                                                data-schedule-date="{{ \Carbon\Carbon::parse($schedule->departure_time)->format('d M Y, H:i') }}"
                                                data-schedule-price="{{ $schedule->price }}"
                                                data-schedule-quota="{{ $schedule->quota }}">
                                                <i class="fas fa-ticket-alt me-2"></i>Pesan Tiket
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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

    {{-- Modal Booking --}}
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                    <h5 class="modal-title fw-bold" id="bookingModalLabel">
                        <i class="fas fa-ticket-alt me-2"></i>Pesan Tiket
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    {{-- Info Jadwal --}}
                    <div class="bg-light rounded-3 p-3 mb-4">
                        <p class="fw-bold mb-1" id="modalScheduleName">-</p>
                        <p class="text-muted mb-1 small"><i class="fas fa-calendar me-1"></i><span id="modalScheduleDate">-</span></p>
                        <p class="fw-bold text-primary mb-0" id="modalSchedulePrice">-</p>
                    </div>

                    <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="travel_schedule_id" id="modalScheduleId">

                        <div class="mb-3">
                            <label for="modalPassengerName" class="form-label fw-medium">
                                <i class="fas fa-user me-1 text-primary"></i>Nama Penumpang Utama
                            </label>
                            <input type="text" id="modalPassengerName" name="passenger_name" 
                                class="form-control" placeholder="Nama sesuai KTP" 
                                value="{{ old('passenger_name', Auth::user()->nama) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="modalPassengerPhone" class="form-label fw-medium">
                                <i class="fas fa-phone me-1 text-primary"></i>Nomor Telepon
                            </label>
                            <input type="text" id="modalPassengerPhone" name="passenger_phone" 
                                class="form-control" placeholder="08xxxxxxxxxx"
                                value="{{ old('passenger_phone', Auth::user()->no_telp) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">
                                <i class="fas fa-chair me-1 text-primary"></i>Jumlah Kursi
                                <span class="text-muted float-end small">Max: <span id="modalMaxSeats">-</span></span>
                            </label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" id="decreaseSeats"><i class="fas fa-minus"></i></button>
                                <input type="number" name="seats" id="modalSeatsInput" class="form-control text-center" value="1" min="1" required>
                                <button type="button" class="btn btn-outline-secondary" id="increaseSeats"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center bg-primary bg-opacity-10 rounded-3 p-3 mb-4">
                            <span class="fw-medium">Total Harga:</span>
                            <span class="fw-bold fs-5 text-primary" id="modalTotal">Rp 0</span>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check-circle me-2"></i>Konfirmasi Pemesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/penumpang/dashboard.js') }}"></script>
    <script>
        // Handle modal booking
        const bookingModal = document.getElementById('bookingModal');
        bookingModal.addEventListener('show.bs.modal', function(event) {
            const btn = event.relatedTarget;
            const scheduleId    = btn.getAttribute('data-schedule-id');
            const scheduleName  = btn.getAttribute('data-schedule-name');
            const scheduleDate  = btn.getAttribute('data-schedule-date');
            const schedulePrice = parseFloat(btn.getAttribute('data-schedule-price'));
            const scheduleQuota = parseInt(btn.getAttribute('data-schedule-quota'));

            document.getElementById('modalScheduleId').value   = scheduleId;
            document.getElementById('modalScheduleName').textContent = scheduleName;
            document.getElementById('modalScheduleDate').textContent = scheduleDate;
            document.getElementById('modalSchedulePrice').textContent = 'Rp ' + schedulePrice.toLocaleString('id-ID');
            document.getElementById('modalMaxSeats').textContent = scheduleQuota;
            document.getElementById('modalSeatsInput').max = scheduleQuota;
            document.getElementById('modalSeatsInput').value = 1;
            updateTotal(schedulePrice, 1);

            document.getElementById('increaseSeats').onclick = function() {
                const input = document.getElementById('modalSeatsInput');
                if (parseInt(input.value) < scheduleQuota) {
                    input.value = parseInt(input.value) + 1;
                    updateTotal(schedulePrice, input.value);
                }
            };
            document.getElementById('decreaseSeats').onclick = function() {
                const input = document.getElementById('modalSeatsInput');
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                    updateTotal(schedulePrice, input.value);
                }
            };
            document.getElementById('modalSeatsInput').oninput = function() {
                updateTotal(schedulePrice, this.value);
            };
        });

        function updateTotal(price, seats) {
            const total = price * parseInt(seats);
            document.getElementById('modalTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }
    </script>
@endsection