@extends('layouts.app')
@section('title', 'Produk - Syamama Kitchen 🍰')

@section('content')
<section class="section" style="padding-top:2rem;">
    <div class="container">
        <div class="section-header">
            <h2>Menu Kami 🛍️</h2>
            <p>Temukan menu favoritmu di sini!</p>
        </div>

        <!-- Category Filter -->
        <div class="category-pills" style="justify-content:center; margin-bottom:2rem;">
            <a href="{{ route('products.index') }}" class="category-pill {{ !request('category') ? 'active' : '' }}">🏷️ Semua</a>
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug, 'search' => request('search'), 'sort' => request('sort')]) }}"
                   class="category-pill {{ request('category') == $category->slug ? 'active' : '' }}">
                    {{ $category->icon }} {{ $category->name }} ({{ $category->products_count }})
                </a>
            @endforeach
        </div>

        <!-- Search & Sort -->
        <div class="flex items-center justify-between gap-2 mb-3" style="flex-wrap:wrap;">
            <form action="{{ route('products.index') }}" method="GET" class="flex gap-1" style="flex:1; min-width:250px;">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Cari produk..." class="form-control" style="max-width:350px;">
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
                                <div>
                                    <span class="product-price">{{ $product->formatted_price }}</span>
                                    <br>
                                    <span class="product-stock {{ $product->stock <= 5 ? 'stock-low' : '' }}">
                                        {{ $product->stock > 0 ? 'Stok: ' . $product->stock : '❌ Habis' }}
                                    </span>
                                </div>
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary btn-sm" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                        {{ $product->stock > 0 ? '🛒 +' : '😢' }}
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
                <div class="emoji">🔍</div>
                <h3>Produk tidak ditemukan</h3>
                <p>Coba ubah kata kunci atau filter pencarian kamu.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Lihat Semua Produk</a>
            </div>
        @endif
    </div>
</section>
@endsection
