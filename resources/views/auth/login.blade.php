<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5 col-xl-4">
                <div class="card login-card">
                    <div class="card-header">
                        <h2 class="mb-0">Selamat Datang</h2>
                        <p class="text-white-50">Silakan login untuk melanjutkan</p>
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

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
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

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <div class="form-floating flex-grow-1 position-relative">
                                        <input type="password" name="password" id="password" class="form-control" placeholder=" " required>
                                        <label for="password">Password</label>
                                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 password-toggle" onclick="togglePasswordVisibility()">
                                            <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                        </span>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="text-danger mt-2 small"><i class="fas fa-triangle-exclamation me-1"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3 d-flex justify-content-end">
                                <a href="#" class="forgot-password">Lupa password?</a>
                            </div>

                            <div class="d-grid gap-2 mb-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Masuk Sekarang
                                </button>
                            </div>
                            
                            <div class="text-center mb-3">
                                <p class="mb-0">Belum punya akun? <a href="{{ route('register.page') }}" class="register-link">Daftar di sini</a></p>
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
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>