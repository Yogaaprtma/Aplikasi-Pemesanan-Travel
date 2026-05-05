@extends('admin.layouts.main')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Konfirmasi Pembayaran</h2>
            <p class="text-muted mb-0">Kelola pembayaran yang menunggu konfirmasi</p>
        </div>
        <div class="d-flex gap-2">
            <div class="bg-white px-3 py-2 rounded-pill shadow-sm">
                <i class="fas fa-clock text-warning me-2"></i>
                <span class="fw-medium">{{ $pendingPayments->total() }} Menunggu</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Pembayaran Pending --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-warning bg-opacity-10 border-bottom py-3">
            <h5 class="fw-bold mb-0 text-warning">
                <i class="fas fa-hourglass-half me-2"></i>Menunggu Konfirmasi
                <span class="badge bg-warning text-dark ms-2">{{ $pendingPayments->total() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            @forelse($pendingPayments as $payment)
                <div class="border-bottom p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-5">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center rounded-circle fw-bold flex-shrink-0"
                                    style="width:45px;height:45px;font-size:18px;">
                                    {{ strtoupper(substr($payment->booking->user->nama ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="fw-bold mb-0">{{ $payment->booking->user->nama ?? '-' }}</p>
                                    <small class="text-muted">{{ $payment->booking->user->email ?? '-' }}</small>
                                    <div class="mt-1">
                                        <span class="badge bg-light text-dark">
                                            {{ $payment->booking->booking_code ?? '#' . $payment->booking->id }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mt-3 mt-lg-0">
                            <p class="mb-1 fw-medium">
                                <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                {{ $payment->booking->travelSchedule->origin ?? '-' }} → {{ $payment->booking->travelSchedule->destination ?? '-' }}
                            </p>
                            <small class="text-muted d-block">
                                <i class="fas fa-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($payment->booking->travelSchedule->departure_time)->format('d M Y, H:i') }}
                            </small>
                            <small class="text-muted d-block">
                                <i class="fas fa-credit-card me-1"></i>{{ $payment->payment_method }}
                                &nbsp;|&nbsp;
                                <i class="fas fa-users me-1"></i>{{ $payment->booking->seats }} kursi
                            </small>
                        </div>
                        <div class="col-lg-3 mt-3 mt-lg-0 text-end">
                            <p class="fw-bold text-success fs-5 mb-2">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </p>
                            @if($payment->payment_proof)
                                <a href="{{ Storage::url($payment->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-secondary mb-2 d-block">
                                    <i class="fas fa-image me-1"></i>Lihat Bukti
                                </a>
                            @else
                                <p class="text-muted small mb-2">Tidak ada bukti</p>
                            @endif
                            <div class="d-flex gap-2 justify-content-end">
                                <form action="{{ '/admin/payments/' . $payment->id . '/confirm' }}" method="POST" class="confirm-form">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm px-3">
                                        <i class="fas fa-check me-1"></i>Konfirmasi
                                    </button>
                                </form>
                                <form action="{{ '/admin/payments/' . $payment->id . '/reject' }}" method="POST" class="reject-form">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-danger btn-sm px-3">
                                        <i class="fas fa-times me-1"></i>Tolak
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-check-circle text-success fa-3x mb-3 d-block"></i>
                    <p class="text-muted">Tidak ada pembayaran yang menunggu konfirmasi</p>
                </div>
            @endforelse
        </div>
        @if($pendingPayments->hasPages())
            <div class="card-footer bg-white py-3 d-flex justify-content-center">
                {{ $pendingPayments->links() }}
            </div>
        @endif
    </div>

    {{-- Pembayaran Terkonfirmasi --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-success bg-opacity-10 border-bottom py-3">
            <h5 class="fw-bold mb-0 text-success">
                <i class="fas fa-check-circle me-2"></i>Sudah Dikonfirmasi (5 Terbaru)
            </h5>
        </div>
        <div class="card-body p-0">
            @forelse($confirmedPayments as $payment)
                <div class="border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fw-medium">{{ $payment->booking->user->nama ?? '-' }}</span>
                        <span class="text-muted mx-2">→</span>
                        <span>{{ $payment->booking->travelSchedule->destination ?? '-' }}</span>
                        <small class="text-muted d-block">
                            {{ $payment->invoice_number }}
                            &nbsp;|&nbsp;
                            Dikonfirmasi: {{ $payment->paid_at?->format('d M Y, H:i') }}
                        </small>
                    </div>
                    <span class="fw-bold text-success">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>
            @empty
                <div class="text-center py-4">
                    <p class="text-muted mb-0">Belum ada pembayaran yang dikonfirmasi</p>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.confirm-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi Pembayaran?',
                    text: 'Tindakan ini akan mengkonfirmasi pembayaran dan booking akan diaktifkan.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Konfirmasi!',
                    cancelButtonText: 'Batal',
                }).then(result => { if (result.isConfirmed) form.submit(); });
            });
        });
        document.querySelectorAll('.reject-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Tolak Pembayaran?',
                    text: 'Booking akan dibatalkan dan kuota akan dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Batal',
                }).then(result => { if (result.isConfirmed) form.submit(); });
            });
        });
    </script>
@endsection
