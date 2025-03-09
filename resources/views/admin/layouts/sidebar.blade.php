<div class="sidebar col-lg-2 col-md-3 px-0 animate__animated animate__fadeInLeft">
    <div class="d-flex flex-column h-100">
        <div class="p-3 mb-4 d-flex align-items-center">
            <i class="fas fa-bus-alt text-white fs-3 me-3"></i>
            <h1 class="text-white fs-4 fw-bold m-0">Travel Admin</h1>
        </div>
        
        <nav class="flex-grow-1">
            <div class="px-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('home.admin') }}" class="nav-link {{ request()->routeIs('home.admin') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('jadwal.page') }}" class="nav-link {{ request()->routeIs('jadwal.page') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Jadwal Travel</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.report') }}" class="nav-link {{ request()->routeIs('admin.report') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <div class="p-3 border-top border-secondary mt-3">
            <a href="{{ route('logout') }}" class="nav-link d-flex align-items-center">
                <i class="fas fa-sign-out-alt"></i>
                <span class="ms-3">Logout</span>
            </a>
        </div>
    </div>
</div>