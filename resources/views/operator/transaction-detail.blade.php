@extends('layouts.app')

@section('title', 'Detail Transaksi - Operator')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('operator.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('operator.pending-transactions') }}">Transaksi Pending</a></li>
                <li class="breadcrumb-item active">Detail Transaksi</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Detail Transaksi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Informasi Transaksi</h6>
                        <table class="table table-sm">
                            <tr><td>Kode Transaksi</td><td>: <strong>{{ $transaction->transaction_code }}</strong></td></tr>
                            <tr><td>Status</td><td>: <span class="badge {{ $transaction->status_badge_class }}">{{ $transaction->status_text }}</span></td></tr>
                            <tr><td>Tanggal Dibuat</td><td>: {{ $transaction->created_at->format('d/m/Y H:i') }}</td></tr>
                            <tr><td>Jumlah</td><td>: <strong class="text-primary">{{ $transaction->formatted_amount }}</strong></td></tr>
                            @if($transaction->discount_amount > 0)
                                <tr><td>Diskon</td><td>: <span class="text-success">-{{ 'Rp ' . number_format($transaction->discount_amount, 0, ',', '.') }}</span></td></tr>
                            @endif
                            @if($transaction->promo_code)
                                <tr><td>Kode Promo</td><td>: <span class="badge bg-info">{{ $transaction->promo_code }}</span></td></tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Informasi User</h6>
                        <table class="table table-sm">
                            <tr><td>Nama</td><td>: <strong>{{ $transaction->user->name }}</strong></td></tr>
                            <tr><td>Email</td><td>: {{ $transaction->user->email }}</td></tr>
                            <tr><td>Phone</td><td>: {{ $transaction->user->phone ?: '-' }}</td></tr>
                            <tr><td>Alamat</td><td>: {{ $transaction->user->address ?: '-' }}</td></tr>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Informasi Game</h6>
                        <table class="table table-sm">
                            <tr><td>Game</td><td>: <strong>{{ $transaction->topupPackage->game->name }}</strong></td></tr>
                            <tr><td>Mata Uang</td><td>: {{ $transaction->topupPackage->game->currency_name }}</td></tr>
                            <tr><td>Paket</td><td>: <strong>{{ $transaction->topupPackage->name }}</strong></td></tr>
                            <tr><td>Jumlah Game</td><td>: {{ $transaction->topupPackage->amount }} {{ $transaction->topupPackage->game->currency_name }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Informasi Player</h6>
                        <table class="table table-sm">
                            <tr><td>Player ID</td><td>: <strong>{{ $transaction->player_id }}</strong></td></tr>
                            <tr><td>Player Name</td><td>: {{ $transaction->player_name ?: '-' }}</td></tr>
                        </table>
                    </div>
                </div>
                
                @if($transaction->notes)
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary">Catatan</h6>
                            <div class="alert alert-light">
                                {{ $transaction->notes }}
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($transaction->processedBy)
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary">Informasi Pemrosesan</h6>
                            <table class="table table-sm">
                                <tr><td>Diproses Oleh</td><td>: <strong>{{ $transaction->processedBy->name }}</strong></td></tr>
                                <tr><td>Tanggal Diproses</td><td>: {{ $transaction->processed_at->format('d/m/Y H:i') }}</td></tr>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Aksi</h5>
            </div>
            <div class="card-body">
                @if($transaction->status === 'pending')
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#processModal">
                            <i class="fas fa-check me-2"></i>Proses Transaksi
                        </button>
                        <a href="{{ route('operator.pending-transactions') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                @else
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#processModal">
                            <i class="fas fa-edit me-2"></i>Update Status
                        </button>
                        <a href="{{ route('operator.pending-transactions') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Status</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Transaksi Dibuat</h6>
                            <p class="text-muted mb-0">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @if($transaction->processed_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Diproses oleh {{ $transaction->processedBy->name }}</h6>
                                <p class="text-muted mb-0">{{ $transaction->processed_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Process Modal -->
<div class="modal fade" id="processModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proses Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('operator.transaction.process', $transaction) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Konfirmasi:</strong> Anda akan memproses transaksi {{ $transaction->transaction_code }}
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Baru</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="processing" {{ $transaction->status === 'processing' ? 'selected' : '' }}>Diproses</option>
                            <option value="completed" {{ $transaction->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="failed" {{ $transaction->status === 'failed' ? 'selected' : '' }}>Gagal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Tambahkan catatan jika diperlukan...">{{ $transaction->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-marker {
    position: absolute;
    left: -35px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}
.timeline-content {
    padding-left: 10px;
}
.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: -29px;
    top: 12px;
    width: 2px;
    height: calc(100% + 8px);
    background-color: #dee2e6;
}
</style>
@endsection 