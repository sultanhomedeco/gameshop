@extends('layouts.app')

@section('title', 'Dashboard Operator')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard Operator</h1>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Transaksi Pending</h5>
                    <p class="display-6 fw-bold">{{ $stats['pending_count'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Diproses Hari Ini</h5>
                    <p class="display-6 fw-bold">{{ $stats['processed_today'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">10 Transaksi Pending Terbaru</h5>
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pending_transactions as $trx)
                            <tr>
                                <td>{{ $trx->transaction_code }}</td>
                                <td>{{ $trx->user->name ?? '-' }}</td>
                                <td>{{ $trx->topupPackage->game->name ?? '-' }}</td>
                                <td>{{ $trx->topupPackage->name ?? '-' }}</td>
                                <td><span class="badge {{ $trx->status_badge_class }}">{{ $trx->status_text }}</span></td>
                                <td>{{ $trx->formatted_amount }}</td>
                                <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#processModal{{ $trx->id }}">
                                        <i class="fas fa-check"></i> Proses
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Proses Transaksi -->
                            <div class="modal fade" id="processModal{{ $trx->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Proses Transaksi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('operator.transaction.process', $trx) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="alert alert-info">
                                                    <strong>Informasi Transaksi:</strong><br>
                                                    Kode: {{ $trx->transaction_code }}<br>
                                                    Game: {{ $trx->topupPackage->game->name }}<br>
                                                    Paket: {{ $trx->topupPackage->name }}<br>
                                                    Player ID: {{ $trx->player_id }}<br>
                                                    Jumlah: {{ $trx->formatted_amount }}
                                                </div>
                                                <div class="mb-3">
                                                    <label for="status{{ $trx->id }}" class="form-label">Status</label>
                                                    <select class="form-select" id="status{{ $trx->id }}" name="status" required>
                                                        <option value="processing">Diproses</option>
                                                        <option value="completed">Selesai</option>
                                                        <option value="failed">Gagal</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="notes{{ $trx->id }}" class="form-label">Catatan</label>
                                                    <textarea class="form-control" id="notes{{ $trx->id }}" name="notes" rows="3" placeholder="Tambahkan catatan jika diperlukan...">{{ $trx->notes }}</textarea>
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
                        @empty
                            <tr><td colspan="8" class="text-center text-muted">Belum ada transaksi pending.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">10 Transaksi Terakhir yang Diproses Anda</h5>
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_processed as $trx)
                            <tr>
                                <td>{{ $trx->transaction_code }}</td>
                                <td>{{ $trx->user->name ?? '-' }}</td>
                                <td>{{ $trx->topupPackage->game->name ?? '-' }}</td>
                                <td>{{ $trx->topupPackage->name ?? '-' }}</td>
                                <td><span class="badge {{ $trx->status_badge_class }}">{{ $trx->status_text }}</span></td>
                                <td>{{ $trx->formatted_amount }}</td>
                                <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($trx->status === 'processing')
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal{{ $trx->id }}">
                                            <i class="fas fa-edit"></i> Update
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal Update Status Transaksi -->
                            <div class="modal fade" id="updateModal{{ $trx->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Status Transaksi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('operator.transaction.process', $trx) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="alert alert-info">
                                                    <strong>Informasi Transaksi:</strong><br>
                                                    Kode: {{ $trx->transaction_code }}<br>
                                                    Game: {{ $trx->topupPackage->game->name }}<br>
                                                    Paket: {{ $trx->topupPackage->name }}<br>
                                                    Player ID: {{ $trx->player_id }}<br>
                                                    Jumlah: {{ $trx->formatted_amount }}
                                                </div>
                                                <div class="mb-3">
                                                    <label for="statusUpdate{{ $trx->id }}" class="form-label">Status</label>
                                                    <select class="form-select" id="statusUpdate{{ $trx->id }}" name="status" required>
                                                        <option value="processing" {{ $trx->status === 'processing' ? 'selected' : '' }}>Diproses</option>
                                                        <option value="completed" {{ $trx->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                                        <option value="failed" {{ $trx->status === 'failed' ? 'selected' : '' }}>Gagal</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="notesUpdate{{ $trx->id }}" class="form-label">Catatan</label>
                                                    <textarea class="form-control" id="notesUpdate{{ $trx->id }}" name="notes" rows="3" placeholder="Tambahkan catatan jika diperlukan...">{{ $trx->notes }}</textarea>
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
                        @empty
                            <tr><td colspan="8" class="text-center text-muted">Belum ada transaksi yang diproses.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 