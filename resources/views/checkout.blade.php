@extends('layouts.app')
@section('title', 'Checkout - Syamama Kitchen')

@section('content')
<section class="section" style="padding-top:2rem;">
    <div class="container">
        <div class="section-header" style="text-align:left;">
            <h2>Checkout</h2>
            <p>Lengkapi data kamu untuk order via WhatsApp!</p>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="checkout-grid">
                <!-- Form -->
                <div class="card" style="padding:2rem;">
                    <h3 style="margin-bottom:1.5rem;">Data Pemesan</h3>

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', auth()->user()->name ?? '') }}" placeholder="Masukkan nama lengkap" required>
                        @error('customer_name') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor WhatsApp *</label>
                        <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" placeholder="contoh: 081234567890" required>
                        @error('customer_phone') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat Pengiriman *</label>
                        <textarea name="customer_address" class="form-control" placeholder="Masukkan alamat lengkap untuk pengiriman" required>{{ old('customer_address', auth()->user()->address ?? '') }}</textarea>
                        @error('customer_address') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="customer_note" class="form-control" placeholder="contoh: tolong dibungkus terpisah, kirim jam 5 sore, dll" style="min-height:80px;">{{ old('customer_note') }}</textarea>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="cart-summary">
                    <h3 style="margin-bottom:1rem;">Pesanan Kamu</h3>
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
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="width:18px;height:18px;fill:currentColor;"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.3-5.1-3.7-10.6-6.5z"/></svg>
                        Order via WhatsApp
                    </button>

                    <p class="text-sm text-light text-center mt-2">
                        Klik tombol di atas akan mengirim pesananmu<br>ke WhatsApp Syamama Kitchen
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
