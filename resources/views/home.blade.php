@extends('layouts.app')

@section('title', 'Beranda - GameShop')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-primary">Selamat Datang di GameShop</h1>
            <p class="lead">Platform top-up game online terpercaya untuk Mobile Legends, Free Fire, PUBG, dan game populer lainnya</p>
        </div>
    </div>
</div>

@guest
<!-- Hero Section untuk Guest -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card bg-gradient-primary text-white border-0 shadow-lg">
            <div class="card-body p-5">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-3">Mulai Top Up Game Favorit Anda</h2>
                        <p class="lead mb-4">Daftar sekarang dan nikmati kemudahan top-up game online dengan proses cepat dan aman</p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg fw-semibold">
                                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg fw-semibold">
                                <i class="fas fa-sign-in-alt me-2"></i>Masuk
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="fas fa-gamepad fa-6x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endguest

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Game Tersedia</h2>
    </div>
</div>

<div class="row">
    @forelse($games as $game)
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card h-100">
                @if($game->image)
                    <img src="{{ $game->image }}" class="card-img-top" alt="{{ $game->name }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-gamepad fa-3x text-muted"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $game->name }}</h5>
                    <p class="card-text text-muted">{{ $game->description ?: 'Top-up ' . $game->currency_name . ' untuk ' . $game->name }}</p>
                    <div class="d-grid">
                        @auth
                            <a href="{{ route('topup.form', $game) }}" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Top Up Sekarang
                            </a>
                        @else
                            <a href="{{ route('game.detail', $game) }}" class="btn btn-outline-primary">
                                <i class="fas fa-info-circle me-2"></i>Lihat Detail
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-gamepad fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Belum ada game tersedia</h4>
                <p class="text-muted">Silakan hubungi admin untuk menambahkan game.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="row mt-5">
    <div class="col-12">
        <h2 class="mb-4">Mengapa Memilih GameShop?</h2>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-bolt fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Proses Cepat</h5>
                <p class="card-text">Top-up diproses dalam waktu singkat, maksimal 5 menit setelah pembayaran.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Aman & Terpercaya</h5>
                <p class="card-text">Transaksi aman dengan sistem keamanan yang terjamin.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Layanan 24/7</h5>
                <p class="card-text">Customer service siap membantu Anda kapan saja.</p>
            </div>
        </div>
    </div>
</div>

@guest
<!-- Call to Action untuk Guest -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card bg-light border-0">
            <div class="card-body text-center p-5">
                <h3 class="mb-3">Siap untuk Memulai?</h3>
                <p class="text-muted mb-4">Bergabunglah dengan ribuan pemain yang telah mempercayai GameShop untuk top-up game mereka</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-rocket me-2"></i>Mulai Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>Sudah Punya Akun?
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endguest

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #ff6b35 0%, #e55a2b 100%);
}
.card {
    transition: transform 0.2s ease-in-out;
}
.card:hover {
    transform: translateY(-5px);
}
</style>
@endsection 