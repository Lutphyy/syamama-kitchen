# 🚀 Deployment Guide - Syamama Kitchen ke Hostinger

## ✅ PRE-DEPLOYMENT CHECKLIST

### Status Web (Local):
- ✅ Database: MySQL (syamama_kitchen)
- ✅ Fitur showcase: Done
- ✅ Bug dashboard filter: Fixed
- ✅ Responsive mobile: Done
- ✅ Cart validation: Fixed
- ⚠️ Perlu test akhir sebelum deploy

---

## 📋 STEP-BY-STEP DEPLOYMENT

### **PHASE 1: PERSIAPAN FILE**

#### 1.1. Export Database
```bash
# Via phpMyAdmin:
# 1. Buka http://localhost/phpmyadmin
# 2. Klik database "syamama_kitchen"
# 3. Tab "Export"
# 4. Format: SQL
# 5. Download file (syamama_kitchen.sql)
```

#### 1.2. Optimize Laravel untuk Production
```bash
cd C:\Users\NITRO\Documents\Kuliah\Semester6\IMK\syamama-kitchen

# Clear all cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Generate production cache (JANGAN DULU - tunggu di server)
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache
```

#### 1.3. Update .env untuk Production
Buat file baru: `.env.production`
```env
APP_NAME="Syamama Kitchen"
APP_ENV=production
APP_KEY=base64:dZ1TKFq9no26mDx+KKPNr0W8qLSWaUfLzsfeH9fWDc8=
APP_DEBUG=false
APP_URL=https://syamama.shop

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

WHATSAPP_NUMBER=6285167453912

APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456789_syamama
DB_USERNAME=u123456789_syamama
DB_PASSWORD=GANTI_DENGAN_PASSWORD_DATABASE_HOSTINGER

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.syamama.shop

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database

CACHE_STORE=file
CACHE_PREFIX=syamama

MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@syamama.shop"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

#### 1.4. Compress Project
```bash
# JANGAN include:
# - /node_modules
# - /vendor (akan composer install di server)
# - /.env (buat baru di server)
# - /storage/logs/*.log
# - /bootstrap/cache/*

# Yang HARUS include:
# - Semua folder app, config, database, public, resources, routes
# - composer.json & composer.lock
# - .env.example
# - artisan
```

**Manual ZIP atau via command:**
```bash
# Buat .gitignore dulu jika belum ada
echo "node_modules/" > .deployignore
echo "vendor/" >> .deployignore
echo ".env" >> .deployignore
echo "storage/logs/*.log" >> .deployignore

# Zip via Windows Explorer:
# Klik kanan folder project → Send to → Compressed (zipped) folder
```

---

### **PHASE 2: SETUP HOSTINGER**

#### 2.1. Login ke Hostinger Panel
1. Login: https://hpanel.hostinger.com
2. Pilih hosting "syamama.shop"
3. Buka **File Manager**

#### 2.2. Upload Files
```
1. File Manager → public_html
2. Delete semua file default (index.html, dll)
3. Upload syamama-kitchen.zip
4. Klik kanan → Extract
5. Semua file harus di root public_html, bukan di folder
```

**Struktur harus seperti ini:**
```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── artisan
├── composer.json
└── ...
```

#### 2.3. Setup Database
```
1. hPanel → Databases → MySQL Databases
2. Create New Database:
   - Database Name: syamama_kitchen
   - Username: (auto generate atau buat sendiri)
   - Password: (SIMPAN PASSWORD INI!)
3. Import Database:
   - phpMyAdmin → Import
   - Choose File: syamama_kitchen.sql
   - Click Go
```

#### 2.4. Create .env File
```
1. File Manager → public_html
2. New File → .env
3. Copy paste isi .env.production
4. Update credentials database:
   DB_DATABASE=u123456_syamama_kitchen (sesuai yang dibuat)
   DB_USERNAME=u123456_syamama (sesuai yang dibuat)
   DB_PASSWORD=PASSWORD_DATABASE_HOSTINGER
```

---

### **PHASE 3: SETUP COMPOSER & DEPENDENCIES**

#### 3.1. SSH Terminal
```
1. hPanel → Advanced → SSH Access
2. Enable SSH (jika belum)
3. Buka Terminal (PuTTY atau Windows Terminal)

