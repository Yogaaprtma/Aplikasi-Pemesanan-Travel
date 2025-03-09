@extends('admin.layouts.main')

@section('title', 'Detail Laporan Travel')

@section('content')
    <div class="container-fluid py-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-4">
                <li class="breadcrumb-item"><a href="{{ route('home.admin') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.report') }}">Laporan Travel</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Laporan</li>
            </ol>
        </nav>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="card-title mb-0 text-truncate">{{ $travel->destination }}</h5>
                <a href="{{ route('admin.report') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> <span class="d-none d-sm-inline">Kembali</span>
                </a>
            </div>
            
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="d-flex align-items-start align-items-sm-center flex-column flex-sm-row">
                            <div class="travel-icon bg-primary bg-opacity-10 rounded-circle p-3 me-0 me-sm-3 mb-3 mb-sm-0">
                                <i class="fas fa-bus text-primary fa-2x"></i>
                            </div>
                            <div class="text-center text-sm-start">
                                <h4 class="mb-2">{{ $travel->destination }}</h4>
                                <div class="d-flex flex-wrap justify-content-center justify-content-sm-start">
                                    <span class="badge bg-light text-dark me-2 mb-2">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($travel->departure_time)->format('d M Y') }}
                                    </span>
                                    <span class="badge bg-light text-dark me-2 mb-2">
                                        <i class="far fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($travel->departure_time)->format('H:i') }}
                                    </span>
                                    <span class="badge bg-primary mb-2">
                                        <i class="fas fa-users me-1"></i>
                                        {{ count($travel->bookings) }} Penumpang
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="small text-muted">Status</div>
                                        <div class="fw-bold">
                                            @php
                                                $now = \Carbon\Carbon::now();
                                                $departureTime = \Carbon\Carbon::parse($travel->departure_time);
                                                if ($departureTime->isPast()) {
                                                    $status = 'Selesai';
                                                    $statusClass = 'text-success';
                                                } elseif ($departureTime->diffInHours($now) <= 24) {
                                                    $status = 'Segera Berangkat';
                                                    $statusClass = 'text-warning';
                                                } else {
                                                    $status = 'Terjadwal';
                                                    $statusClass = 'text-primary';
                                                }
                                            @endphp
                                            <span class="{{ $statusClass }}">{{ $status }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="small text-muted">Kapasitas Terisi</div>
                                        <div class="fw-bold">
                                            @php
                                                $capacity = 100;
                                                $totalPassengers = count($travel->bookings);
                                                $percentage = round(($totalPassengers / $capacity) * 100);
                                            @endphp
                                            {{ $percentage }}%
                                            <div class="progress mt-1" style="height: 6px;">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <ul class="nav nav-tabs d-md-none mb-3" id="viewToggle" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="cards-tab" data-bs-toggle="tab" data-bs-target="#cards-view" type="button" role="tab">
                            <i class="fas fa-th-large me-1"></i> Kartu
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="table-tab" data-bs-toggle="tab" data-bs-target="#table-view" type="button" role="tab">
                            <i class="fas fa-table me-1"></i> Tabel
                        </button>
                    </li>
                </ul>

                <div class="tab-content d-md-none">
                    <div class="tab-pane fade" id="table-view" role="tabpanel" aria-labelledby="table-tab">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">No. Telepon</th>
                                        <th scope="col">Kursi</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($travel->bookings as $key => $booking)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-placeholder bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                    <span class="text-secondary">{{ substr($booking->user->nama, 0, 1) }}</span>
                                                </div>
                                                <div>{{ $booking->user->nama }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="d-inline-block text-truncate" style="max-width: 120px;">
                                                {{ $booking->user->email }}
                                            </span>
                                        </td>
                                        <td>{{ $booking->user->no_telp }}</td>
                                        <td class="text-center">{{ $booking->seats }}</td>
                                        <td>
                                            @php
                                                $statusClass = '';
                                                $statusIcon = '';
                                                
                                                if($booking->status == 'confirmed') {
                                                    $statusClass = 'success';
                                                    $statusIcon = 'check-circle';
                                                } elseif($booking->status == 'pending') {
                                                    $statusClass = 'warning';
                                                    $statusIcon = 'clock';
                                                } else {
                                                    $statusClass = 'danger';
                                                    $statusIcon = 'times-circle';
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }} d-flex align-items-center">
                                                <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                                                <p class="text-muted mb-0">Tidak ada penumpang dalam jadwal ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade show active" id="cards-view" role="tabpanel" aria-labelledby="cards-tab">
                        <div class="passenger-cards">
                            @forelse($travel->bookings as $key => $booking)
                            <div class="card mb-3 border">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-placeholder bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                <span class="text-secondary fw-bold">{{ substr($booking->user->nama, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $booking->user->nama }}</h6>
                                                <small class="text-muted">Kursi: <span class="fw-bold">{{ $booking->seats }}</span></small>
                                            </div>
                                        </div>
                                        @php
                                            $statusClass = '';
                                            $statusIcon = '';
                                            
                                            if($booking->status == 'confirmed') {
                                                $statusClass = 'success';
                                                $statusIcon = 'check-circle';
                                            } elseif($booking->status == 'pending') {
                                                $statusClass = 'warning';
                                                $statusIcon = 'clock';
                                            } else {
                                                $statusClass = 'danger';
                                                $statusIcon = 'times-circle';
                                            }
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }} py-2 px-3">
                                            <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="row g-2">
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-envelope text-muted me-2"></i>
                                                <div class="text-truncate">
                                                    <small class="d-block text-muted">Email:</small>
                                                    <span class="d-block text-truncate">{{ $booking->user->email }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-phone text-muted me-2"></i>
                                                <div>
                                                    <small class="d-block text-muted">No. Telepon:</small>
                                                    <span>{{ $booking->user->no_telp }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                                    <p class="text-muted mb-0">Tidak ada penumpang dalam jadwal ini.</p>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" width="5%">#</th>
                                    <th scope="col" width="25%">Nama Penumpang</th>
                                    <th scope="col" width="20%">Email</th>
                                    <th scope="col" width="15%">No. Telepon</th>
                                    <th scope="col" width="10%">Kursi</th>
                                    <th scope="col" width="15%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($travel->bookings as $key => $booking)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-placeholder bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                <span class="text-secondary">{{ substr($booking->user->nama, 0, 1) }}</span>
                                            </div>
                                            <div>{{ $booking->user->nama }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                            {{ $booking->user->email }}
                                        </span>
                                    </td>
                                    <td>{{ $booking->user->no_telp }}</td>
                                    <td class="text-center">{{ $booking->seats }}</td>
                                    <td>
                                        @php
                                            $statusClass = '';
                                            $statusIcon = '';
                                            
                                            if($booking->status == 'confirmed') {
                                                $statusClass = 'success';
                                                $statusIcon = 'check-circle';
                                            } elseif($booking->status == 'pending') {
                                                $statusClass = 'warning';
                                                $statusIcon = 'clock';
                                            } else {
                                                $statusClass = 'danger';
                                                $statusIcon = 'times-circle';
                                            }
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }} d-flex align-items-center justify-content-center p-2">
                                            <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                                            <p class="text-muted mb-0">Tidak ada penumpang dalam jadwal ini.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center py-3">
                <div class="text-muted small mb-2 mb-md-0">
                    Total <span id="passengerCount" class="badge bg-light text-dark">{{ count($travel->bookings) }}</span> penumpang
                </div>
                <div class="d-flex">
                    <button id="printReport" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fas fa-print me-1"></i> <span class="d-none d-sm-inline">Cetak</span>
                    </button>
                    <div class="text-muted small d-flex align-items-center">
                        <i class="far fa-clock me-1"></i>
                        {{ now()->format('d M Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection