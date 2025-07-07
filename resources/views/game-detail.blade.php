@extends('layouts.app')

@section('title', $game->name . ' - GameShop')

@section('content')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">{{ $game->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        @if($game->image)
            <img src="{{ $game->image }}" class="img-fluid rounded shadow" alt="{{ $game->name }}">
        @else
            <div class="bg-light rounded shadow d-flex align-items-center justify-content-center" style="height: 300px;">
                <i class="fas fa-gamepad fa-4x text-muted"></i>
            </div>
        @endif
    </div>
    <div class="col-md-8">
        <h1 class="mb-3">{{ $game->name }}</h1>
        <p class="lead text-muted">{{ $game->description ?: 'Top-up ' . $game->currency_name . ' untuk ' . $game->name }}</p>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-coins fa-2x mb-2"></i>
                        <h5>Mata Uang</h5>
                        <p class="mb-0">{{ $game->currency_name }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <h5>Status</h5>
                        <p class="mb-0">{{ $game->is_active ? 'Aktif' : 'Tidak Aktif' }}</p>
                    </div>
                </div>
            </div>
        </div>

        @auth
            <div class="d-grid">
                <a href="{{ route('topup.form', $game) }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-cart me-2"></i>Top Up Sekarang
                </a>
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Silakan <a href="{{ route('login') }}">masuk</a> atau <a href="{{ route('register') }}">daftar</a> untuk melakukan top-up.
            </div>
        @endauth
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Paket Top-up Tersedia</h2>
    </div>
</div>

<div class="row">
    @forelse($packages as $package)
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card h-100 package-card">
                <div class="card-body text-center">
                    <div class="package-icon mb-3">
                        <i class="fas fa-gem fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">{{ $package->name }}</h5>
                    <p class="card-text text-muted">{{ $package->description ?: $package->amount . ' ' . $game->currency_name }}</p>
                    <div class="price-tag mb-3">
                        <span class="h4 text-primary fw-bold">{{ $package->formatted_price }}</span>
                    </div>
                    @auth
                        <div class="d-grid">
                            <a href="{{ route('topup.form', $game) }}?package={{ $package->id }}" class="btn btn-outline-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Pilih Paket
                            </a>
                        </div>
                    @else
                        <div class="d-grid">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Masuk untuk Top-up
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Belum ada paket tersedia</h4>
                <p class="text-muted">Silakan hubungi admin untuk menambahkan paket top-up.</p>
            </div>
        </div>
    @endforelse
</div>

<style>
.package-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 2px solid transparent;
}
.package-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border-color: #ff6b35;
}
.package-icon {
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.price-tag {
    background: linear-gradient(135deg, #ff6b35 0%, #e55a2b 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    display: inline-block;
}
</style>
@endsection 