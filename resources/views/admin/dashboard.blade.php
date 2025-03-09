@extends('admin.layouts.main')

@section('title', 'Travel Admin Dashboard')

@section('content')

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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark animate__animated animate__fadeIn">Dashboard</h2>
        <div class="bg-white px-3 py-2 rounded-pill shadow-sm animate__animated animate__fadeIn">
            <i class="far fa-calendar-alt text-primary me-2"></i>
            <span class="fw-medium" id="currentDate"></span>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card h-100 animate__animated animate__fadeInUp">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-gradient-primary text-white">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Jadwal Aktif</p>
                        <h3 class="mb-0">{{ $totalSchedules }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-gradient-success text-white">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Total Penumpang</p>
                        <h3 class="mb-0">{{ $totalPassengers }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-gradient-warning text-white">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Tiket Terjual</p>
                        <h3 class="mb-0">{{ $totalTicketsSold }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-gradient-danger text-white">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Pendapatan</p>
                        <h3 class="mb-0">Rp {{ number_format($totalRevenue, 2, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4" data-aos="fade-up">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold">Jadwal Travel Terbaru</h5>
            <a href="{{ route('jadwal.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Jadwal
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Tujuan</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Kuota</th>
                            <th>Terisi</th>
                            <th>Harga</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentSchedules as $schedule)
                        <tr>
                            <td class="ps-3 text-secondary">#JDW{{ $schedule->id }}</td>
                            <td><span class="fw-medium">{{ $schedule->destination }}</span></td>
                            <td>{{ date('d M Y', strtotime($schedule->departure_time)) }}</td>
                            <td>{{ date('H:i', strtotime($schedule->departure_time)) }} WIB</td>
                            <td>{{ $schedule->quota }}</td>
                            <td>
                                <div class="progress" style="height: 6px; width: 60px;">
                                    @php
                                        $percentage = $schedule->quota > 0 ? ($schedule->bookings_count / $schedule->quota) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar 
                                        @if($percentage >= 80) bg-success 
                                        @elseif($percentage >= 50) bg-warning 
                                        @else bg-danger @endif" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted">{{ $schedule->bookings_count }}/{{ $schedule->quota }}</small>
                            </td>
                            <td>Rp {{ number_format($schedule->price, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @php
                                    $remainingQuota = $schedule->quota - $schedule->bookings_count;
                                @endphp
                                <span class="badge 
                                    {{ $schedule->quota == 0 ? 'bg-secondary' : ($remainingQuota == 0 ? 'bg-danger' : 'bg-success') }}">
                                    {{ $schedule->quota == 0 ? 'Complete' : ($remainingQuota == 0 ? 'Penuh' : 'Aktif') }}
                                </span>
                            </td>                            
                            <td class="text-end pe-3">
                                <a href="{{ route('jadwal.edit', $schedule->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('jadwal.destroy', $schedule->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Hapus Jadwal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white text-center">
            <a href="{{ route('jadwal.page') }}" class="text-decoration-none">View All Schedules <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Laporan Penumpang</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="passengerChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Penumpang Terbaru</h5>
                    <a href="#" class="text-primary small">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($latestPassengers as $passenger)
                            <li class="list-group-item px-0 py-3 d-flex align-items-center">
                                <img class="avatar me-3" src="{{ asset('images/avatar.jpg') }}" alt="User" onerror="this.src='https://via.placeholder.com/40'">
                                <div class="flex-grow-1">
                                    <p class="mb-0 fw-medium">{{ $passenger->user->nama ?? 'Guest' }}</p>
                                    <p class="mb-0 small text-muted">
                                        {{ $passenger->travelSchedule->destination ?? '-' }},
                                        {{ \Carbon\Carbon::parse($passenger->travelSchedule->departure_time)->format('d M Y') }}
                                    </p>
                                </div>
                                <span class="badge 
                                    @if($passenger->status == 'confirmed') bg-success text-light
                                    @elseif($passenger->status == 'pending') bg-warning text-dark
                                    @else bg-danger text-light
                                    @endif">
                                    {{ ucfirst($passenger->status) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passengerData = @json($passengerReport);
    
            const passengerLabels = passengerData.map(data => data.destination);
            const passengerCounts = passengerData.map(data => data.total_passengers);
    
            const passengerCtx = document.getElementById('passengerChart').getContext('2d');
            const passengerChart = new Chart(passengerCtx, {
                type: 'bar',
                data: {
                    labels: passengerLabels,
                    datasets: [{
                        label: 'Jumlah Penumpang',
                        data: passengerCounts,
                        backgroundColor: 'rgba(67, 97, 238, 0.5)',
                        borderColor: 'rgb(67, 97, 238)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: 'Yakin ingin menghapus jadwal ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>    
@endsection