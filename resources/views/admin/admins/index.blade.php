@extends('admin.layouts.app')
@section('title', 'Kelola Admin')

@section('content')
<div class="admin-header">
    <h1>Kelola Admin</h1>
    <button class="btn btn-primary" onclick="openModal('addAdminModal')">+ Tambah Admin</button>
</div>

<div class="card">
    <h3 style="margin-bottom:1rem;">Daftar Admin</h3>
    <p class="text-light text-sm mb-3">Admin dapat login dan mengelola toko. Berhati-hatilah saat menambah atau menghapus admin.</p>

    <div class="table-wrap desktop-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Ditambahkan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr>
                        <td>
                            <div class="flex items-center gap-1">
                                <div style="width:40px;height:40px;border-radius:50%;background:var(--primary);color:white;display:flex;align-items:center;justify-content:center;font-weight:bold;font-size:1.1rem;">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold">{{ $admin->name }}</div>
                                    @if($admin->id == auth()->id())
                                        <span class="badge badge-pending" style="font-size:0.7rem;">Anda</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->phone }}</td>
                        <td class="text-sm text-light">{{ $admin->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($admin->id != auth()->id() && $admins->count() > 1)
                                <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Yakin hapus admin ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            @else
                                <span class="text-sm text-light">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-light" style="padding:2rem;">Belum ada admin</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="mobile-cards" style="grid-template-columns:1fr;">
        @forelse($admins as $admin)
            <div class="mobile-card">
                <div class="mobile-card-header" style="flex-direction:row;gap:0.75rem;align-items:center;">
                    <div style="width:50px;height:50px;border-radius:50%;background:var(--primary);color:white;display:flex;align-items:center;justify-content:center;font-weight:bold;font-size:1.3rem;flex-shrink:0;">
                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                    </div>
                    <div style="flex:1;">
                        <h4 style="margin:0 0 0.2rem 0;font-size:0.95rem;">{{ $admin->name }}</h4>
                        @if($admin->id == auth()->id())
                            <span class="badge badge-pending" style="font-size:0.65rem;">Anda</span>
                        @endif
                    </div>
                </div>
                <div class="mobile-card-body">
                    <div class="mobile-card-row">
                        <span class="text-light" style="font-size:0.75rem;">Email:</span>
                        <span style="font-size:0.75rem;">{{ Str::limit($admin->email, 20) }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="text-light" style="font-size:0.75rem;">Telepon:</span>
                        <span style="font-size:0.75rem;">{{ $admin->phone }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="text-light" style="font-size:0.75rem;">Ditambahkan:</span>
                        <span style="font-size:0.75rem;">{{ $admin->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
                @if($admin->id != auth()->id() && $admins->count() > 1)
                    <div class="mobile-card-actions">
                        <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Yakin hapus admin ini?');" style="width:100%;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" style="width:100%;">Hapus Admin</button>
                        </form>
                    </div>
                @else
                    <div class="text-center text-sm text-light" style="padding:0.5rem;">Tidak dapat dihapus</div>
                @endif
            </div>
        @empty
            <div style="text-align:center;padding:2rem;" class="text-light">Belum ada admin</div>
        @endforelse
    </div>
</div>

<!-- Info Card -->
<div class="card mt-3" style="background:var(--bg-warm); border-left:4px solid var(--secondary);">
    <h4 style="margin-bottom:0.5rem; display:flex; align-items:center; gap:0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:20px;height:20px;color:var(--secondary);"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
        Informasi Penting
    </h4>
    <ul style="margin:0.5rem 0 0 1.2rem; line-height:1.8; color:var(--text-light);">
        <li>Admin dapat mengelola produk, kategori, pesanan, dan admin lainnya</li>
        <li>Simpan email dan password admin baru di tempat yang aman</li>
        <li>Minimal harus ada 1 admin aktif di sistem</li>
        <li>Tidak dapat menghapus akun admin sendiri</li>
    </ul>
</div>

<!-- Add Admin Modal -->
<div class="modal-overlay" id="addAdminModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Tambah Admin Baru</h3>
            <button class="modal-close" onclick="closeModal('addAdminModal')">✕</button>
        </div>
        <form action="{{ route('admin.admins.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="name" class="form-control" placeholder="contoh: Budi Santoso" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" placeholder="contoh: budi@syamama.com" required>
                    <p class="text-sm text-light mt-1">Email ini akan digunakan untuk login</p>
                </div>
                <div class="form-group">
                    <label class="form-label">No. Telepon/WhatsApp *</label>
                    <input type="text" name="phone" class="form-control" placeholder="contoh: 081234567890" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password *</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang password" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('addAdminModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Admin</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) overlay.classList.remove('active');
    });
});
</script>
@endsection
