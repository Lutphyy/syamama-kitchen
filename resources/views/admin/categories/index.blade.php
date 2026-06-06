@extends('admin.layouts.app')
@section('title', 'Kelola Kategori')

@section('content')
<div class="admin-header">
    <h1>Kelola Kategori</h1>
    <button class="btn btn-primary" onclick="openModal('addCategoryModal')">+ Tambah Kategori</button>
</div>

<!-- Categories Grid -->
<div class="grid-3 desktop-grid">
    @forelse($categories as $category)
        <div class="card fade-in" style="padding:1.5rem;">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-1">
                    <div>
                        <h3 style="font-size:1.1rem;">{{ $category->name }}</h3>
                        <span class="text-sm text-light">{{ $category->products_count }} produk</span>
                    </div>
                </div>
                <div class="flex gap-1">
                    <button class="btn btn-sm btn-secondary" onclick="editCategory({{ json_encode($category) }})">Edit</button>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin hapus kategori ini? Semua produk di dalamnya juga akan terhapus!');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
            @if($category->description)
                <p class="text-sm text-light">{{ $category->description }}</p>
            @endif
            <div class="mt-1">
                <span class="badge badge-processing">slug: {{ $category->slug }}</span>
            </div>
        </div>
    @empty
        <div class="empty-state" style="grid-column:1/-1;">
            <div style="font-size:3rem; margin-bottom:1rem; color:var(--text-light);">—</div>
            <h3>Belum ada kategori</h3>
            <p>Buat kategori pertama untuk mengorganisir produk kamu.</p>
        </div>
    @endforelse
</div>

<!-- Mobile Cards for Categories -->
<div class="mobile-cards">
    @forelse($categories as $category)
        <div class="mobile-card">
            <div class="mobile-card-body">
                <h4 style="margin:0 0 0.3rem 0;font-size:0.9rem;">{{ $category->name }}</h4>
                <span class="text-sm text-light">{{ $category->products_count }} produk</span>
                @if($category->description)
                    <p class="text-sm text-light" style="margin:0.5rem 0;line-height:1.4;font-size:0.75rem;">{{ Str::limit($category->description, 50) }}</p>
                @endif
                <span class="badge badge-processing" style="font-size:0.65rem;margin-top:0.3rem;">{{ $category->slug }}</span>
            </div>
            <div class="mobile-card-actions">
                <button class="btn btn-sm btn-secondary" onclick="editCategory({{ json_encode($category) }})" style="flex:1;">Edit</button>
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');" style="flex:1;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" style="width:100%;">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <div style="grid-column:1/-1;text-align:center;padding:2rem;" class="text-light">Belum ada kategori</div>
    @endforelse
</div>

<!-- Add Modal -->
<div class="modal-overlay" id="addCategoryModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Tambah Kategori</h3>
            <button class="modal-close" onclick="closeModal('addCategoryModal')">✕</button>
        </div>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Kategori *</label>
                    <input type="text" name="name" class="form-control" placeholder="contoh: Kue Kering" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" placeholder="Deskripsi singkat kategori" rows="3"></textarea>
                </div>
                <input type="hidden" name="icon" value="📦">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('addCategoryModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editCategoryModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Edit Kategori</h3>
            <button class="modal-close" onclick="closeModal('editCategoryModal')">✕</button>
        </div>
        <form id="editCatForm" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Kategori *</label>
                    <input type="text" name="name" id="editcat_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" id="editcat_description" class="form-control" rows="3"></textarea>
                </div>
                <input type="hidden" name="icon" id="editcat_icon" value="📦">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('editCategoryModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

function editCategory(cat) {
    document.getElementById('editCatForm').action = '/admin/categories/' + cat.id;
    document.getElementById('editcat_name').value = cat.name;
    document.getElementById('editcat_icon').value = cat.icon;
    document.getElementById('editcat_description').value = cat.description || '';
    openModal('editCategoryModal');
}

document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) overlay.classList.remove('active');
    });
});
</script>
@endsection
