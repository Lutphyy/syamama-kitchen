@extends('layouts.app')
@section('title', 'Syamama Kitchen - Kue & Makanan Rumahan Lezat')
    
@section('content')
<!-- Hero -->
<div class="hero-wrapper">
<section class="hero" id="heroSection">
    <!-- Floating emoji in hero -->
    <span class="hero-emoji" style="top:20%;left:15%;animation-delay:0s;">❤️</span>
    <span class="hero-emoji" style="top:60%;left:70%;animation-delay:1s;">🧋</span>
    <span class="hero-emoji" style="top:35%;left:80%;animation-delay:2s;">🍉</span>
    <div class="container">
        <div class="hero-content slide-up">
            <h1>Rasa Rumahan,<br>Kualitas Istimewa!</h1>
            <p>Kue kering, kue basah, makanan & minuman homemade dibuat dengan resep turun-temurun dan bahan pilihan terbaik.</p>
            <div class="hero-actions">
                <a href="{{ route('products.index') }}" class="btn btn-lg" style="background:white;color:var(--primary);font-weight:800;">Lihat Menu</a>
                <a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}" class="btn btn-lg btn-whatsapp" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="width:18px;height:18px;fill:currentColor;"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.3-5.1-3.7-10.6-6.5z"/></svg>
                    Chat WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>
</div>

<!-- Content slides over hero -->
<div class="content-overlay">

<!-- Categories -->
<section class="section">
    <div class="container">
        <div class="section-header fade-in">
            <h2>Kategori Menu</h2>
            <p>Pilih kategori favoritmu!</p>
        </div>
        <div class="category-pills" style="justify-content:center;">
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="category-pill">
                    {{ $category->icon }} {{ $category->name }}
                    <span class="text-sm text-light">({{ $category->products_count }})</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="section" style="background:var(--bg-warm); padding:4rem 0;">
    <div class="container">
        <div class="section-header fade-in">
            <h2>Menu Terbaru</h2>
            <p>Cobain menu-menu terbaru dari dapur kami!</p>
        </div>
        <div class="grid-4">
            @foreach($products as $product)
                <div class="product-card fade-in">
                    <a href="{{ route('products.show', $product->slug) }}">
                        <div class="product-image">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                {{ $product->category->icon ?? '' }}
                            @endif
                        </div>
                    </a>
                    <div class="product-body">
                        <span class="product-category">{{ $product->category->icon ?? '' }} {{ $product->category->name ?? '' }}</span>
                        <h3 class="product-name">
                            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                        </h3>
                        <p class="product-desc">{{ $product->description }}</p>
                        <div class="product-footer">
                            <span class="product-price">{{ $product->formatted_price }}</span>
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary btn-sm" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    {{ $product->stock > 0 ? '' : 'Habis' }}
                                    @if($product->stock > 0)<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;"><path d="M11 9h2V6h3V4h-3V1h-2v3H8v2h3v3zm-4 9c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2zm-9.83-3.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25z"/></svg>@endif
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Lihat Semua Produk</a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Kenapa Syamama Kitchen?</h2>
            <p>Alasan kenapa kamu harus cobain produk kami!</p>
        </div>
        <div class="grid-3">
            <div class="card" style="padding:2rem; text-align:center;">
                <div style="font-size:2.5rem; margin-bottom:1rem; color:var(--primary);">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:48px;height:48px;"><path d="M12 3L4 9v12h16V9l-8-6zm0 2.236L18 9.708V19H6V9.708L12 5.236zM8 13h8v2H8v-2z"/></svg>
                </div>
                <h3 style="margin-bottom:0.5rem;">100% Homemade</h3>
                <p class="text-light">Semua dibuat fresh dari dapur rumah, tanpa pengawet berbahaya!</p>
            </div>
            <div class="card" style="padding:2rem; text-align:center;">
                <div style="font-size:2.5rem; margin-bottom:1rem; color:var(--secondary);">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:48px;height:48px;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.94s4.18 1.36 4.18 3.85c0 1.89-1.44 2.98-3.12 3.19z"/></svg>
                </div>
                <h3 style="margin-bottom:0.5rem;">Harga Bersahabat</h3>
                <p class="text-light">Kualitas premium tapi harga tetap terjangkau. Nggak bikin kantong bolong!</p>
            </div>
            <div class="card" style="padding:2rem; text-align:center;">
                <div style="font-size:2.5rem; margin-bottom:1rem; color:var(--primary);">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" style="width:48px;height:48px;"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.3-5.1-3.7-10.6-6.5z"/></svg>
                </div>
                <h3 style="margin-bottom:0.5rem;">Order Gampang</h3>
                <p class="text-light">Pilih, checkout, langsung chat WhatsApp. Sesimpel itu!</p>
            </div>
        </div>
    </div>
</section>
</div><!-- /content-overlay -->
@endsection
