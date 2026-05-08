@extends('admin.layouts.main')

@section('title', 'Manajemen Penumpang')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Manajemen Penumpang</h2>
            <p class="text-muted mb-0">Daftar semua penumpang yang terdaftar</p>
        </div>
        <div class="bg-white px-3 py-2 rounded-pill shadow-sm">
            <i class="fas fa-users text-primary me-2"></i>
            <span class="fw-medium">Total: {{ $users->total() }} Penumpang</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user-friends text-primary me-2"></i>Daftar Penumpang</h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="searchUser" class="form-control bg-light border-0" placeholder="Cari nama atau email...">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="userTable">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Penumpang</th>
                            <th class="px-4 py-3">No. Telepon</th>
                            <th class="px-4 py-3">Alamat</th>
                            <th class="px-4 py-3 text-center">Total Booking</th>
                            <th class="px-4 py-3">Terdaftar</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr class="user-row">
                                <td class="px-4 py-3 text-muted">{{ $users->firstItem() + $index }}</td>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-3 d-flex align-items-center justify-content-center rounded-circle fw-bold"
                                            style="width:40px;height:40px;font-size:16px;">
                                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-medium user-name">{{ $user->nama }}</p>
                                            <small class="text-muted user-email">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">{{ $user->no_telp }}</td>
                                <td class="px-4 py-3 text-truncate" style="max-width:150px;">{{ $user->alamat }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                        {{ $user->bookings_count }} Booking
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <small class="text-muted">{{ $user->created_at->format('d M Y') }}</small>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ '/admin/users/' . $user->id }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-user-slash text-muted fa-3x mb-3 d-block"></i>
                                    <p class="text-muted mb-0">Belum ada penumpang terdaftar</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
            <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <script>
        document.getElementById('searchUser').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            document.querySelectorAll('.user-row').forEach(row => {
                const name = row.querySelector('.user-name')?.textContent.toLowerCase() || '';
                const email = row.querySelector('.user-email')?.textContent.toLowerCase() || '';
                row.style.display = (name.includes(query) || email.includes(query)) ? '' : 'none';
            });
        });
    </script>
@endsection
