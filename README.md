# Sepatu.id - Platform E-Commerce Toko Sepatu Modern

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

**Sepatu.id** adalah aplikasi toko online (e-commerce) khusus sepatu berbasis web yang dibangun dengan framework **Laravel 13**, **Tailwind CSS**, dan **Alpine.js**. Aplikasi ini dirancang modern, responsif, cepat, serta menyediakan fitur lengkap baik untuk pengalaman berbelanja pelanggan maupun pengelolaan toko oleh tim administrator.

---

## Fitur Utama

### Sisi Pelanggan (Customer)
- **Katalog Produk Interaktif**: Pencarian produk, filter kategori, detail deskripsi, harga diskon, dan seleksi ukuran sepatu (shoe size variant).
- **Keranjang Belanja (Cart)**: Tambah, ubah kuantitas, hapus item, dan seleksi produk sebelum lanjut ke kasir.
- **Wishlist**: Simpan sepatu favorit ke daftar keinginan untuk dibeli nanti.
- **Sistem Voucher Promo**: Masukkan kode kupon diskon (tipe persentase maupun potongan tetap/fixed) dengan validasi minimum belanja dan kuota pemakaian.
- **Proses Checkout Mudah**: Pengisian alamat pengiriman penerima, catatan order, dan instruksi pembayaran transfer bank.
- **Pelacakan Pesanan (Order Tracking)**: 
  - Riwayat pesanan pelanggan.
  - Unggah bukti pembayaran transfer.
  - Pelacakan nomor resi pengiriman kurir.
  - Tombol konfirmasi penerimaan barang.
- **Ulasan & Rating Produk**: Pelanggan dapat memberikan rating bintang, review teks, dan mengunggah foto produk setelah pesanan selesai.
- **Autentikasi Fleksibel**: Registrasi akun lokal, verifikasi email, login kata sandi, serta login cepat menggunakan **Google OAuth (Single Sign-On)**.
- **Manajemen Profil**: Pengaturan nama, email, nomor handphone, alamat default pengiriman, dan pergantian kata sandi.

###  Sisi Administrator (Admin Panel)
- **Dashboard & Analitik Bisnis**:
  - Statistik total penjualan, pendapatan, jumlah pesanan, dan produk aktif.
  - Notifikasi pesanan baru masuk secara real-time.
  - Ringkasan pesanan terbaru dan produk dengan stok menipis.
- **Manajemen Produk & Kategori**:
  - Tambah, edit, dan hapus kategori produk.
  - Manajemen katalog produk lengkap (nama, slug, deskripsi, harga normal & diskon, foto produk).
  - Manajemen multi-varian ukuran (size) dan stok unit per produk.
- **Manajemen Gudang & Stok (Stock Inventory)**:
  - Pemantauan stok per ukuran produk secara terpusat.
  - Penambahan atau penyesuaian stok langsung dari tabel gudang.
- **Manajemen Pesanan (Orders)**:
  - Verifikasi bukti transfer pembayaran dari pelanggan.
  - Pembaruan status pesanan (*Menunggu Pembayaran*, *Diproses*, *Dikirim*, *Selesai*, *Dibatalkan*).
  - Input nomor resi pengiriman kurir.
  - Fitur cetak label / alamat pengiriman untuk kemudahan packing.
  - Pembatalan pesanan otomatis / manual (Auto-cancel pesanan yang kedaluwarsa).
- **Sistem Manajemen Voucher**:
  - Pembuatan kode voucher diskon baru (tipe persen / nominal tetap).
  - Pengaturan batas diskon maksimal, batas pemakaian kuota, serta masa berlaku voucher.
  - Tombol switch on/off untuk aktivasi atau menonaktifkan voucher.
- **Laporan Penjualan (Reports)**:
  - Rekap penjualan harian, bulanan, dan tahunan.
  - Filter rentang tanggal transaksi.
  - Unduh laporan dalam format **PDF**.
  - Ekspor data transaksi ke file **CSV** dan fitur impor data.
- **Kelola Pengguna (User Management)**:
  - Pengelolaan data pelanggan dan akun administrator.
- **CMS / Pengaturan Landing Page**:
  - Kustomisasi teks promosi, banner hero, nomor kontak WhatsApp/telepon, dan informasi toko langsung dari admin panel tanpa perlu menyentuh kode program.
- **Audit Trail & Activity Log**:
  - Pencatatan riwayat aktivitas penting di dalam sistem untuk keamanan dan transparansi data.

---

## Teknologi & Stack yang Digunakan

