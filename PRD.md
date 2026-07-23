# Product Requirement Document (PRD)
**Project Name:** Admin Coffee POS (Point of Sales)
**Document Version:** 1.0
**Date:** 23 Juli 2026
**Author:** Senior Product Manager & System Analyst

---

## 1. JUDUL & LATAR BELAKANG PROYEK

### Nama Aplikasi:
**Admin Coffee POS**

### Tujuan:
Membangun sistem *Point of Sales* (POS) terpadu yang efisien dan andal khusus untuk kedai kopi (Admin Coffee). Aplikasi ini dirancang untuk mendigitalisasi proses pencatatan transaksi kasir, mengelola manajemen inventaris (stok barang), memantau kinerja karyawan melalui sistem *shift*, serta menyediakan laporan analitik komprehensif bagi pemilik (*owner*). Tujuan utamanya adalah untuk:
1. Meminimalkan *human error* dalam pencatatan transaksi dan rekap kas harian.
2. Mempercepat proses pelayanan pelanggan (pemesanan hingga pencetakan struk).
3. Mencegah manipulasi atau kecurangan penjualan dengan mencocokkan target/harapan penjualan terhadap pendapatan aktual kas/QRIS.
4. Memudahkan pemilik usaha mengambil keputusan bisnis berbasis data (*data-driven decision*) melalui *dashboard* Web Admin.

---

## 2. FRONTEND REQUIREMENTS (MOBILE APP & WEB ADMIN)

Sistem ini terbagi menjadi dua *platform* utama yang melayani *user persona* yang berbeda: Kasir (menggunakan Mobile App/Tablet) dan Pemilik/Manajer (menggunakan Web Admin).

### A. Mobile App (Kasir / Staff)
Aplikasi berbasis *mobile/tablet* (dikembangkan menggunakan React Native / Expo) yang dioptimalkan untuk orientasi *landscape* demi kenyamanan operasional kasir.

**Fitur Utama:**
- **Autentikasi & Otorisasi:** Login menggunakan kredensial kasir/staf dengan keamanan token (*Sanctum*).
- **Manajemen Shift (Buka/Tutup Shift):**
  - **Open Shift:** Kasir diwajibkan memasukkan modal awal (*starting cash*) sebelum dapat melakukan transaksi.
  - **Close Shift:** Kasir menutup shift dengan memasukkan uang fisik aktual (*actual cash*) dan uang digital aktual (*actual QRIS*). Sistem akan merekam data penjualan dan secara otomatis membandingkan ekspektasi penjualan produk utama (cup & makanan) berdasarkan riwayat pesanan dengan tanpa memberikan celah manipulasi input manual oleh kasir.
- **Katalog Produk & Manajemen Pesanan:**
  - Menampilkan daftar kategori dan produk (lengkap dengan gambar dan harga).
  - Keranjang belanja (*cart*) dinamis yang menghitung otomatis Subtotal, Diskon, Pajak (*Tax*), Biaya Layanan (*Service Charge*), dan Total Akhir.
- **Pembayaran (Checkout):**
  - Mendukung multi-metode pembayaran: **Tunai (Cash)** dan **QRIS/E-Wallet**.
  - Kalkulasi otomatis jumlah kembalian (*change amount*) khusus metode tunai.
- **Integrasi Hardware (Bluetooth Thermal Printer):**
  - Kemampuan mendeteksi, menghubungkan (*pairing*), dan menyimpan konfigurasi printer bluetooth.
  - Pencetakan struk (receipt) secara nirkabel saat transaksi selesai.

### B. Web Admin (Owner / Manager)
*Dashboard* berbasis web responsif yang diakses melalui peramban (browser) oleh pemilik atau manajer toko.

**Fitur Utama:**
- **Dashboard Analitik:** Ringkasan performa penjualan harian/bulanan, tren penjualan, dan grafik produk terlaris.
- **Manajemen Produk & Kategori (Master Data):**
  - Mengelola kategori menu (Kopi, Non-Kopi, Makanan, dll).
  - Mengelola data produk (Nama, Harga, Gambar, dan Stok). Terdapat fitur pembaruan stok cepat.
- **Manajemen Shift & Transaksi:**
  - Melihat riwayat *shift* seluruh staf, termasuk deteksi selisih (*discrepancy*) antara uang kas aktual vs pendapatan sistem.
  - Melihat riwayat transaksi historis secara detail beserta *items* yang dibeli.