# Connect via SSH:
ssh u123456@syamama.shop -p 65002
# Password: (hosting password)
```

#### 3.2. Install Dependencies
```bash
# Masuk ke folder
cd public_html

# Check PHP version
php -v
# Harus PHP 8.1+

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Generate Application Key (jika belum ada di .env)
php artisan key:generate

# Run migrations
php artisan migrate --force

# ⚠️ STORAGE LINK - Hostinger disable exec(), jadi php artisan storage:link TIDAK BISA!
# Gunakan cara MANUAL via File Manager atau SSH:

# CARA 1 - Via File Manager (RECOMMENDED untuk pemula):
# 1. Buka File Manager → public_html/public
# 2. Klik "Create Folder" → buat folder bernama "storage"
# 3. Klik kanan folder "storage" → Delete (hapus dulu)
# 4. Sekarang buat Symbolic Link:
#    - Jika ada opsi "Create Symlink", gunakan:
#      Target: ../storage/app/public
#      Link name: storage
# 5. Jika tidak ada opsi symlink, gunakan CARA 2 (SSH)

# CARA 2 - Via SSH (jika CARA 1 tidak ada opsi symlink):
cd public_html/public
ln -s ../storage/app/public storage

# Verify storage link berhasil
ls -la public/storage
# Harus muncul: storage -> ../storage/app/public

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 755 public

# Cache production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### **PHASE 4: DOMAIN CONFIGURATION**

#### 4.1. Fix "Forbidden" Error - Point Domain ke Public Folder
```
⚠️ ERROR "Forbidden" muncul karena domain syamama.shop point ke root public_html,
   padahal Laravel harus diakses via folder /public

Ada 3 SOLUSI (pilih salah satu):
```

**SOLUSI 1 - Change Document Root (RECOMMENDED - paling mudah):**
```
1. Login hPanel → syamama.shop
2. Klik "Advanced" atau "Settings"
3. Cari "Document Root" atau "Web Root"
4. Ubah dari: /public_html
   Menjadi: /public_html/public
5. Save
6. Wait 1-2 minutes, refresh website
```

**SOLUSI 2 - Create .htaccess di Root (jika Document Root tidak bisa diubah):**
```
1. File Manager → public_html (root folder)
2. Create New File: .htaccess
3. Paste code ini:
```

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

```
4. Save
5. Refresh website
```

**SOLUSI 3 - Pindah semua file public/ ke root (NOT RECOMMENDED - kurang aman):**
```
1. File Manager → public_html/public
2. Select All files dalam folder public/
3. Move ke: /public_html (root)
4. Edit index.php di root:
   - Cari: __DIR__.'/../vendor/autoload.php'
   - Ganti: __DIR__.'/vendor/autoload.php'
   - Cari: __DIR__.'/../bootstrap/app.php'
   - Ganti: __DIR__.'/bootstrap/app.php'
5. Refresh website
```

**Verify:**
```
Buka: https://syamama.shop
Harus muncul homepage, bukan "Forbidden" atau listing files
```

#### 4.2. Setup SSL (HTTPS)
```
1. hPanel → Security → SSL
2. Pilih "Free SSL Certificate"
3. Enable untuk syamama.shop
4. Wait 5-10 minutes
```

---

### **PHASE 5: TESTING**

#### 5.1. Test Homepage
```
https://syamama.shop
```
**Check:**
- [ ] Homepage load tanpa error
- [ ] CSS & gambar muncul
- [ ] Hero section tampil
- [ ] Product showcase tampil (jika ada)

#### 5.2. Test Customer Flow
```
- [ ] Browse produk
- [ ] Filter kategori
- [ ] Search produk
- [ ] Detail produk
- [ ] Add to cart
- [ ] View cart
- [ ] Checkout WhatsApp
```

#### 5.3. Test Admin Panel
```
https://syamama.shop/admin

Login:
- Email: admin@syamama.com
- Password: password

Test:
- [ ] Dashboard load
- [ ] Stats tampil
- [ ] Filter periode (7 hari, 30 hari, 3 bulan, 1 tahun)
- [ ] CRUD Produk
- [ ] CRUD Kategori
- [ ] Kelola Pesanan
- [ ] Kelola Admin
- [ ] Edit produk → centang showcase
```

