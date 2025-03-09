<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Travel Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/main.css') }}">
    @yield('styles')
</head>
<body>
    <div class="overlay"></div>
    <div class="d-flex">
        @include('admin.layouts.sidebar')

        <div class="content-wrapper flex-grow-1">
            <nav class="navbar navbar-expand-lg navbar-light sticky-top">
                <div class="container-fluid">
                    <button id="sidebarToggle" class="btn d-lg-none me-3">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <a class="navbar-brand d-none d-lg-block animate__animated animate__fadeIn" href="#">
                        <i class="fas fa-bus-alt me-2"></i> Travel Admin
                    </a>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown">
                            <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <img class="avatar me-2" src="{{ asset('images/logo.jpg') }}" alt="Profile">
                                <span class="d-none d-md-inline-block">{{ Auth::user()->nama }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="container-fluid p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ asset('js/admin/main.js') }}"></script>
    @yield('scripts')
</body>
</html>