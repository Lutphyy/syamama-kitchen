@extends('admin.layouts.app')
@section('title', 'Kelola Produk')

@section('content')
<div class="admin-header">
    <h1>📦 Kelola Produk</h1>
    <button class="btn btn-primary" onclick="openModal('addProductModal')">➕ Tambah Produk</button>
</div>

<!-- Search -->
<div class="flex items-center gap-2 mb-3" style="flex-wrap:wrap;">
    <form action="{{ route('admin.products.index') }}" method="GET" class="flex gap-1" style="flex:1;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Cari produk..." class="form-control" style="max-width:300px;">
        <select name="category" class="form-control" style="max-width:200px;">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Cari</button>
    </form>
</div>

<!-- Products Table -->
<div class="table-wrap">
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>
                        <div class="flex items-center gap-1">
                            <div style="width:45px;height:45px;border-radius:8px;background:var(--bg-warm);display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;overflow:hidden;">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    {{ $product->category->icon ?? '🍰' }}
                                @endif
                            </div>
                            <div>
                                <div class="font-bold">{{ $product->name }}</div>
                                <div class="text-sm text-light">{{ Str::limit($product->description, 40) }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-processing">{{ $product->category->icon ?? '' }} {{ $product->category->name ?? '-' }}</span></td>
                    <td class="font-bold text-primary">{{ $product->formatted_price }}</td>
                    <td>
                        <span class="{{ $product->stock <= 5 ? 'text-danger font-bold' : '' }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td>
                        @if($product->is_active)
                            <span class="badge badge-completed">✅ Aktif</span>
                        @else
                            <span class="badge badge-cancelled">❌ Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex gap-1">
                            <button class="btn btn-sm btn-secondary" onclick="editProduct({{ json_encode($product) }})">✏️</button>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-light" style="padding:2rem;">Belum ada produk 📭</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $products->withQueryString()->links() }}
</div>

<!-- Add Product Modal -->
<div class="modal-overlay" id="addProductModal">
    <div class="modal">
        <div class="modal-header">
            <h3>➕ Tambah Produk Baru</h3>
            <button class="modal-close" onclick="closeModal('addProductModal')">✕</button>
        </div>
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Produk *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select name="category_id" class="form-control" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi *</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="form-group">
                        <label class="form-label">Harga (Rp) *</label>
                        <input type="number" name="price" class="form-control" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stok *</label>
                        <input type="number" name="stock" class="form-control" min="0" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Gambar Produk</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:0.5rem;">
                    <input type="checkbox" name="is_active" value="1" checked id="is_active_add" style="accent-color:var(--primary);">
                    <label for="is_active_add">Aktifkan produk</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('addProductModal')">Batal</button>
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal-overlay" id="editProductModal">
    <div class="modal">
        <div class="modal-header">
            <h3>✏️ Edit Produk</h3>
            <button class="modal-close" onclick="closeModal('editProductModal')">✕</button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Produk *</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select name="category_id" id="edit_category" class="form-control" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi *</label>
                    <textarea name="description" id="edit_description" class="form-control" required></textarea>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="form-group">
                        <label class="form-label">Harga (Rp) *</label>
                        <input type="number" name="price" id="edit_price" class="form-control" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stok *</label>
                        <input type="number" name="stock" id="edit_stock" class="form-control" min="0" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Gambar Produk (kosongkan jika tidak diubah)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:0.5rem;">
                    <input type="checkbox" name="is_active" value="1" id="edit_is_active" style="accent-color:var(--primary);">
                    <label for="edit_is_active">Aktifkan produk</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('editProductModal')">Batal</button>
                <button type="submit" class="btn btn-primary">💾 Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

function editProduct(product) {
    document.getElementById('editForm').action = '/admin/products/' + product.id;
    document.getElementById('edit_name').value = product.name;
    document.getElementById('edit_category').value = product.category_id;
    document.getElementById('edit_description').value = product.description;
    document.getElementById('edit_price').value = product.price;
    document.getElementById('edit_stock').value = product.stock;
    document.getElementById('edit_is_active').checked = product.is_active;
    openModal('editProductModal');
}

// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) overlay.classList.remove('active');
    });
});
</script>
@endsection
