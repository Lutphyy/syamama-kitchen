@extends('layouts.app')
@section('title', 'Keranjang Belanja - Syamama Kitchen')

@section('content')
<section class="section" style="padding-top:2rem;">
    <div class="container">
        <div class="section-header" style="text-align:left;">
            <h2>Keranjang Belanja</h2>
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
                                        {{ $item['product']->category->icon ?? '' }}
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
                                        <button type="submit" class="btn btn-danger btn-sm" style="padding:0.2rem 0.5rem; font-size:0.75rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px;"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h3 style="margin-bottom:1rem;">Ringkasan</h3>
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
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="width:18px;height:18px;fill:currentColor;"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.3-5.1-3.7-10.6-6.5z"/></svg>
                        Checkout via WhatsApp
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline w-full mt-1" style="justify-content:center;">
                        Lanjut Belanja
                    </a>
                </div>
            </div>
        @else
            <div class="empty-state">
                <div style="font-size:3rem; margin-bottom:1rem; color:var(--text-light);">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:64px;height:64px;"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                </div>
                <h3>Keranjang masih kosong nih!</h3>
                <p>Yuk pilih menu favorit kamu dan tambahkan ke keranjang.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Belanja Sekarang</a>
            </div>
        @endif
    </div>
</section>
@endsection
