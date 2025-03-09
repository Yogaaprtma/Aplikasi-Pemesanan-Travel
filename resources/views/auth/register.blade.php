<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card register-card">
                    <div class="card-header">
                        <h2 class="mb-0">Buat Akun Baru</h2>
                        <p class="text-white-50">Silakan isi form berikut untuk mendaftar</p>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        
                        @if (session('error'))
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="fas fa-circle-exclamation me-2"></i>
                                <div>{{ session('error') }}</div>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="fas fa-circle-check me-2"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                        @endif

                        <form action="{{ route('register.post') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <input type="text" name="nama" id="nama" class="form-control" placeholder=" " required>
                                        <label for="nama">Nama Lengkap</label>
                                    </div>
                                </div>
                                @error('nama')
                                    <div class="text-danger mt-2 small"><i class="fas fa-triangle-exclamation me-1"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <input type="email" name="email" id="email" class="form-control" placeholder=" " required>
                                        <label for="email">Email Address</label>
                                    </div>
                                </div>
                                @error('email')
                                    <div class="text-danger mt-2 small"><i class="fas fa-triangle-exclamation me-1"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <input type="text" name="no_telp" id="no_telp" class="form-control" placeholder=" " required>
                                        <label for="no_telp">Nomor Telepon</label>
                                    </div>
                                </div>
                                @error('no_telp')
                                    <div class="text-danger mt-2 small"><i class="fas fa-triangle-exclamation me-1"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-location-dot"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <textarea name="alamat" id="alamat" class="form-control" style="height: 100px" placeholder=" " required></textarea>
                                        <label for="alamat">Alamat</label>
                                    </div>
                                </div>
                                @error('alamat')
                                    <div class="text-danger mt-2 small"><i class="fas fa-triangle-exclamation me-1"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <input type="password" name="password" id="password" class="form-control" placeholder=" " required>
                                        <label for="password">Password</label>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="text-danger mt-2 small"><i class="fas fa-triangle-exclamation me-1"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2 mb-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                                </button>
                            </div>
                            
                            <div class="text-center">
                                <p class="mb-0">Sudah punya akun? <a href="{{ route('login.page') }}" class="login-link">Masuk di sini</a></p>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center mt-4 text-secondary small">
                    <p>© 2025 Travel Booking. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>