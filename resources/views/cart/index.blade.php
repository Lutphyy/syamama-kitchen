@extends('layouts.app')
@section('title', 'Keranjang Belanja - Syamama Kitchen 🛒')

@section('content')
<section class="section" style="padding-top:2rem;">
    <div class="container">
        <div class="section-header" style="text-align:left;">
            <h2>🛒 Keranjang Belanja</h2>
            <p>Review pesananmu sebelum checkout!</p>
        </div>

        @if(count($cartItems) > 0)
            <div class="cart-layout">
                <!-- Cart Items -->
                <div>
                    <div class="card" style="overflow:visible;">
                        @foreach($cartItems as $item)
                            <div class="cart-item fade-in">
                                <div class="cart-item-image">
                                    @if($item['product']->image)
                                        <img src="{{ asset('storage/' . $item['product']->image) }}" alt="{{ $item['product']->name }}">
                                    @else
                                        {{ $item['product']->category->icon ?? '🍰' }}
                                    @endif
                                </div>
                                <div class="cart-item-info">
                                    <h4>{{ $item['product']->name }}</h4>
                                    <span class="price">{{ $item['product']->formatted_price }}</span>
                                </div>
                                <div>
                                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-1">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                        <div class="qty-control">
                                            <button type="button" onclick="this.parentNode.querySelector('input').stepDown(); this.closest('form').submit();">−</button>
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" max="{{ $item['product']->stock }}" onchange="this.closest('form').submit();">
                                            <button type="button" onclick="this.parentNode.querySelector('input').stepUp(); this.closest('form').submit();">+</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="text-right" style="min-width:100px;">
                                    <div class="font-bold text-primary">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                                    <form action="{{ route('cart.remove') }}" method="POST" style="margin-top:0.3rem;">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                        <button type="submit" class="btn btn-danger btn-sm" style="padding:0.2rem 0.5rem; font-size:0.75rem;">🗑️</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h3 style="margin-bottom:1rem;">📋 Ringkasan</h3>
                    @foreach($cartItems as $item)
                        <div class="summary-row">
                            <span class="text-sm">{{ $item['product']->name }} (x{{ $item['quantity'] }})</span>
                            <span class="text-sm font-bold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <div class="summary-row summary-total">
                        <span class="font-bold">Total</span>
                        <span class="total-price">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('checkout') }}" class="btn btn-whatsapp btn-lg w-full mt-2" style="justify-content:center;">
                        📱 Checkout via WhatsApp
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline w-full mt-1" style="justify-content:center;">
                        🛍️ Lanjut Belanja
                    </a>
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="emoji">🛒</div>
                <h3>Keranjang masih kosong nih!</h3>
                <p>Yuk pilih menu favorit kamu dan tambahkan ke keranjang.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">🛍️ Belanja Sekarang</a>
            </div>
        @endif
    </div>
</section>
@endsection
