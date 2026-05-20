@extends('layouts.app')
@section('title', 'Checkout - Syamama Kitchen 📱')

@section('content')
<section class="section" style="padding-top:2rem;">
    <div class="container">
        <div class="section-header" style="text-align:left;">
            <h2>📱 Checkout</h2>
            <p>Lengkapi data kamu untuk order via WhatsApp!</p>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="checkout-grid">
                <!-- Form -->
                <div class="card" style="padding:2rem;">
                    <h3 style="margin-bottom:1.5rem;">📝 Data Pemesan</h3>

                    <div class="form-group">
                        <label class="form-label">👤 Nama Lengkap *</label>
                        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', auth()->user()->name ?? '') }}" placeholder="Masukkan nama lengkap" required>
                        @error('customer_name') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">📱 Nomor WhatsApp *</label>
                        <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" placeholder="contoh: 081234567890" required>
                        @error('customer_phone') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">📍 Alamat Pengiriman *</label>
                        <textarea name="customer_address" class="form-control" placeholder="Masukkan alamat lengkap untuk pengiriman" required>{{ old('customer_address', auth()->user()->address ?? '') }}</textarea>
                        @error('customer_address') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">📝 Catatan (opsional)</label>
                        <textarea name="customer_note" class="form-control" placeholder="contoh: tolong dibungkus terpisah, kirim jam 5 sore, dll" style="min-height:80px;">{{ old('customer_note') }}</textarea>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="cart-summary">
                    <h3 style="margin-bottom:1rem;">🛒 Pesanan Kamu</h3>
                    @foreach($cartItems as $item)
                        <div class="summary-row" style="align-items:flex-start;">
                            <div>
                                <span class="text-sm font-bold">{{ $item['product']->name }}</span>
                                <br>
                                <span class="text-sm text-light">{{ $item['product']->formatted_price }} × {{ $item['quantity'] }}</span>
                            </div>
                            <span class="text-sm font-bold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <div class="summary-row summary-total">
                        <span class="font-bold">Total</span>
                        <span class="total-price">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <button type="submit" class="btn btn-whatsapp btn-lg w-full mt-2" style="justify-content:center;">
                        📱 Order via WhatsApp
                    </button>

                    <p class="text-sm text-light text-center mt-2">
                        Klik tombol di atas akan mengirim pesananmu<br>ke WhatsApp Syamama Kitchen 💛
                    </p>

                    <a href="{{ route('cart.index') }}" class="btn btn-outline w-full mt-1" style="justify-content:center;">
                        ← Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
