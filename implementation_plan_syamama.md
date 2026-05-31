# Revisi Web Syamama Kitchen

Revisi menyeluruh untuk web Syamama Kitchen: mengurangi emoji, update font & warna, penyederhanaan footer, penggantian logo, dan pengamanan akses admin.

## User Review Required

> [!IMPORTANT]
> **Lokasi Logo Baru**: Logo harus diletakkan di `public/images/logo.png` (atau `.svg`/`.jpg`). Saya akan buat direktori `public/images/` dan referensi logo dari sana. Kamu tinggal taruh file logo-nya di lokasi tersebut.

> [!IMPORTANT]
> **Nomor WhatsApp Footer**: Saat ini nomor WA diambil dari `.env` (`WHATSAPP_NUMBER=6285167453912`). Saya akan pakai nomor yang sama di footer dengan icon WA resmi (SVG). Apakah nomor ini sudah benar?

> [!WARNING]
> **Keamanan Admin**: Saya akan menghilangkan tombol Masuk/Login dan Register dari halaman publik. Akses admin akan melalui URL rahasia `/admin-secret-login` (bisa diganti). Registrasi publik akan dihapus; hanya admin yang bisa mengundang admin baru via artisan command. Jika lupa password, bisa reset via artisan command juga.

## Open Questions

> [!IMPORTANT]
> **URL admin login rahasia**: Saya usulkan `/syamama-panel` sebagai URL rahasia. URL ini tidak terlihat di mana pun di web. Apakah kamu mau URL lain?

## Proposed Changes

### 1. CSS - Font & Color Overhaul

#### [MODIFY] [app.css](file:///c:/Users/NITRO/Documents/Kuliah/Semester6/IMK/syamama-kitchen/public/css/app.css)

- **Font**: Ganti `Nunito` + `Fredoka One` → `Poppins` (judul) + `Open Sans` (body/deskripsi/harga)
- **Warna utama**: `--primary: #FA7302` (orange), tambah `--secondary: #047FD5` (biru), tetap ada putih
- Update semua `font-family` reference di seluruh CSS
- Update semua color variable yang terkait
- Hapus floating emoji background (`.floating-foods`)
- Hapus `hero::before` emoji content
- Ganti `.hero-float` emoji menjadi dekoratif non-emoji (SVG/CSS shape)

---

### 2. Layout - Emoji Cleanup & Footer Simplification

#### [MODIFY] [app.blade.php](file:///c:/Users/NITRO/Documents/Kuliah/Semester6/IMK/syamama-kitchen/resources/views/layouts/app.blade.php)

- **Hapus floating foods** emoji section (`<div class="floating-foods">`)
- **Navbar**: Hapus semua emoji dari nav links (🏠, 🛍️, 🛒), ganti dengan text only
- **Hapus tombol Masuk/Login/Logout** dari navbar publik (seluruh `@auth` / `@else` block)
- **Brand icon**: Ganti emoji 🍳 → logo image dari `public/images/logo.png`
- **Footer**: Sederhanakan hanya:
  - `© 2026 Syamama Kitchen.`
  - Icon WhatsApp (SVG resmi) dengan link ke WA
- **Favicon**: Ganti dari emoji ke logo image

---

### 3. Home Page - Emoji Cleanup

#### [MODIFY] [home.blade.php](file:///c:/Users/NITRO/Documents/Kuliah/Semester6/IMK/syamama-kitchen/resources/views/home.blade.php)

- Hapus emoji dari judul hero (`🥤`), section headers (`📋`, `✨`, `🤔`)
- Hapus emoji dari tombol (`🛍️`, `📱`, `🛒`)
- Hapus emoji dari card icons (`🏡`, `💰`, `📱`) → ganti dengan SVG icon sederhana
- Hapus `hero-float` emoji div
- Ganti emoji WhatsApp button → icon WA SVG resmi

---

### 4. Product Pages - Emoji Cleanup

#### [MODIFY] [index.blade.php](file:///c:/Users/NITRO/Documents/Kuliah/Semester6/IMK/syamama-kitchen/resources/views/products/index.blade.php)

- Hapus emoji dari header (`🛍️`), tombol, dan filter (`🏷️`, `🔍`)
- Ganti emoji keranjang (`🛒`, `😢`) → text biasa / SVG icon

#### [MODIFY] [show.blade.php](file:///c:/Users/NITRO/Documents/Kuliah/Semester6/IMK/syamama-kitchen/resources/views/products/show.blade.php)

- Hapus emoji dari meta items (`📦`, `🏷️`)
- Hapus emoji dari tombol tambah keranjang (`🛒`)
- Ganti emoji WA (`📱`) → icon WA SVG resmi
- Hapus emoji dari related section (`🤩`)

---

### 5. Cart & Checkout - Emoji Cleanup

#### [MODIFY] [cart/index.blade.php](file:///c:/Users/NITRO/Documents/Kuliah/Semester6/IMK/syamama-kitchen/resources/views/cart/index.blade.php)

- Hapus emoji dari header (`🛒`), tombol, summary (`📋`, `📱`, `🛍️`)
- Hapus emoji dari empty state (`🛒`, `🛍️`)
- Ganti emoji delete (`🗑️`) → icon SVG

