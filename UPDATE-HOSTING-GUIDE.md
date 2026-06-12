# Panduan Update Website ke Hosting

## 📋 Ringkasan Perubahan

### Fitur Baru:
1. ✅ Section **Testimoni** (sebelum FAQ)
2. ✅ Section **FAQ** dengan accordion
3. ✅ Navbar redesign (menu tengah, tombol Belanja Sekarang di kanan)
4. ✅ Export **PDF** di admin panel (format A4)
5. ✅ Auto close mobile menu
6. ✅ Bug fixes (gap footer cart, image detail produk)

### File yang Berubah:
- `resources/views/home.blade.php` (Testimoni, FAQ section)
- `resources/views/layouts/app.blade.php` (Navbar update, auto close menu)
- `resources/views/cart/index.blade.php` (Fix gap footer)
- `public/css/app.css` (CSS baru: testimoni, FAQ, navbar)
- `app/Http/Controllers/Admin/OrderController.php` (Method exportPdf)
- `resources/views/admin/orders/pdf.blade.php` (NEW FILE - Template PDF)
- `resources/views/admin/orders/index.blade.php` (Tombol Export PDF)
- `resources/views/admin/dashboard.blade.php` (Tombol Export PDF)
- `routes/web.php` (Route exportPdf)

---

## 🚀 CARA 1: Update via File Manager (RECOMMENDED - PALING MUDAH)

### Step 1: Backup Database (PENTING!)
1. Login ke hPanel Hostinger
2. Database → phpMyAdmin
3. Pilih database → Export → Go
4. Simpan file .sql sebagai backup

### Step 2: Backup File Lama
1. Buka File Manager di hPanel
2. Masuk ke folder `public_html`
3. Download folder `resources` dan `public` sebagai backup
4. Atau rename jadi `resources_backup` dan `public_backup`

### Step 3: Upload File yang Berubah

**Upload via File Manager:**

1. **Folder resources/views:**
   - Upload `resources/views/home.blade.php`
   - Upload `resources/views/layouts/app.blade.php`
   - Upload `resources/views/cart/index.blade.php`
   - Upload `resources/views/admin/orders/index.blade.php`
   - Upload `resources/views/admin/orders/pdf.blade.php` **(NEW FILE)**
   - Upload `resources/views/admin/dashboard.blade.php`

2. **Folder public:**
   - Upload `public/css/app.css`

3. **Folder app:**
   - Upload `app/Http/Controllers/Admin/OrderController.php`

4. **Folder routes:**
   - Upload `routes/web.php`

### Step 4: Install Library PDF (PENTING!)

Buka **Terminal SSH** di hPanel Hostinger:

```bash
cd domains/syamama.shop/public_html
composer require barryvdh/laravel-dompdf
```

Tunggu sampai selesai (1-2 menit).

### Step 5: Clear Cache

Masih di Terminal SSH:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Step 6: Test Website
1. Buka https://syamama.shop
2. Test section baru: Testimoni, FAQ
3. Test navbar mobile (klik menu → auto close)
4. Login admin → Test Export PDF di Kelola Pesanan

---

## 🚀 CARA 2: Update via Git (Jika Pakai Git)

### Step 1: Commit Perubahan di Local

```bash
git add .
git commit -m "Add Testimoni, FAQ sections, Export PDF, Navbar redesign"
git push origin main
```

### Step 2: Pull di Server Hosting

Login SSH ke Hostinger:

```bash
cd domains/syamama.shop/public_html
git pull origin main
```

### Step 3: Install Library & Clear Cache

```bash
composer require barryvdh/laravel-dompdf
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---

## 🚀 CARA 3: Re-Upload Semua File (Nuclear Option)

Jika cara 1 & 2 ribet, bisa upload ulang semua:

### Step 1: Backup Database
(Sama seperti Cara 1 Step 1)

### Step 2: Hapus File Lama
1. File Manager → `public_html`
2. Hapus semua KECUALI:
   - `.env`
   - `storage/` folder
   - `database/database.sqlite` (jika pakai SQLite)

### Step 3: Upload Semua File Baru
1. Zip seluruh project Laravel di local (KECUALI `vendor`, `node_modules`)
2. Upload zip ke `public_html`
3. Extract zip

### Step 4: Install Dependencies

Terminal SSH:

```bash
cd domains/syamama.shop/public_html
composer install
composer require barryvdh/laravel-dompdf
php artisan storage:link
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---

## ✅ Checklist Setelah Update

- [ ] Website bisa diakses (https://syamama.shop)
- [ ] Section Testimoni muncul (sebelum FAQ)
- [ ] Section FAQ muncul dengan accordion
- [ ] Navbar: menu di tengah, tombol "Belanja Sekarang" di kanan (desktop)
- [ ] Mobile: menu bisa dibuka/tutup, auto close saat klik
- [ ] Login admin berhasil
- [ ] Admin panel: tombol "Export PDF" muncul (warna merah)
- [ ] Klik Export PDF → download file PDF otomatis
- [ ] PDF format A4 landscape dengan header & table

---

## 🆘 Troubleshooting

### Error "Class 'Barryvdh\DomPDF\Facade\Pdf' not found"
**Solusi:** Library belum terinstall
```bash
composer require barryvdh/laravel-dompdf
php artisan config:clear
```

### Error "Route [admin.orders.exportPdf] not defined"
**Solusi:** Routes belum di-clear
```bash
php artisan route:clear
php artisan route:cache
```

### CSS/Styling Tidak Berubah
**Solusi:** Browser cache
1. Buka website di mode Incognito
2. Atau tekan `Ctrl + Shift + R` (hard refresh)
3. Clear cache Laravel:
```bash
php artisan view:clear
php artisan cache:clear
```

### Section Testimoni/FAQ Tidak Muncul
**Solusi:** View cache
```bash
php artisan view:clear
```

### Export PDF Download Kosong
**Solusi:**
1. Cek file `resources/views/admin/orders/pdf.blade.php` sudah ada
2. Pastikan library terinstall:
```bash
composer show barryvdh/laravel-dompdf
```

---

## 📝 Catatan Penting

1. **SELALU backup database** sebelum update
2. **Jangan hapus folder `storage`** - berisi gambar produk
3. **Jangan hapus file `.env`** - berisi konfigurasi database & whatsapp
4. Setelah update, test di **mode Incognito** untuk pastikan bukan cache browser
5. Jika error, cek file `storage/logs/laravel.log` untuk detail error

---

## 🎉 Selesai!

Website sudah terupdate dengan fitur baru:
- Section Testimoni & FAQ
- Navbar yang lebih modern
- Export PDF untuk laporan pesanan
- Mobile navigation lebih smooth

Selamat! 🚀