| Komponen | Teknologi |
|---|---|
| **Framework Backend** | [Laravel 13](https://laravel.com) |
| **Bahasa Pemrograman** | PHP 8.3+ |
| **Frontend Styling** | [Tailwind CSS](https://tailwindcss.com) & PostCSS |
| **Frontend Interactivity** | [Alpine.js](https://alpinejs.dev) |
| **Bundler & Build Tool** | [Vite](https://vitejs.dev) |
| **Database** | MySQL / MariaDB (Dapat disesuaikan dengan PostgreSQL atau SQLite) |
| **Otentikasi Tambahan** | Laravel Socialite (Google OAuth 2.0) |
| **Laporan & Ekspor** | DomPDF / Snappy PDF & CSV Exporter |
| **Pengembangan Lokal** | Laragon / XAMPP / Laravel Herd |

---

## Prasyarat Sistem

Sebelum memulai instalasi, pastikan sistem Anda telah terpasang:
- **PHP**: Versi **8.3** atau yang lebih baru dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `curl`, `gd`, `fileinfo`, `zip`.
- **Composer**: Versi **2.x+**.
- **Node.js**: Versi **18.x / 20.x+** dan **NPM**.
- **Database Server**: MySQL 8.0+ atau MariaDB 10.4+.
- Web Server / Environment Lokal: Disarankan menggunakan **Laragon**, **Laravel Herd**, atau **XAMPP**.

---

## Panduan Instalasi & Menjalankan Proyek

Ikuti langkah-langkah berikut untuk menjalankan **Sepatu.id** di komputer lokal Anda:

### 1. Clone Repositori
```bash
git clone https://github.com/aufafadlah/sepatu.id.git
cd Sepatu.id
```

### 2. Instal Dependensi PHP
```bash
composer install
```

### 3. Instal Dependensi Frontend (Node.js)
```bash
npm install
```

### 4. Konfigurasi Environment (`.env`)
Salin berkas `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
*(Di Windows PowerShell atau Command Prompt bisa menggunakan `copy .env.example .env`)*

Buka berkas `.env` lalu sesuaikan konfigurasi koneksi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_sepatu
DB_USERNAME=root
DB_PASSWORD=
```
> **Catatan**: Buatlah database baru di MySQL dengan nama `db_sepatu` (misalnya melalui phpMyAdmin atau HeidiSQL di Laragon).

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi & Seeder Database
Perintah berikut akan membuat seluruh tabel yang dibutuhkan dan mengisi data awal (kategori, produk demo, varian ukuran, voucher promo, dan akun pengguna):
```bash
php artisan migrate:fresh --seed
```

### 7. Buat Symbolic Link Storage
Pastikan folder storage publik terhubung agar foto produk dan bukti transfer dapat diakses:
```bash
php artisan storage:link
```

### 8. Jalankan Server Pengembangan

Anda dapat menjalankan seluruh layanan (PHP Server, Queue Worker, Log Pail, dan Vite) sekaligus dengan satu perintah:
```bash
composer run dev
```

Atau jalankan secara terpisah melalui dua terminal:

**Terminal 1 (Backend Laravel):**
```bash
php artisan serve
```

**Terminal 2 (Frontend Vite):**
```bash
npm run dev
```

Aplikasi sekarang dapat diakses melalui browser di:
 **http://localhost:8000** atau **http://127.0.0.1:8000**

---

##  Akun Demo Bawaan (Default Credentials)

Setelah menjalankan `php artisan db:seed`, Anda dapat menggunakan akun bawaan berikut untuk login:

| Peran (Role) | Email | Kata Sandi (Password) | Akses URL |
|---|---|---|---|
| **Administrator** | `admin@sepatu.id` | `password` | `/admin/dashboard` |
| **Pelanggan (User)** | `user@sepatu.id` | `password` | `/` (Katalog & Belanja) |

###  Voucher Promo Demo
| Kode Voucher | Tipe | Nilai Potongan | Minimal Belanja | Keterangan |
|---|---|---|---|---|
| `SEPATUBARU20` | Persentase | 20% (Maks. Rp 50.000) | Rp 100.000 | Promo Pengguna Baru |
| `HEMAT50RB` | Nominal Tetap | Rp 50.000 | Rp 250.000 | Diskon Belanja Spesial |

---

##  Struktur Direktori Proyek

```plaintext
sepatu.id/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Controller modul manajemen admin (Produk, Pesanan, Laporan, Stok, dll.)
│   │   │   ├── Auth/          # Controller autentikasi (Breeze + Socialite)
│   │   │   ├── CartController.php
│   │   │   ├── CatalogController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── OrderController.php
│   │   │   └── WishlistController.php
│   │   └── Middleware/        # Middleware otentikasi role (admin & user)
│   └── Models/                # Eloquent Models (Product, Order, Voucher, User, Cart, dll.)
├── database/
│   ├── migrations/            # Skema tabel database
│   └── seeders/               # Data awal (Users, Categories, Products, Vouchers)
├── resources/
│   ├── css/                   # Berkas Tailwind CSS
│   ├── js/                    # Skrip JavaScript & Alpine.js
│   └── views/
│       ├── admin/             # Tampilan Blade halaman admin
│       ├── auth/              # Tampilan halaman login, register, reset password
│       ├── catalog/           # Halaman etalase & detail produk
│       ├── checkout/          # Halaman kasir & pembayaran
│       ├── orders/            # Halaman pesanan saya & bukti bayar
│       └── layouts/           # Template layout induk
├── routes/
│   ├── auth.php               # Rute otentikasi
│   └── web.php                # Seluruh rute web aplikasi
└── public/                    # Assets publik (gambar, build css/js)
```

---

##  Konfigurasi Tambahan (Opsional)

### Google Login (OAuth 2.0)
Untuk mengaktifkan login dengan Google:
1. Buat kredensial OAuth 2.0 di [Google Cloud Console](https://console.cloud.google.com/).
2. Daftarkan Redirect URI ke: `http://localhost:8000/auth/google/callback` (atau sesuai domain Anda).
3. Masukkan Client ID dan Secret ke berkas `.env`:
   ```env
   GOOGLE_CLIENT_ID=your-google-client-id
   GOOGLE_CLIENT_SECRET=your-google-client-secret
   GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
   ```

### Produksi / Build Bundle
Sebelum mendeploy aplikasi ke server produksi:
```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

##  Kontribusi

Kontribusi selalu disambut dengan senang hati! Jika Anda menemukan bug atau memiliki ide pengembangan fitur baru:
1. *Fork* repositori ini.
2. Buat branch fitur baru (`git checkout -b feature/NamaFitur`).
3. *Commit* perubahan Anda (`git commit -m "feat: Menambahkan fitur X"`).
4. *Push* ke branch Anda (`git push origin feature/NamaFitur`).
5. Buat sebuah **Pull Request**.

---

## Lisensi

Proyek ini dirilis di bawah lisensi [MIT License](LICENSE).
Dikembangkan untuk kemudahan belanja sepatu dan manajemen toko online.
