@extends('layouts.app')

@section('title', 'Top Up ' . $game->name . ' - GameShop')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">
                    <i class="fas fa-gamepad me-2"></i>Top Up {{ $game->name }}
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('topup.process', $game) }}">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            @if($game->image)
                                <img src="{{ $game->image }}" class="img-fluid rounded" alt="{{ $game->name }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-gamepad fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h4>{{ $game->name }}</h4>
                            <p class="text-muted">{{ $game->description }}</p>
                            <p><strong>Mata Uang:</strong> {{ $game->currency_name }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="topup_package_id" class="form-label">Pilih Nominal Top Up</label>
                        <select class="form-select @error('topup_package_id') is-invalid @enderror" 
                                id="topup_package_id" name="topup_package_id" required>
                            <option value="">Pilih nominal...</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}" 
                                        data-price="{{ $package->price }}"
                                        {{ old('topup_package_id') == $package->id ? 'selected' : '' }}>
                                    {{ $package->name }} - {{ $package->formatted_price }}
                                </option>
                            @endforeach
                        </select>
                        @error('topup_package_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="player_id" class="form-label">ID Player</label>
                        <input type="text" class="form-control @error('player_id') is-invalid @enderror" 
                               id="player_id" name="player_id" value="{{ old('player_id') }}" 
                               placeholder="Masukkan ID player Anda" required>
                        @error('player_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Pastikan ID player sudah benar untuk menghindari kesalahan pengiriman.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="player_name" class="form-label">Nama Player (Opsional)</label>
                        <input type="text" class="form-control @error('player_name') is-invalid @enderror" 
                               id="player_name" name="player_name" value="{{ old('player_name') }}" 
                               placeholder="Masukkan nama player Anda">
                        @error('player_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="promo_code" class="form-label">Kode Promo (Opsional)</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('promo_code') is-invalid @enderror" 
                                   id="promo_code" name="promo_code" value="{{ old('promo_code') }}" 
                                   placeholder="Masukkan kode promo jika ada">
                            <button type="button" class="btn btn-outline-primary" id="applyPromo">
                                <i class="fas fa-tag me-1"></i>Terapkan
                            </button>
                        </div>
                        @error('promo_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="promoMessage" class="form-text"></div>
                    </div>

                    <div id="priceSummary" class="mb-3" style="display: none;">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Ringkasan Harga</h6>
                                <div class="row">
                                    <div class="col-6">Harga Asli:</div>
                                    <div class="col-6 text-end" id="originalPrice">-</div>
                                </div>
                                <div class="row" id="discountRow" style="display: none;">
                                    <div class="col-6">Diskon:</div>
                                    <div class="col-6 text-end text-success" id="discountAmount">-</div>
                                </div>
                                <hr>
                                <div class="row fw-bold">
                                    <div class="col-6">Total Bayar:</div>
                                    <div class="col-6 text-end text-primary" id="finalPrice">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Informasi Penting:</h6>
                        <ul class="mb-0">
                            <li>Pastikan ID player sudah benar sebelum melakukan pembayaran</li>
                            <li>Proses top-up akan diproses maksimal 5 menit setelah pembayaran</li>
                            <li>Jika ada masalah, silakan hubungi customer service</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-cart me-2"></i>Lanjutkan ke Pembayaran
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentPromo = null;

document.getElementById('topup_package_id').addEventListener('change', function() {
    updatePriceSummary();
});

document.getElementById('applyPromo').addEventListener('click', function() {
    const promoCode = document.getElementById('promo_code').value.trim();
    if (!promoCode) {
        showPromoMessage('Masukkan kode promo terlebih dahulu', 'danger');
        return;
    }
    
    // Simulate promo validation (in real app, this would be an AJAX call)
    validatePromo(promoCode);
});

function validatePromo(promoCode) {
    // This is a simplified version - in real app, make AJAX call to backend
    const selectedPackage = document.getElementById('topup_package_id').value;
    if (!selectedPackage) {
        showPromoMessage('Pilih paket terlebih dahulu', 'danger');
        return;
    }
    
    // Simulate API call
    fetch(`/api/validate-promo?code=${promoCode}&package_id=${selectedPackage}`)
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                currentPromo = data.promo;
                showPromoMessage(`Promo berhasil diterapkan! ${data.promo.discount_text}`, 'success');
                updatePriceSummary();
            } else {
                showPromoMessage(data.message || 'Kode promo tidak valid', 'danger');
            }
        })
        .catch(error => {
            // Fallback for demo - simulate some promos
            simulatePromoValidation(promoCode);
        });
}

function simulatePromoValidation(promoCode) {
    const promos = {
        'WELCOME10': { discount_type: 'percentage', discount_value: 10, name: 'Welcome Bonus 10%' },
        'GAMING5000': { discount_type: 'fixed', discount_value: 5000, name: 'Gaming Festival' },
        'BONUS100': { discount_type: 'bonus', discount_value: 100, name: 'Bonus 100 Diamond' }
    };
    
    if (promos[promoCode]) {
        currentPromo = promos[promoCode];
        showPromoMessage(`Promo berhasil diterapkan! ${getDiscountText(currentPromo)}`, 'success');
        updatePriceSummary();
    } else {
        showPromoMessage('Kode promo tidak valid atau sudah kadaluarsa', 'danger');
    }
}

function getDiscountText(promo) {
    switch(promo.discount_type) {
        case 'percentage': return `${promo.discount_value}%`;
        case 'fixed': return `Rp ${promo.discount_value.toLocaleString()}`;
        case 'bonus': return `+${promo.discount_value} Bonus`;
        default: return '';
    }
}

function showPromoMessage(message, type) {
    const messageDiv = document.getElementById('promoMessage');
    messageDiv.className = `form-text text-${type}`;
    messageDiv.textContent = message;
}

function updatePriceSummary() {
    const selectedOption = document.getElementById('topup_package_id').options[document.getElementById('topup_package_id').selectedIndex];
    const price = parseFloat(selectedOption.dataset.price);
    
    if (price) {
        const originalPrice = price;
        let discountAmount = 0;
        let finalPrice = originalPrice;
        
        if (currentPromo) {
            if (currentPromo.discount_type === 'percentage') {
                discountAmount = originalPrice * (currentPromo.discount_value / 100);
                finalPrice = originalPrice - discountAmount;
            } else if (currentPromo.discount_type === 'fixed') {
                discountAmount = Math.min(currentPromo.discount_value, originalPrice);
                finalPrice = originalPrice - discountAmount;
            }
        }
        
        document.getElementById('originalPrice').textContent = `Rp ${originalPrice.toLocaleString()}`;
        document.getElementById('finalPrice').textContent = `Rp ${finalPrice.toLocaleString()}`;
        
        if (discountAmount > 0) {
            document.getElementById('discountAmount').textContent = `-Rp ${discountAmount.toLocaleString()}`;
            document.getElementById('discountRow').style.display = 'block';
        } else {
            document.getElementById('discountRow').style.display = 'none';
        }
        
        document.getElementById('priceSummary').style.display = 'block';
    } else {
        document.getElementById('priceSummary').style.display = 'none';
    }
}
</script>
@endpush
@endsection 