@extends('admin.layouts.main')

@section('title', 'Edit Jadwal')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/travel/edit.css') }}">

    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7 col-xxl-6">
                <nav aria-label="breadcrumb" class="mb-4 slide-in-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home.admin') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('jadwal.page') }}" class="text-decoration-none">Jadwal Travel</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Jadwal</li>
                    </ol>
                </nav>

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden scale-in-center">
                    <div class="card-header bg-gradient-primary text-white p-4 border-0">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3 bg-white bg-opacity-25 rounded-circle">
                                <i class="fas fa-edit fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-bold">Edit Jadwal Travel</h4>
                                <p class="mb-0 opacity-75">Silahkan perbarui informasi jadwal di bawah ini</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-lg-5">
                        @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible border-0 shadow-sm fade show slide-in-top" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="alert-icon me-3">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <h5 class="alert-heading mb-1">Oops! Ada beberapa kesalahan:</h5>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <form action="{{ route('jadwal.update', $schedule->id) }}" method="POST" class="needs-validation" id="scheduleForm" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="row g-4">
                                <div class="col-md-12 fade-in" style="--delay: 0.1s">
                                    <div class="form-floating form-group">
                                        <input type="text" id="destination" name="destination" class="form-control form-control-lg @error('destination') is-invalid @enderror" value="{{ old('destination', $schedule->destination) }}" placeholder="Masukkan tujuan" required>
                                        <label for="destination">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>Tujuan
                                        </label>
                                        <div class="invalid-feedback">
                                            Tujuan tidak boleh kosong
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 fade-in" style="--delay: 0.2s">
                                    <label class="form-label mb-2">
                                        <i class="far fa-calendar-alt text-primary me-2"></i>Tanggal Keberangkatan
                                    </label>
                                    <div class="input-group input-group-lg has-validation">
                                        <input type="date" id="departure_date" name="departure_date" class="form-control @error('departure_date') is-invalid @enderror" value="{{ old('departure_date', date('Y-m-d', strtotime($schedule->departure_time))) }}" required>
                                        <div class="invalid-feedback">
                                            Tanggal keberangkatan diperlukan
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 fade-in" style="--delay: 0.3s">
                                    <label class="form-label mb-2">
                                        <i class="far fa-clock text-primary me-2"></i>Waktu Keberangkatan
                                    </label>
                                    <div class="input-group input-group-lg has-validation">
                                        <input type="time" id="departure_time" name="departure_time" class="form-control @error('departure_time') is-invalid @enderror" value="{{ old('departure_time', date('H:i', strtotime($schedule->departure_time))) }}" required>
                                        <div class="invalid-feedback">
                                            Waktu keberangkatan diperlukan
                                        </div>
                                    </div>
                                    <input type="hidden" id="combined_datetime" name="departure_time">
                                </div>

                                <div class="col-md-6 fade-in" style="--delay: 0.4s">
                                    <div class="form-floating">
                                        <input type="number" id="quota" name="quota" class="form-control form-control-lg @error('quota') is-invalid @enderror" value="{{ old('quota', $schedule->quota) }}" min="1" placeholder="Masukkan kuota" required>
                                        <label for="quota">
                                            <i class="fas fa-users text-primary me-2"></i>Kuota
                                        </label>
                                        <div class="invalid-feedback">
                                            Kuota minimal 1 orang
                                        </div>
                                    </div>
                                    <div class="form-text mt-2">
                                        <div class="d-flex align-items-center">
                                            <input type="range" class="form-range me-2" id="quotaRange" min="1" max="100"
                                                value="{{ old('quota', $schedule->quota) }}">
                                            <span id="quotaValue">{{ old('quota', $schedule->quota) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 fade-in" style="--delay: 0.5s">
                                    <div class="form-floating">
                                        <input type="number" id="price" name="price" class="form-control form-control-lg @error('price') is-invalid @enderror" value="{{ old('price', $schedule->price) }}" min="0" placeholder="Masukkan harga" required>
                                        <label for="price">
                                            <i class="fas fa-tag text-primary me-2"></i>Harga (Rp)
                                        </label>
                                        <div class="invalid-feedback">
                                            Harga tidak boleh negatif
                                        </div>
                                    </div>
                                    <div id="priceFormatted" class="form-text fw-medium mt-2 text-success">
                                        Rp {{ number_format($schedule->price, 0, ',', '.') }}
                                    </div>
                                </div>

                                <div class="col-12 mt-4 slide-in-bottom">
                                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-md-end">
                                        <a href="{{ route('jadwal.page') }}" class="btn btn-light btn-lg px-4 d-flex align-items-center justify-content-center shadow-sm">
                                            <i class="fas fa-arrow-left me-2"></i>
                                            <span>Kembali</span>
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-lg px-5 d-flex align-items-center justify-content-center shadow-sm">
                                            <i class="fas fa-save me-2"></i>
                                            <span>Perbarui Jadwal</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin/travel/edit.js') }}"></script>
@endsection