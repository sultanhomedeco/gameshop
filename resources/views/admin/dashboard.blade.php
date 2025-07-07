@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard Admin</h1>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total User</h5>
                    <p class="display-6 fw-bold">{{ $stats['total_users'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Transaksi</h5>
                    <p class="display-6 fw-bold">{{ $stats['total_transactions'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Transaksi Pending</h5>
                    <p class="display-6 fw-bold">{{ $stats['pending_transactions'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="display-6 fw-bold">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">10 Transaksi Terbaru</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>User</th>
                            <th>Game</th>
                            <th>Paket</th>
                            <th>Status</th>
                            <th>Nominal</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_transactions as $trx)
                            <tr>
                                <td>{{ $trx->transaction_code }}</td>
                                <td>{{ $trx->user->name ?? '-' }}</td>
                                <td>{{ $trx->topupPackage->game->name ?? '-' }}</td>
                                <td>{{ $trx->topupPackage->name ?? '-' }}</td>
                                <td><span class="badge {{ $trx->status_badge_class }}">{{ $trx->status_text }}</span></td>
                                <td>{{ $trx->formatted_amount }}</td>
                                <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Grafik Revenue Bulanan ({{ date('Y') }})</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach(range(1,12) as $m)
                    <div class="col text-center">
                        <div class="fw-bold">{{ DateTime::createFromFormat('!m', $m)->format('M') }}</div>
                        <div class="bg-primary rounded mx-auto" style="height: {{ isset($monthly_revenue[$m-1]) ? max(10, $monthly_revenue[$m-1]['total']/10000) : 10 }}px; width: 20px;"></div>
                        <div class="small">Rp {{ isset($monthly_revenue[$m-1]) ? number_format($monthly_revenue[$m-1]['total'],0,',','.') : 0 }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection 