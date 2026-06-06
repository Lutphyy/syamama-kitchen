@extends('layouts.app')
@section('title', 'Produk - Syamama Kitchen')

@section('content')
<section class="section" style="padding-top:2rem;">
    <div class="container">
        <div class="section-header">
            <h2 class="showcase-title">
                <span style="color:#047FD5;">Our</span>
                <span style="color:#FA7302;">Menu</span>
            </h2>
            <p class="showcase-desc">Temukan menu favoritmu di sini!</p>
        </div>

        <!-- Category Filter -->
        <div class="category-pills" style="justify-content:center; margin-bottom:2rem;">
            <a href="{{ route('products.index') }}" class="category-pill {{ !request('category') ? 'active' : '' }}">Semua</a>
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug, 'search' => request('search'), 'sort' => request('sort')]) }}"
                   class="category-pill {{ request('category') == $category->slug ? 'active' : '' }}">
                    {{ $category->name }} ({{ $category->products_count }})
                </a>
            @endforeach
        </div>

        <!-- Search & Sort -->
        <div class="flex items-center justify-between gap-2 mb-3" style="flex-wrap:wrap;">
            <form action="{{ route('products.index') }}" method="GET" class="flex gap-1" style="flex:1; min-width:250px;">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="form-control" style="max-width:350px;">
                <button type="submit" class="btn btn-primary btn-sm">Cari</button>
            </form>
            <div class="flex gap-1 items-center">
                <span class="text-sm text-light font-bold">Urutkan:</span>
                <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'newest'])) }}"
                   class="btn btn-sm {{ request('sort', 'newest') == 'newest' ? 'btn-primary' : 'btn-outline' }}">Terbaru</a>
                <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price_low'])) }}"
                   class="btn btn-sm {{ request('sort') == 'price_low' ? 'btn-primary' : 'btn-outline' }}">Termurah</a>
                <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price_high'])) }}"
                   class="btn btn-sm {{ request('sort') == 'price_high' ? 'btn-primary' : 'btn-outline' }}">Termahal</a>
            </div>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="grid-4">
                @foreach($products as $product)
                    <div class="product-card fade-in {{ $product->stock <= 0 ? 'product-out-of-stock' : '' }}">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <div class="product-image">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                @else
                                    <div style="background:var(--bg-warm);height:100%;display:flex;align-items:center;justify-content:center;color:var(--text-light);font-size:0.8rem;">No Image</div>
                                @endif
                            </div>
                        </a>
                        <div class="product-body">
                            <span class="product-category" style="background:#047FD5;color:white;">{{ $product->category->name ?? '' }}</span>
                            <h3 class="product-name">
                                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                            </h3>
                            <p class="product-desc">{{ $product->description }}</p>
                            <div class="product-footer">
                                <div>
                                    <span class="product-price">{{ $product->formatted_price }}</span>
                                    <br>
                                    <span class="text-sm text-light">
                                        Stok: {{ $product->stock > 0 ? $product->stock : 'Habis' }}
                                    </span>
                                </div>
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary btn-sm" {{ $product->stock <= 0 ? 'disabled' : '' }} style="{{ $product->stock <= 0 ? 'background:#999;cursor:not-allowed;' : '' }}">
                                        @if($product->stock > 0)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;"><path d="M11 9h2V6h3V4h-3V1h-2v3H8v2h3v3zm-4 9c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2zm-9.83-3.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25z"/></svg>
                                        @else
                                            Habis
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrap">
                {{ $products->withQueryString()->links() }}
            </div>
        @else
            <div class="empty-state">
                <div style="font-size:3rem; margin-bottom:1rem; color:var(--text-light);">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:64px;height:64px;"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                </div>
                <h3>Produk tidak ditemukan</h3>
                <p>Coba ubah kata kunci atau filter pencarian kamu.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Lihat Semua Produk</a>
            </div>
        @endif
    </div>
</section>
@endsection
