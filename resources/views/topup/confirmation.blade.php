@extends('layouts.app')

@section('title', 'Konfirmasi Top Up - GameShop')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">
                    <i class="fas fa-check-circle me-2"></i>Pesanan Berhasil Dibuat!
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <h5><i class="fas fa-info-circle me-2"></i>Detail Pesanan</h5>
                    <p class="mb-0">Pesanan top-up Anda telah berhasil dibuat dan sedang menunggu pembayaran.</p>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h5>Informasi Transaksi</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Kode Transaksi:</strong></td>
                                <td>{{ $transaction->transaction_code }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal:</strong></td>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td><span class="badge {{ $transaction->status_badge_class }}">{{ $transaction->status_text }}</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Detail Top Up</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Game:</strong></td>
                                <td>{{ $transaction->topupPackage->game->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Paket:</strong></td>
                                <td>{{ $transaction->topupPackage->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jumlah:</strong></td>
                                <td>{{ $transaction->topupPackage->formatted_amount }}</td>
                            </tr>
                            <tr>
                                <td><strong>Harga:</strong></td>
                                <td>{{ $transaction->formatted_amount }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Informasi Player</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>ID Player:</strong></td>
                                <td>{{ $transaction->player_id }}</td>
                            </tr>
                            @if($transaction->player_name)
                            <tr>
                                <td><strong>Nama Player:</strong></td>
                                <td>{{ $transaction->player_name }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Langkah Selanjutnya:</h6>
                    <ol class="mb-0">
                        <li>Lakukan pembayaran sebesar <strong>{{ $transaction->formatted_amount }}</strong></li>
                        <li>Kirim bukti pembayaran ke customer service</li>
                        <li>Top-up akan diproses maksimal 5 menit setelah pembayaran diterima</li>
                        <li>Anda akan mendapat notifikasi ketika top-up selesai diproses</li>
                    </ol>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('user.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt me-2"></i>Lihat Dashboard
                    </a>
                    <a href="{{ route('user.transactions') }}" class="btn btn-outline-primary">
                        <i class="fas fa-history me-2"></i>Lihat Riwayat Transaksi
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 