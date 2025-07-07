@extends('layouts.app')

@section('title', 'Transaksi Menunggu - Operator')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-3">Transaksi Menunggu</h1>
        <p class="text-muted">Daftar transaksi yang menunggu untuk diproses</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Transaksi Pending</h5>
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
                                    <th>Tanggal</th>
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
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('operator.transaction.detail', $transaction) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#processModal{{ $transaction->id }}">
                                                    <i class="fas fa-check"></i> Proses
                                                </button>
                                            </div>
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
                                                <form action="{{ route('operator.transaction.process', $transaction) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="alert alert-info">
                                                            <strong>Informasi Transaksi:</strong><br>
                                                            Kode: {{ $transaction->transaction_code }}<br>
                                                            Game: {{ $transaction->topupPackage->game->name }}<br>
                                                            Paket: {{ $transaction->topupPackage->name }}<br>
                                                            Player ID: {{ $transaction->player_id }}<br>
                                                            Jumlah: {{ $transaction->formatted_amount }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="status" class="form-label">Status</label>
                                                            <select class="form-select" id="status" name="status" required>
                                                                <option value="processing">Diproses</option>
                                                                <option value="completed">Selesai</option>
                                                                <option value="failed">Gagal</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="notes" class="form-label">Catatan</label>
                                                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Tambahkan catatan jika diperlukan...">{{ $transaction->notes }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Proses Transaksi</button>
                                                    </div>
                                                </form>
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
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="text-success">Tidak ada transaksi pending</h5>
                        <p class="text-muted">Semua transaksi telah diproses dengan baik!</p>
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