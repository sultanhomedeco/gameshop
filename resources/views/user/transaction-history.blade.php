@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="container">
    <h1 class="mb-4">Riwayat Transaksi</h1>
    <div class="card">
        <div class="card-body">
            @if($transactions->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode Transaksi</th>
                                <th>Game</th>
                                <th>Paket</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Diproses Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $trx)
                                <tr>
                                    <td>{{ $trx->transaction_code }}</td>
                                    <td>{{ $trx->topupPackage->game->name ?? '-' }}</td>
                                    <td>{{ $trx->topupPackage->name ?? '-' }}</td>
                                    <td>{{ $trx->topupPackage->formatted_amount ?? '-' }}</td>
                                    <td><span class="badge {{ $trx->status_badge_class }}">{{ $trx->status_text }}</span></td>
                                    <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $trx->processedBy->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $transactions->links() }}
                </div>
            @else
                <p class="text-muted">Belum ada transaksi.</p>
            @endif
        </div>
    </div>
</div>
@endsection 