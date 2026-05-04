<div class="sidebar col-lg-2 col-md-3 px-0 animate__animated animate__fadeInLeft">
    <div class="d-flex flex-column h-100 bg-primary text-white">
        <div class="p-3 mb-4 d-flex align-items-center">
            <i class="fas fa-bus text-white fs-3 me-3"></i>
            <h1 class="fs-4 fw-bold m-0">Booking</h1>
        </div>

        <nav class="flex-grow-1">
            <div class="px-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('home.penumpang') }}" class="nav-link d-flex align-items-center text-white {{ request()->routeIs('home.penumpang') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('booking.page') }}" class="nav-link d-flex align-items-center text-white {{ request()->routeIs('booking.page') ? 'active' : '' }}">
                            <i class="fas fa-ticket-alt"></i>
                            <span>Booking Saya</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('booking.history') }}" class="nav-link d-flex align-items-center text-white {{ request()->routeIs('booking.history') ? 'active' : '' }}">
                            <i class="fas fa-history"></i>
                            <span>Riwayat</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('profile.index') }}" class="nav-link d-flex align-items-center text-white {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                            <i class="fas fa-user-circle"></i>
                            <span>Profil Saya</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="p-3 border-top border-light mt-3">
            <div class="d-flex align-items-center mb-3">
                <div class="avatar-circle bg-white text-primary d-flex align-items-center justify-content-center rounded-circle fw-bold me-2"
                    style="width:36px;height:36px;font-size:14px;flex-shrink:0;">
                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="mb-0 fw-medium text-white text-truncate" style="font-size:13px;">{{ Auth::user()->nama }}</p>
                    <small class="text-white-50 text-truncate d-block" style="font-size:11px;">{{ Auth::user()->email }}</small>
                </div>
            </div>
            <a href="{{ route('logout') }}" class="nav-link text-white d-flex align-items-center">
                <i class="fas fa-sign-out-alt"></i>
                <span class="ms-3">Logout</span>
            </a>
        </div>
    </div>
</div>