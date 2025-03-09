@extends('admin.layouts.main')

@section('title', 'Kelola Jadwal Travel')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/travel/index.css') }}">

    <div class="container-fluid px-4 py-5 fade-in">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.admin') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Jadwal Travel</li>
            </ol>
        </nav>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row mb-4 align-items-center">
            <div class="col">
                <h2 class="fw-bold text-gradient">
                    <i class="fas fa-bus me-2"></i>Jadwal Travel
                </h2>
                <p class="text-muted">Kelola semua jadwal perjalanan dalam satu tampilan</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('jadwal.create') }}" class="btn btn-primary btn-lg rounded-pill shadow pulse-btn">
                    <i class="fas fa-plus me-2"></i>Tambah Jadwal
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4 slide-in-right">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-search text-primary"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control border-0 bg-light" placeholder="Cari tujuan...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select border-0 bg-light" id="filterTujuan">
                            <option value="">Semua Tujuan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select border-0 bg-light" id="sortOption">
                            <option value="time_asc">Waktu (Terbaru)</option>
                            <option value="time_desc">Waktu (Terlama)</option>
                            <option value="price_asc">Harga (Terendah)</option>
                            <option value="price_desc">Harga (Tertinggi)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-primary w-100 rounded-pill" id="resetFilter">
                            <i class="fas fa-sync-alt me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-md-none mb-4">
            @foreach ($travelSchedules as $schedule)
            <div class="card border-0 shadow-hover mb-3 scale-in travel-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-primary m-0">{{ $schedule->destination }}</h5>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                            <i class="fas fa-users me-1"></i> {{ $schedule->quota }}
                        </span>
                    </div>
                    
                    <div class="d-flex mb-3 text-muted">
                        <div class="me-4">
                            <i class="far fa-calendar-alt me-1"></i>
                            {{ \Carbon\Carbon::parse($schedule->departure_time)->format('d M Y') }}
                        </div>
                        <div>
                            <i class="far fa-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }}
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Rp {{ number_format($schedule->price, 0, ',', '.') }}</h5>
                        <div>
                            <a href="{{ route('jadwal.edit', $schedule->id) }}" class="btn btn-warning btn-sm btn-floating me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('jadwal.destroy', $schedule->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-floating">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="card border-0 shadow-sm d-none d-md-block fade-in">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 ps-4">No</th>
                                <th class="py-3">Tujuan</th>
                                <th class="py-3">Tanggal</th>
                                <th class="py-3">Waktu</th>
                                <th class="py-3">Kuota</th>
                                <th class="py-3">Harga</th>
                                <th class="py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="scheduleTable">
                            @foreach ($travelSchedules as $schedule)
                            <tr class="row-item border-bottom">
                                <td class="ps-4">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="fw-medium">{{ $schedule->destination }}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($schedule->departure_time)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ min(($schedule->quota / 50) * 100, 100) }}%"></div>
                                        </div>
                                        <span class="badge bg-success-subtle text-success fw-normal px-2">{{ $schedule->quota }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold">Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('jadwal.edit', $schedule->id) }}" class="btn btn-outline-warning btn-sm btn-icon" data-bs-toggle="tooltip" title="Edit Jadwal">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('jadwal.destroy', $schedule->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm btn-icon" data-bs-toggle="tooltip" title="Hapus Jadwal">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div id="emptyState" class="card border-0 shadow-sm p-5 text-center d-none fade-in">
            <div class="py-5">
                <i class="fas fa-calendar-times text-muted" style="font-size: 5rem;"></i>
                <h4 class="mt-4">Tidak Ada Jadwal Ditemukan</h4>
                <p class="text-muted mb-4">Coba ubah filter atau tambahkan jadwal baru</p>
                <a href="{{ route('jadwal.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-plus me-2"></i>Tambah Jadwal Baru
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/admin/travel/index.js') }}"></script>
@endsection