#### 5.4. Test Mobile
```
- [ ] Buka di HP
- [ ] Responsive layout
- [ ] Cart badge
- [ ] Product cards
- [ ] Admin panel mobile
```

---

### **PHASE 6: POST-DEPLOYMENT**

#### 6.1. Security Checklist
```
- [ ] APP_DEBUG=false
- [ ] .env tidak accessible via browser
- [ ] Database password kuat
- [ ] HTTPS aktif (SSL)
- [ ] storage/ & bootstrap/cache/ writable
```

#### 6.2. Performance Optimization
```bash
# Via SSH:
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize images (manual)
# Compress gambar produk sebelum upload
```

#### 6.3. Backup Setup
```
1. hPanel → Backups
2. Enable Weekly Backup
3. Manual backup database:
   phpMyAdmin → Export → Save
```

---

## 🐛 TROUBLESHOOTING

### ❌ Error: "Call to undefined function exec()" saat storage:link
```
PROBLEM: Hostinger disable fungsi exec() untuk keamanan
SOLUTION: Gunakan manual symlink (lihat PHASE 3.2)

Via SSH:
cd public_html/public
ln -s ../storage/app/public storage

Via File Manager:
1. public_html/public → Create Symlink
2. Target: ../storage/app/public
3. Name: storage
```

### ❌ Error: "Forbidden" - You don't have permission to access
```
PROBLEM: Domain point ke root, bukan ke /public folder
SOLUTION: Change Document Root (lihat PHASE 4.1)

Quick Fix:
1. hPanel → Settings → Document Root
2. Ubah: /public_html/public
3. Save & refresh

Alternative: Buat .htaccess di root (lihat PHASE 4.1 - SOLUSI 2)
```

### Error 500 - Internal Server Error
```bash
# Check logs
tail -f storage/logs/laravel.log

# Fix permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Clear cache
php artisan config:clear
php artisan cache:clear
```

### CSS/JS Not Loading
```
1. Check .env: APP_URL=https://syamama.shop
2. Hard refresh: Ctrl+Shift+R
3. Check storage link: php artisan storage:link
```

### Images Not Showing
```bash
# Recreate storage link
php artisan storage:link

# Check permissions
chmod -R 755 storage/app/public

# Check .env
FILESYSTEM_DISK=public
```

### Database Connection Error
```
1. Check .env database credentials
2. Test connection via phpMyAdmin
3. Verify database name, user, password
```

### Migration Error
```bash
# Reset migrations (HATI-HATI: hapus data!)
php artisan migrate:fresh --force

# Or import SQL manual via phpMyAdmin
```

---

## 📝 MAINTENANCE

### Update Website
```bash
# Via SSH:
cd public_html

# Pull changes (jika pakai Git)
git pull origin main

# Update dependencies
composer install --no-dev

# Run migrations
php artisan migrate --force

# Clear & cache
php artisan config:clear
php artisan config:cache
php artisan view:clear
```

### Backup Regular
```bash
# Backup database
mysqldump -u USER -p DATABASE > backup.sql

# Backup files
tar -czf backup.tar.gz public_html/
```

---

## 🆘 EMERGENCY CONTACTS

**Hostinger Support:**
- Live Chat: hpanel.hostinger.com
- Email: support@hostinger.com

**Quick Commands:**
```bash
# Clear everything
php artisan optimize:clear

# Check PHP version
php -v

# Check Laravel version
php artisan --version

# Check routes
php artisan route:list

# Check config
php artisan config:show database
```

---

## ✅ FINAL CHECKLIST BEFORE GO LIVE

- [ ] Database exported & imported
- [ ] .env configured correctly
- [ ] Composer dependencies installed
- [ ] Storage linked
- [ ] Permissions set (755)
- [ ] SSL enabled (HTTPS)
- [ ] Domain pointing to /public
- [ ] APP_DEBUG=false
- [ ] All features tested
- [ ] Mobile responsive tested
- [ ] WhatsApp checkout tested
- [ ] Admin panel tested
- [ ] Backup created

---

**Status:** Ready to Deploy! 🚀
**Domain:** https://syamama.shop
**Last Check:** June 8, 2026
