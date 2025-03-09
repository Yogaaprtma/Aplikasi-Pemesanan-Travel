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

                    @php
                        $hasSchedules = \App\Models\TravelSchedule::exists();
                        $hasBookings = \App\Models\Booking::where('user_id', Auth::id())->exists();
                    @endphp

                    <li class="nav-item">
                        @if($hasSchedules && $hasBookings)
                            <a href="{{ route('booking.history') }}" class="nav-link d-flex align-items-center text-white {{ request()->routeIs('booking.history') ? 'active' : '' }}">
                                <i class="fas fa-history"></i>
                                <span>History</span>
                            </a>
                        @else
                            <a href="javascript:void(0);" class="nav-link d-flex align-items-center text-white" onclick="showNoHistoryAlert()">
                                <i class="fas fa-history"></i>
                                <span>History</span>
                            </a>
                        @endif
                    </li>
                </ul>
            </div>
        </nav>

        <div class="p-3 border-top border-light mt-3">
            <a href="{{ route('logout') }}" class="nav-link text-white d-flex align-items-center">
                <i class="fas fa-sign-out-alt"></i>
                <span class="ms-3">Logout</span>
            </a>
        </div>
    </div>
</div>