- **Laporan & Ekspor Data (Reports):**
  - Pembuatan laporan penjualan dan inventaris.
  - Fitur ekspor (*Export to Excel/PDF*) untuk keperluan pembukuan keuangan eksternal.
- **Manajemen Pengguna (User Management):** Tambah, edit, dan hapus akun kasir maupun admin tambahan.
- **Pengaturan Toko:** Mengelola nama toko, besaran pajak default, *service charge*, serta format cetak struk.

---

## 3. BACKEND REQUIREMENTS (LARAVEL API & DATABASE)

Backend bertindak sebagai *single source of truth* (pusat data) berbasis **Laravel 11**, yang mengekspos RESTful API dengan keamanan tinggi untuk melayani Mobile App dan Web Admin.

### A. Arsitektur & Teknologi:
- **Framework:** Laravel 11.x
- **Database:** MySQL / MariaDB.
- **Autentikasi:** Laravel Sanctum (Token-based authentication untuk Mobile API, Session-based untuk Web Admin).
- **Response Format:** Standar JSON.

### B. Daftar Modul API (Endpoints):
1. **Auth API:** `/api/auth/login`, `/api/auth/logout`, `/api/auth/me`
2. **User API:** CRUD Users untuk manajemen akun staf.
3. **Shift API:** 
   - `GET /shifts/current` (Validasi shift aktif staf).
   - `POST /shifts/open` (Rekam modal awal).
   - `POST /shifts/close` (Kalkulasi otomatis *expected cash, qris, cups, foods* dan rekonsiliasi data penutupan).
4. **Katalog API:** 
   - `GET /categories` (Daftar kategori).
   - `GET /products` (Daftar produk aktif & pengecekan stok).
5. **Order API:**
   - `POST /orders` (Memproses keranjang belanja, memotong stok, merekam transaksi).
   - `PATCH /orders/{id}/status` (Pembaruan status: *pending*, *completed*, *cancelled*).
6. **Report API:** `/api/reports/dashboard`, `/api/reports/sales` (Agregasi data penjualan).
7. **Settings API:** Pengaturan global aplikasi dan printer.

### C. Rancangan Database (Key Entities & Relationships):
- **`users`**: Menyimpan data kredensial pegawai (`role: admin, cashier`).
- **`shifts`**: 
  - Relasi: *Belongs to* `users`. 
  - Kolom Penting: `starting_cash`, `actual_cash`, `expected_cash`, `actual_qris`, `actual_cups`, `actual_foods`, `start_time`, `end_time`, `status`.
- **`categories`**: Data master kelompok produk.
- **`products`**: 
  - Relasi: *Belongs to* `categories`.
  - Kolom Penting: `name`, `price`, `stock`, `is_active`, `image_url`.
- **`orders`**:
  - Relasi: *Belongs to* `users`, *Belongs to* `shifts`, *Has Many* `order_items`.
  - Kolom Penting: `subtotal`, `tax`, `service_charge`, `total_amount`, `payment_method`, `amount_paid`, `change_amount`, `status`.
- **`order_items`**:
  - Relasi: *Belongs to* `orders`, *Belongs to* `products`.
  - Kolom Penting: `quantity`, `unit_price`, `subtotal`.

### D. Keamanan & Bisnis Logika (*Business Rules*):
- **Isolasi Shift:** Seorang pengguna (*cashier*) tidak dapat membuka *shift* baru jika masih terdapat *shift* yang berstatus `open`. Transaksi (Order) tidak dapat dibuat tanpa adanya *shift* yang aktif.
- **Pencegahan Kecurangan:** Saat `close shift`, parameter target capaian (jumlah cup & makanan terjual) akan dihitung otomatis murni di sisi Backend berdasarkan relasi `orders -> order_items` dengan status *completed*. Mobile app hanya menerima hasil ini dan mengirimkannya kembali (atau Backend tidak memercayai input *actual_cups* manual dari mobile).
- **Validasi Transaksi:** Setiap pembuatan *order* akan memeriksa validasi stok secara real-time. Jika stok `< quantity` yang dipesan, API menolak transaksi (HTTP 400).
- **Integritas Data:** Jika transaksi dihapus atau di-*cancel*, sistem akan mengembalikan (*restore*) stok produk yang relevan.
