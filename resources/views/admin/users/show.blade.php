@extends('admin.layouts.main')

@section('title', 'Detail Penumpang - ' . $user->nama)

@section('content')
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ '/admin/dashboard' }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ '/admin/users' }}">Penumpang</a></li>
            <li class="breadcrumb-item active">{{ $user->nama }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Kartu Profil --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <div class="avatar-circle bg-primary text-white mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle fw-bold"
                    style="width:80px;height:80px;font-size:32px;">
                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                </div>
                <h4 class="fw-bold mb-1">{{ $user->nama }}</h4>
                <p class="text-muted mb-3">{{ $user->email }}</p>
                <hr>
                <div class="text-start">
                    <p class="mb-2"><i class="fas fa-phone text-primary me-2"></i>{{ $user->no_telp }}</p>
                    <p class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i>{{ $user->alamat }}</p>
                    <p class="mb-0"><i class="fas fa-calendar text-primary me-2"></i>Bergabung {{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>

            {{-- Statistik --}}
            <div class="card border-0 shadow-sm rounded-4 mt-3 p-4">
                <h6 class="fw-bold mb-3">Statistik</h6>
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="bg-light rounded-3 p-3">
                            <h4 class="fw-bold text-primary mb-0">{{ $user->bookings->count() }}</h4>
                            <small class="text-muted">Total Booking</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded-3 p-3">
                            <h4 class="fw-bold text-success mb-0">{{ $user->bookings->where('status', 'confirmed')->count() }}</h4>
                            <small class="text-muted">Dikonfirmasi</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded-3 p-3">
                            <h4 class="fw-bold text-warning mb-0">{{ $user->bookings->where('status', 'pending')->count() }}</h4>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded-3 p-3">
                            <h4 class="fw-bold text-danger mb-0">{{ $user->bookings->where('status', 'cancelled')->count() }}</h4>
                            <small class="text-muted">Dibatalkan</small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <p class="text-muted mb-1">Total Pengeluaran</p>
                    <h5 class="fw-bold text-success">Rp {{ number_format($totalSpent, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>

        {{-- Riwayat Booking --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0"><i class="fas fa-history text-primary me-2"></i>Riwayat Booking</h5>
                </div>
                <div class="card-body p-0">
                    @forelse($user->bookings->sortByDesc('created_at') as $booking)
                        <div class="border-bottom p-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="fw-bold">{{ $booking->booking_code ?? '#' . $booking->id }}</span>
                                        <span class="badge 
                                            @if($booking->status == 'confirmed') bg-success
                                            @elseif($booking->status == 'pending') bg-warning text-dark
                                            @else bg-danger @endif rounded-pill">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                    <p class="mb-1 fw-medium">
                                        <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                        {{ $booking->travelSchedule->origin ?? '-' }} → {{ $booking->travelSchedule->destination ?? '-' }}
                                    </p>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($booking->travelSchedule->departure_time)->format('d M Y, H:i') }}
                                        &nbsp;|&nbsp;
                                        <i class="fas fa-user me-1"></i>{{ $booking->seats }} kursi
                                        &nbsp;|&nbsp;
                                        <i class="fas fa-user-circle me-1"></i>{{ $booking->passenger_name ?? $user->nama }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <p class="fw-bold text-success mb-1">
                                        Rp {{ number_format($booking->seats * ($booking->travelSchedule->price ?? 0), 0, ',', '.') }}
                                    </p>
                                    @if($booking->payment)
                                        <small class="text-muted">{{ $booking->payment->payment_method }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-ticket-alt text-muted fa-3x mb-3 d-block"></i>
                            <p class="text-muted">Belum ada riwayat booking</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
