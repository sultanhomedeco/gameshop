@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-3">Dashboard</h1>
        <p class="text-muted">Selamat datang kembali, {{ auth()->user()->name }}!</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ auth()->user()->transactions()->count() }}</h4>
                        <p class="mb-0">Total Transaksi</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-receipt fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ auth()->user()->transactions()->where('status', 'completed')->count() }}</h4>
                        <p class="mb-0">Transaksi Selesai</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ auth()->user()->transactions()->where('status', 'pending')->count() }}</h4>
                        <p class="mb-0">Menunggu Proses</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">Rp {{ number_format(auth()->user()->transactions()->where('status', 'completed')->sum('amount'), 0, ',', '.') }}</h4>
                        <p class="mb-0">Total Pengeluaran</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-wallet fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-gamepad me-2"></i>Top Up Game
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('user.transactions') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-history me-2"></i>Riwayat Transaksi
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-user me-2"></i>Edit Profil
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="#" class="btn btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#helpModal">
                            <i class="fas fa-question-circle me-2"></i>Bantuan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Transaksi Terbaru</h5>
                <a href="{{ route('user.transactions') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if(isset($transactions) && $transactions->count())
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Game</th>
                                    <th>Paket</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions->take(5) as $trx)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $trx->transaction_code }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-gamepad me-2 text-primary"></i>
                                                {{ $trx->topupPackage->game->name ?? '-' }}
                                            </div>
                                        </td>
                                        <td>{{ $trx->topupPackage->name ?? '-' }}</td>
                                        <td>
                                            <span class="fw-bold text-primary">{{ $trx->formatted_amount }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $trx->status_badge_class }}">{{ $trx->status_text }}</span>
                                        </td>
                                        <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($trx->status === 'pending')
                                                <form action="{{ route('topup.cancel', $trx) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin membatalkan transaksi ini?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($transactions->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('user.transactions') }}" class="btn btn-outline-primary">Lihat Semua Transaksi</a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada transaksi</h5>
                        <p class="text-muted">Mulai top-up game favorit Anda sekarang!</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-gamepad me-2"></i>Top Up Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-question-circle me-2"></i>Panduan Penggunaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-gamepad fa-2x text-primary mb-3"></i>
                                <h6>Top Up Game</h6>
                                <p class="small text-muted">Pilih game dan paket top-up yang diinginkan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-clock fa-2x text-warning mb-3"></i>
                                <h6>Proses Cepat</h6>
                                <p class="small text-muted">Transaksi diproses maksimal 5 menit</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-history fa-2x text-info mb-3"></i>
                                <h6>Riwayat Transaksi</h6>
                                <p class="small text-muted">Pantau status transaksi Anda</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-headset fa-2x text-success mb-3"></i>
                                <h6>Layanan 24/7</h6>
                                <p class="small text-muted">Customer service siap membantu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}
.card:hover {
    transform: translateY(-2px);
}
.table th {
    border-top: none;
    font-weight: 600;
}
</style>
@endsection 