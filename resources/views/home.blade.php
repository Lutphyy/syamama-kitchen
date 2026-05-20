@extends('layouts.app')
@section('title', 'Syamama Kitchen 🍰 - Kue & Makanan Rumahan Lezat')

@section('content')
<!-- Hero -->
<section class="hero">
    <div class="container">
        <div class="hero-content slide-up">
            <h1>Rasa Rumahan,<br>Kualitas Istimewa! 🍰</h1>
            <p>Kue kering, kue basah, makanan & minuman homemade dibuat dengan resep turun-temurun dan bahan pilihan terbaik.</p>
            <div class="hero-actions">
                <a href="{{ route('products.index') }}" class="btn btn-lg" style="background:white;color:var(--primary);font-weight:800;">🛍️ Lihat Menu</a>
                <a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}" class="btn btn-lg btn-whatsapp" target="_blank">📱 Chat WhatsApp</a>
            </div>
        </div>
    </div>
    <div class="hero-float">🍳</div>
</section>

<!-- Categories -->
<section class="section">
    <div class="container">
        <div class="section-header fade-in">
            <h2>Kategori Menu 📋</h2>
            <p>Pilih kategori favoritmu!</p>
            <!-- <div class="emoji-divider">🍪 🍰 🥐 🥤 🍱 🌶️</div> -->
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
            <h2>Menu Terbaru ✨</h2>
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
                                {{ $product->category->icon ?? '🍰' }}
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
                                    {{ $product->stock > 0 ? '🛒 Tambah' : '😢 Habis' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">🛍️ Lihat Semua Produk</a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Kenapa Syamama Kitchen? 🤔</h2>
            <p>Alasan kenapa kamu harus cobain produk kami!</p>
        </div>
        <div class="grid-3">
            <div class="card" style="padding:2rem; text-align:center;">
                <div style="font-size:3rem; margin-bottom:1rem;">🏡</div>
                <h3 style="margin-bottom:0.5rem;">100% Homemade</h3>
                <p class="text-light">Semua dibuat fresh dari dapur rumah, tanpa pengawet berbahaya!</p>
            </div>
            <div class="card" style="padding:2rem; text-align:center;">
                <div style="font-size:3rem; margin-bottom:1rem;">💰</div>
                <h3 style="margin-bottom:0.5rem;">Harga Bersahabat</h3>
                <p class="text-light">Kualitas premium tapi harga tetap terjangkau. Nggak bikin kantong bolong!</p>
            </div>
            <div class="card" style="padding:2rem; text-align:center;">
                <div style="font-size:3rem; margin-bottom:1rem;">📱</div>
                <h3 style="margin-bottom:0.5rem;">Order Gampang</h3>
                <p class="text-light">Pilih, checkout, langsung chat WhatsApp. Sesimpel itu!</p>
            </div>
        </div>
    </div>
</section>
@endsection
