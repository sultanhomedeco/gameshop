@extends('layouts.app')

@section('title', 'Manajemen Transaksi - Admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-3">Manajemen Transaksi</h1>
    </div>
</div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Transaksi</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.transactions') }}">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Diproses</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="user_id" class="form-label">User</label>
                            <select class="form-select" id="user_id" name="user_id">
                                <option value="">Semua User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Transaksi</h5>
            </div>
            <div class="card-body">
                @if($transactions->count())
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>User</th>
                                    <th>Game</th>
                                    <th>Paket</th>
                                    <th>Player ID</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Diproses Oleh</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $transaction->transaction_code }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-bold">{{ $transaction->user->name }}</div>
                                                <small class="text-muted">{{ $transaction->user->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-gamepad me-2 text-primary"></i>
                                                {{ $transaction->topupPackage->game->name }}
                                            </div>
                                        </td>
                                        <td>{{ $transaction->topupPackage->name }}</td>
                                        <td>
                                            <div>
                                                <div class="fw-bold">{{ $transaction->player_id }}</div>
                                                @if($transaction->player_name)
                                                    <small class="text-muted">{{ $transaction->player_name }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-primary">{{ $transaction->formatted_amount }}</span>
                                            @if($transaction->discount_amount > 0)
                                                <br><small class="text-success">-{{ 'Rp ' . number_format($transaction->discount_amount, 0, ',', '.') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $transaction->status_badge_class }}">{{ $transaction->status_text }}</span>
                                        </td>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($transaction->processedBy)
                                                <div>
                                                    <div class="fw-bold">{{ $transaction->processedBy->name }}</div>
                                                    <small class="text-muted">{{ $transaction->processed_at->format('d/m/Y H:i') }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transaction->status === 'pending')
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#processModal{{ $transaction->id }}">
                                                    <i class="fas fa-check"></i> Proses
                                                </button>
                                            @elseif($transaction->status === 'processing')
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#processModal{{ $transaction->id }}">
                                                    <i class="fas fa-edit"></i> Update
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $transaction->id }}">
                                                    <i class="fas fa-eye"></i> Detail
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Process Modal -->
                                    <div class="modal fade" id="processModal{{ $transaction->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Proses Transaksi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.transactions.process', $transaction) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="status" class="form-label">Status</label>
                                                            <select class="form-select" id="status" name="status" required>
                                                                <option value="processing" {{ $transaction->status === 'processing' ? 'selected' : '' }}>Diproses</option>
                                                                <option value="completed" {{ $transaction->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                                                <option value="failed" {{ $transaction->status === 'failed' ? 'selected' : '' }}>Gagal</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="notes" class="form-label">Catatan</label>
                                                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ $transaction->notes }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Detail Modal -->
                                    <div class="modal fade" id="detailModal{{ $transaction->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Transaksi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6>Informasi Transaksi</h6>
                                                            <table class="table table-sm">
                                                                <tr><td>Kode</td><td>: {{ $transaction->transaction_code }}</td></tr>
                                                                <tr><td>Status</td><td>: <span class="badge {{ $transaction->status_badge_class }}">{{ $transaction->status_text }}</span></td></tr>
                                                                <tr><td>Tanggal</td><td>: {{ $transaction->created_at->format('d/m/Y H:i') }}</td></tr>
                                                                <tr><td>Jumlah</td><td>: {{ $transaction->formatted_amount }}</td></tr>
                                                                @if($transaction->discount_amount > 0)
                                                                    <tr><td>Diskon</td><td>: {{ 'Rp ' . number_format($transaction->discount_amount, 0, ',', '.') }}</td></tr>
                                                                @endif
                                                                @if($transaction->promo_code)
                                                                    <tr><td>Kode Promo</td><td>: {{ $transaction->promo_code }}</td></tr>
                                                                @endif
                                                            </table>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6>Informasi User</h6>
                                                            <table class="table table-sm">
                                                                <tr><td>Nama</td><td>: {{ $transaction->user->name }}</td></tr>
                                                                <tr><td>Email</td><td>: {{ $transaction->user->email }}</td></tr>
                                                                <tr><td>Phone</td><td>: {{ $transaction->user->phone ?: '-' }}</td></tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-12">
                                                            <h6>Informasi Game</h6>
                                                            <table class="table table-sm">
                                                                <tr><td>Game</td><td>: {{ $transaction->topupPackage->game->name }}</td></tr>
                                                                <tr><td>Paket</td><td>: {{ $transaction->topupPackage->name }}</td></tr>
                                                                <tr><td>Player ID</td><td>: {{ $transaction->player_id }}</td></tr>
                                                                @if($transaction->player_name)
                                                                    <tr><td>Player Name</td><td>: {{ $transaction->player_name }}</td></tr>
                                                                @endif
                                                            </table>
                                                        </div>
                                                    </div>
                                                    @if($transaction->notes)
                                                        <div class="row mt-3">
                                                            <div class="col-12">
                                                                <h6>Catatan</h6>
                                                                <p class="text-muted">{{ $transaction->notes }}</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada transaksi</h5>
                        <p class="text-muted">Transaksi akan muncul di sini setelah user melakukan top-up.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    border-top: none;
    font-weight: 600;
}
</style>
@endsection 