#### [MODIFY] [checkout.blade.php](file:///c:/Users/NITRO/Documents/Kuliah/Semester6/IMK/syamama-kitchen/resources/views/checkout.blade.php)

- Hapus emoji dari semua label (`📱`, `📝`, `👤`, `📍`, `🛒`, `💛`)
- Ganti emoji WA → icon WA SVG

---

### 6. Admin Security - Hidden Login & Invite System

#### [MODIFY] [web.php](file:///c:/Users/NITRO/Documents/Kuliah/Semester6/IMK/syamama-kitchen/routes/web.php)

- Hapus route `/login` dan `/register` publik
- Tambah route rahasia: `GET /syamama-panel` → menampilkan halaman login admin
- Tambah route: `POST /syamama-panel` → proses login admin
- Tetap pertahankan `POST /logout`

#### [MODIFY] [AuthController.php](file:///c:/Users/NITRO/Documents/Kuliah/Semester6/IMK/syamama-kitchen/app/Http/Controllers/AuthController.php)

- Hapus method `showRegister()` dan `register()`
- Method `showLogin()` → render view khusus admin login (minimalis, tanpa link register)
- Tambah validasi di `login()`: hanya user dengan role `admin` yang bisa login

#### [MODIFY] [login.blade.php](file:///c:/Users/NITRO/Documents/Kuliah/Semester6/IMK/syamama-kitchen/resources/views/auth/login.blade.php)

- Redesign sebagai halaman admin login minimalis
- Hapus link "Daftar di sini"
- Hapus emoji, pakai font baru
- Tampilan bersih, profesional

#### [DELETE] [register.blade.php](file:///c:/Users/NITRO/Documents/Kuliah/Semester6/IMK/syamama-kitchen/resources/views/auth/register.blade.php)

- Hapus file ini karena registrasi publik dihapus

#### [NEW] Artisan Command: `admin:create`

Buat command `php artisan admin:create` untuk membuat akun admin baru. Caranya:
```
php artisan admin:create
> Masukkan nama: Admin Syamama
> Masukkan email: admin@syamama.com
> Masukkan password: ********
> Admin berhasil dibuat!
```

#### [NEW] Artisan Command: `admin:reset-password`

Buat command `php artisan admin:reset-password` untuk reset password admin. Caranya:
```
php artisan admin:reset-password
> Masukkan email admin: admin@syamama.com
> Masukkan password baru: ********
> Password berhasil direset!
```

Ini aman karena hanya orang yang punya akses server/terminal yang bisa jalankan command ini. Tidak bisa diakses dari web.

---

### 7. Admin Panel - Minor Emoji Cleanup

#### [MODIFY] [admin/layouts/app.blade.php](file:///c:/Users/NITRO/Documents/Kuliah/Semester6/IMK/syamama-kitchen/resources/views/admin/layouts/app.blade.php)

- Kurangi emoji di sidebar (tetap beberapa yang wajar karena ini admin area)
- Ganti brand icon emoji → logo image
- Update font reference

#### [MODIFY] [admin/dashboard.blade.php](file:///c:/Users/NITRO/Documents/Kuliah/Semester6/IMK/syamama-kitchen/resources/views/admin/dashboard.blade.php)

- Kurangi emoji di stat cards dan headers
- Update chart colors ke palet baru (#FA7302 dan #047FD5)

---

### 8. Logo Directory Setup

#### [NEW] `public/images/` directory

- Buat direktori `public/images/`
- Tempat untuk meletakkan `logo.png` (atau format lain)
- Sementara akan pakai text-based logo sampai file logo disediakan

---

## Ringkasan Perubahan

| Area | Sebelum | Sesudah |
|------|---------|---------|
| Font judul | Fredoka One / Nunito | **Poppins** (geometric sans-serif) |
| Font body | Nunito | **Open Sans** |
| Warna utama | `#FF8C42` (orange) | `#FA7302` (orange) |
| Warna sekunder | `#FFC93C` (kuning) | `#047FD5` (biru) |
| Emoji | Banyak sekali | Minimal / dihilangkan |
| Logo | Emoji 🍳 | Image file (`public/images/logo.png`) |
| Footer | 3-column dengan banyak info | Hanya © + icon WA |
| Login/Register | Terlihat di navbar | **Tersembunyi** di `/syamama-panel` |
| Buat akun admin | Via registrasi publik | Via `php artisan admin:create` |
| Reset password | Tidak ada | Via `php artisan admin:reset-password` |

## Verification Plan

### Automated Tests
- `php artisan admin:create` → buat akun test → cek login berhasil
- `php artisan admin:reset-password` → reset password → cek login dengan password baru
- Akses `/login` dan `/register` → harus 404
- Akses `/syamama-panel` → harus tampil form login
- Login sebagai non-admin → harus ditolak

### Manual Verification
- Cek tampilan web di browser: font, warna, footer
- Cek navbar tidak ada tombol login
- Cek semua halaman emoji sudah berkurang
- Cek icon WhatsApp di footer pakai SVG resmi
- Cek admin panel masih berfungsi normal
