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
                    <div style="background:var(--bg-warm);height:300px;display:flex;align-items:center;justify-content:center;color:var(--text-light);border-radius:12px;">No Image</div>
                @endif
            </div>

            <!-- Info -->
            <div class="detail-info">
                <span class="product-category" style="font-size:0.9rem; padding:0.4rem 0.8rem; background:#047FD5; color:white;">
                    {{ $product->category->name ?? '' }}
                </span>
                <h1 style="margin-top:0.8rem;">{{ $product->name }}</h1>
                <div class="detail-price">{{ $product->formatted_price }}</div>

                <div class="detail-meta">
                    <div class="meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px;color:var(--text-medium);"><path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
                        <span class="{{ $product->stock <= 5 ? 'text-danger font-bold' : '' }}">
                            Stok: {{ $product->stock > 0 ? $product->stock . ' tersedia' : 'Habis' }}
                        </span>
                    </div>
                    <div class="meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px;color:var(--text-medium);"><path d="M17.63 5.84C17.27 5.33 16.67 5 16 5L5 5.01C3.9 5.01 3 5.9 3 7v10c0 1.1.9 1.99 2 1.99L16 19c.67 0 1.27-.33 1.63-.84L22 12l-4.37-6.16z"/></svg>
                        <span>{{ $product->category->name ?? '-' }}</span>
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
                        <button type="submit" class="btn btn-primary btn-lg">Tambah ke Keranjang</button>
                    </form>
                @else
                    <div class="alert alert-warning">Maaf, produk ini sedang habis. Coba lagi nanti ya!</div>
                @endif

                <!-- WhatsApp Direct -->
                <div class="mt-3">
                    <a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}?text={{ urlencode('Halo Syamama Kitchen! Saya mau tanya tentang ' . $product->name) }}"
                       class="btn btn-whatsapp" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="width:18px;height:18px;fill:currentColor;"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.3-5.1-3.7-10.6-6.5z"/></svg>
                        Tanya via WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($related->count() > 0)
            <div class="section" style="padding-bottom:0;">
                <div class="section-header">
                    <h2>Produk Serupa</h2>
                </div>
                <div class="grid-4">
                    @foreach($related as $relProduct)
                        <div class="product-card fade-in">
                            <a href="{{ route('products.show', $relProduct->slug) }}">
                                <div class="product-image">
                                    @if($relProduct->image)
                                        <img src="{{ asset('storage/' . $relProduct->image) }}" alt="{{ $relProduct->name }}">
                                    @else
                                        <div style="background:var(--bg-warm);height:100%;display:flex;align-items:center;justify-content:center;color:var(--text-light);font-size:0.8rem;">No Image</div>
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
                                        <button type="submit" class="btn btn-primary btn-sm"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;"><path d="M11 9h2V6h3V4h-3V1h-2v3H8v2h3v3zm-4 9c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2zm-9.83-3.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25z"/></svg></button>
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
