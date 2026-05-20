@extends('layouts.app')
@section('title', $product->name . ' - Syamama Kitchen')

@section('content')
<section class="section" style="padding-top:2rem;">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="mb-3">
            <a href="{{ route('home') }}" class="text-light">Beranda</a>
            <span class="text-light"> / </span>
            <a href="{{ route('products.index') }}" class="text-light">Produk</a>
            <span class="text-light"> / </span>
            <span class="text-primary font-bold">{{ $product->name }}</span>
        </div>

        <div class="product-detail">
            <!-- Image -->
            <div class="product-gallery">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                @else
                    {{ $product->category->icon ?? '🍰' }}
                @endif
            </div>

            <!-- Info -->
            <div class="detail-info">
                <span class="product-category" style="font-size:0.9rem; padding:0.4rem 0.8rem;">
                    {{ $product->category->icon ?? '' }} {{ $product->category->name ?? '' }}
                </span>
                <h1 style="margin-top:0.8rem;">{{ $product->name }}</h1>
                <div class="detail-price">{{ $product->formatted_price }}</div>

                <div class="detail-meta">
                    <div class="meta-item">
                        📦 <span class="{{ $product->stock <= 5 ? 'text-danger font-bold' : '' }}">
                            Stok: {{ $product->stock > 0 ? $product->stock . ' tersedia' : 'Habis' }}
                        </span>
                    </div>
                    <div class="meta-item">
                        🏷️ <span>{{ $product->category->name ?? '-' }}</span>
                    </div>
                </div>

                <div class="detail-desc">
                    {{ $product->description }}
                </div>

                @if($product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="qty-control">
                            <button type="button" onclick="changeQty(-1)">−</button>
                            <input type="number" name="quantity" id="qty" value="1" min="1" max="{{ $product->stock }}">
                            <button type="button" onclick="changeQty(1)">+</button>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">🛒 Tambah ke Keranjang</button>
                    </form>
                @else
                    <div class="alert alert-warning">😢 Maaf, produk ini sedang habis. Coba lagi nanti ya!</div>
                @endif

                <!-- WhatsApp Direct -->
                <div class="mt-3">
                    <a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}?text={{ urlencode('Halo Syamama Kitchen! Saya mau tanya tentang ' . $product->name) }}"
                       class="btn btn-whatsapp" target="_blank">
                        📱 Tanya via WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($related->count() > 0)
            <div class="section" style="padding-bottom:0;">
                <div class="section-header">
                    <h2>Produk Serupa 🤩</h2>
                </div>
                <div class="grid-4">
                    @foreach($related as $relProduct)
                        <div class="product-card fade-in">
                            <a href="{{ route('products.show', $relProduct->slug) }}">
                                <div class="product-image">
                                    @if($relProduct->image)
                                        <img src="{{ asset('storage/' . $relProduct->image) }}" alt="{{ $relProduct->name }}">
                                    @else
                                        {{ $relProduct->category->icon ?? '🍰' }}
                                    @endif
                                </div>
                            </a>
                            <div class="product-body">
                                <h3 class="product-name">
                                    <a href="{{ route('products.show', $relProduct->slug) }}">{{ $relProduct->name }}</a>
                                </h3>
                                <div class="product-footer">
                                    <span class="product-price">{{ $relProduct->formatted_price }}</span>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $relProduct->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary btn-sm">🛒 +</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
function changeQty(delta) {
    const input = document.getElementById('qty');
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > {{ $product->stock }}) val = {{ $product->stock }};
    input.value = val;
}
</script>
@endsection
