@extends('admin.layouts.main')

@section('title', 'Laporan Travel')

@section('content')
    <div class="container-fluid py-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-4">
                <li class="breadcrumb-item"><a href="{{ route('home.admin') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Travel</li>
            </ol>
        </nav>

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="card-title mb-0">Laporan Jumlah Penumpang Per Travel</h5>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="travelTable">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" width="5%">#</th>
                                <th scope="col" width="35%">Nama Travel</th>
                                <th scope="col" width="30%">Tanggal Keberangkatan</th>
                                <th scope="col" width="20%">Jumlah Penumpang</th>
                                <th scope="col" width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $key => $report)
                                <tr data-departure="{{ \Carbon\Carbon::parse($report->departure_time)->format('Y-m-d') }}" data-destination="{{ $report->destination }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="travel-icon bg-light rounded-circle p-2 me-2">
                                                <i class="fas fa-bus text-primary"></i>
                                            </div>
                                            <span class="travel-name">{{ $report->destination }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            <span class="departure-date">{{ \Carbon\Carbon::parse($report->departure_time)->format('d M Y') }}</span>
                                        </span>
                                        <span class="badge bg-light text-dark">
                                            <i class="far fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($report->departure_time)->format('H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                @php
                                                    $capacity = 100;
                                                    $percentage = min(($report->bookings_count / $capacity) * 100, 100);
                                                    $colorClass = $percentage > 80 ? 'bg-success' : ($percentage > 50 ? 'bg-info' : 'bg-primary');
                                                @endphp
                                                <div class="progress-bar {{ $colorClass }}" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $report->bookings_count }}" aria-valuemin="0" aria-valuemax="{{ $capacity }}">
                                                    
                                                </div>
                                            </div>
                                            <span class="fw-bold">{{ $report->bookings_count }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.travel.report', $report->id) }}" class="btn btn-primary btn-sm rounded-pill">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr id="noDataRow">
                                    <td colspan="5" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                                            <p class="text-muted mb-0">Belum ada data laporan travel</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(isset($reports) && method_exists($reports, 'links'))
                    <div class="d-flex justify-content-center mt-4">
                        {{ $reports->links() }}
                    </div>
                @endif
            </div>
            
            <div class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center py-3">
                <div class="text-muted small mb-2 mb-md-0">
                    Total <span id="visibleRecords">{{ isset($reports) && method_exists($reports, 'total') ? $reports->total() : count($reports) }}</span> laporan ditemukan
                </div>
                <div class="text-muted small">
                    Terakhir diperbarui: {{ now()->format('d M Y H:i') }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/admin/report/report.js') }}"></script>
    @endpush
@endsection