@extends('penumpang.layouts.main')

@section('title', 'Profil Saya')

@section('styles')
    <style>
        .profile-avatar {
            width: 90px; height: 90px; font-size: 36px;
            border: 4px solid #fff; box-shadow: 0 4px 15px rgba(0,0,0,.15);
        }
        .section-card { border-radius: 16px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
        .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15); }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        {{-- Header --}}
        <div class="card section-card mb-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="bg-primary p-4" style="min-height:100px;"></div>
                <div class="px-4 pb-4" style="margin-top:-50px;">
                    <div class="d-flex align-items-end gap-3">
                        <div class="profile-avatar bg-white text-primary d-flex align-items-center justify-content-center rounded-circle fw-bold flex-shrink-0">
                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $user->nama }}</h4>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            {{-- Edit Profil --}}
            <div class="col-lg-6">
                <div class="card section-card h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0"><i class="fas fa-user-edit text-primary me-2"></i>Edit Profil</h5>
                    </div>
                    <div class="card-body">
                        @if($errors->has('nama') || $errors->has('no_telp') || $errors->has('alamat'))
                            <div class="alert alert-danger rounded-3">
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->only(['nama', 'no_telp', 'alamat']) as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf @method('PUT')
                            <div class="mb-3">
                                <label class="form-label fw-medium">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                                    <input type="text" name="nama" class="form-control border-start-0" value="{{ old('nama', $user->nama) }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" class="form-control border-start-0 bg-light" value="{{ $user->email }}" disabled>
                                </div>
                                <small class="text-muted">Email tidak bisa diubah.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Nomor Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-phone text-muted"></i></span>
                                    <input type="text" name="no_telp" class="form-control border-start-0" value="{{ old('no_telp', $user->no_telp) }}" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-medium">Alamat</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                    <textarea name="alamat" class="form-control border-start-0" rows="2" required>{{ old('alamat', $user->alamat) }}</textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Ganti Password --}}
            <div class="col-lg-6">
                <div class="card section-card h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0"><i class="fas fa-lock text-primary me-2"></i>Ganti Password</h5>
                    </div>
                    <div class="card-body">
                        @if($errors->has('current_password') || $errors->has('password'))
                            <div class="alert alert-danger rounded-3">
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->only(['current_password', 'password']) as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf @method('PUT')
                            <div class="mb-3">
                                <label class="form-label fw-medium">Password Saat Ini</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="current_password" class="form-control border-start-0" required placeholder="••••••••">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-key text-muted"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0" required placeholder="Min. 8 karakter">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-medium">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-key text-muted"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control border-start-0" required placeholder="Ulangi password baru">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fas fa-shield-alt me-2"></i>Ubah Password
                            </button>
                        </form>

                        {{-- Info Akun --}}
                        <hr class="my-4">
                        <h6 class="fw-bold text-muted mb-3">Informasi Akun</h6>
                        <p class="mb-1 small"><i class="fas fa-calendar-plus text-primary me-2"></i>Terdaftar: {{ $user->created_at->format('d M Y') }}</p>
                        @php
                            $totalBookings    = $user->bookings()->count();
                            $confirmedBookings = $user->bookings()->where('status', 'confirmed')->count();
                        @endphp
                        <p class="mb-1 small"><i class="fas fa-ticket-alt text-primary me-2"></i>Total Booking: {{ $totalBookings }}</p>
                        <p class="mb-0 small"><i class="fas fa-check-circle text-success me-2"></i>Terkonfirmasi: {{ $confirmedBookings }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
