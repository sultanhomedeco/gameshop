@extends('layouts.app')

@section('title', 'Transaksi Diproses - Operator')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-3">Transaksi Diproses</h1>
        <p class="text-muted">Daftar transaksi yang telah Anda proses</p>
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
                <form method="GET" action="{{ route('operator.processed-transactions') }}">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Semua Status</option>
                                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Diproses</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3 mb-3">
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
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Transaksi Diproses</h5>
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
                                    <th>Tanggal Diproses</th>
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
                                        <td>{{ $transaction->processed_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('operator.transaction.detail', $transaction) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada transaksi diproses</h5>
                        <p class="text-muted">Transaksi yang Anda proses akan muncul di sini.</p>
                        <a href="{{ route('operator.pending-transactions') }}" class="btn btn-primary">
                            <i class="fas fa-clock me-2"></i>Lihat Transaksi Pending
                        </a>
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