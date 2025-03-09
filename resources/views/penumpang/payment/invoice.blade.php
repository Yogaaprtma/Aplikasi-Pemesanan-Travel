@extends('penumpang.layouts.main')

@section('title', 'Invoice Pembayaran')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/penumpang/payment/invoice.css') }}">
@endsection

@section('content')
    <div class="container py-4">
        <div class="custom-breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home.penumpang') }}" class="text-decoration-none">
                        <i class="bi bi-house-door me-1"></i>Home
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('booking.history') }}" class="text-decoration-none">Riwayat Booking</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Invoice</li>
            </ol>
        </div>

        <div class="invoice-container">
            <div class="card invoice-card" id="invoice-content">
                <div class="invoice-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h2 class="m-0 fw-bold">INVOICE</h2>
                            <p class="mb-0 opacity-75">{{ date('d M Y', strtotime($payment->created_at)) }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h5 class="fw-bold mb-1">ID Pembayaran</h5>
                            <p class="mb-0">#{{ $payment->id }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="invoice-body">
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h5 class="fw-bold mb-3">Detail Pemesan</h5>
                            <p class="mb-1"><span class="fw-medium">Nama:</span> {{ $payment->booking->user->nama }}</p>
                            <p class="mb-0"><span class="fw-medium">Email:</span> {{ $payment->booking->user->email ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3">Detail Perjalanan</h5>
                            <p class="mb-1"><span class="fw-medium">Tujuan:</span> {{ $payment->booking->travelSchedule->destination }}</p>
                            <p class="mb-0">
                                <span class="fw-medium">Keberangkatan:</span> 
                                {{ \Carbon\Carbon::parse($payment->booking->travelSchedule->departure_time)->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="invoice-divider"></div>
                    
                    <div class="row">
                        <div class="col-12">
                            <h5 class="fw-bold mb-3">Detail Booking</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Deskripsi</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-end">Harga/Kursi</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $payment->booking->travelSchedule->destination }}</td>
                                            <td class="text-center">{{ $payment->booking->seats }}</td>
                                            <td class="text-end">Rp{{ number_format($payment->booking->travelSchedule->price, 0, ',', '.') }}</td>
                                            <td class="text-end fw-bold">Rp{{ number_format($payment->booking->travelSchedule->price * $payment->booking->seats, 0, ',', '.') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="invoice-total">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><span class="fw-medium">Metode Pembayaran:</span> {{ $payment->payment_method }}</p>
                                <p class="mb-0">
                                    <span class="fw-medium">Status:</span> 
                                    <span class="badge bg-success status-badge">
                                        <i class="bi bi-check-circle-fill me-1"></i> Lunas
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p class="mb-1 text-muted">Subtotal:</p>
                                <h4 class="fw-bold">Rp{{ number_format($payment->booking->travelSchedule->price * $payment->booking->seats, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="invoice-footer">
                <div class="action-buttons">
                    <a href="{{ route('home.penumpang') }}" class="btn btn-outline-secondary btn-download">
                        <i class="bi bi-house-door me-2"></i> Kembali ke Home
                    </a>
                    <button id="downloadPdf" class="btn btn-primary btn-download">
                        <i class="bi bi-file-earmark-pdf me-2"></i> Download Invoice
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="{{ asset('js/penumpang/payment/invoice.js') }}"></script>
@endsection