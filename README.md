# AGRIS (Agroindustri System)

AGRIS adalah platform e-commerce dan kemitraan agroindustri modern yang dirancang untuk menghubungkan produsen/admin dengan agen penyalur. Platform ini dibangun menggunakan framework **Laravel 13** dan **Tailwind CSS v4** dengan integrasi API pihak ketiga seperti **Midtrans**, **Biteship**, **Wilayah.id**, dan **Google OAuth** untuk menghadirkan pengalaman berbelanja, manajemen logistik, serta pembayaran online yang terotomatisasi secara aman dan real-time.

---

## 🚀 Fitur Utama (Core Features)

Berikut adalah daftar fitur utama yang tersedia di sistem AGRIS berdasarkan peran pengguna (*Admin* dan *Agen*):

### 1. Keamanan & Autentikasi Pengguna (Security & Auth)
- **Registrasi & Verifikasi OTP**: Pendaftaran akun menggunakan email dengan sistem verifikasi OTP (One-Time Password) yang dikirim otomatis melalui Email (Laravel Mailer) untuk menjamin validitas akun.
- **Google OAuth (Sign In with Google)**: Kemudahan masuk dan mendaftar dengan sekali klik menggunakan akun Google (Laravel Socialite).
- **Reset Password**: Alur reset password mandiri yang aman menggunakan token verifikasi via email.
- **Login Throttling**: Proteksi tambahan terhadap serangan brute-force dengan membatasi jumlah kegagalan login berturut-turut.

### 2. Modul Kemitraan Agen (Partnership Module)
- **Pengajuan Kemitraan**: Agen dapat mendaftar menjadi mitra resmi AGRIS.
- **Unggah MOU Digital**: Agen mengunggah dokumen nota kesepahaman (MOU) yang telah ditandatangani untuk diverifikasi.
- **Verifikasi Kemitraan**: Admin memiliki panel khusus untuk menyetujui/menolak berkas kemitraan dan menandatangani kerja sama.

### 3. Manajemen Produk & Kategori (Product Catalog & Inventory)
- **Katalog Terkategori**: Pengelompokan produk pertanian dan agroindustri berdasarkan kategori (misalnya: pupuk, beras premium, pestisida, dsb).
- **CRUD Inventaris oleh Admin**: Manajemen data produk yang dinamis oleh Admin lengkap dengan upload gambar produk, detail harga, deskripsi, dan berat/karung.
- **Soft Deletes (Keranjang Sampah)**: Fitur *Trash*, *Restore*, dan *Force Delete* untuk mengamankan data produk dari penghapusan permanen yang tidak disengaja.

### 4. Keranjang & Sistem Transaksi (Cart & Transaction System)
- **Keranjang Belanja Interaktif**: Tambah, edit jumlah, dan hapus produk dari keranjang secara real-time sebelum checkout.
- **Opsi Pengambilan**: Pilihan fleksibel bagi agen untuk memilih metode pengiriman:
  1. **Kirim via Kurir**: Menggunakan jasa ekspedisi terintegrasi.
  2. **Ambil di Tempat**: Mengambil langsung di gudang utama AGRIS (Patrang, Jember) tanpa biaya pengiriman.

### 5. Logistik Terintegrasi (Biteship Shipping API)
- **Cek Ongkir Otomatis**: Integrasi dengan API Biteship untuk menghitung tarif pengiriman berdasarkan bobot total pesanan dan alamat tujuan secara real-time.
- **Pelacakan Pengiriman (Live Tracking)**: Pengguna dapat melacak status perjalanan paket dengan tautan pelacakan langsung Biteship.
- **Sinkronisasi Otomatis**: Webhook Biteship menerima pembaruan dari ekspedisi dan mengubah status pesanan (`diproses` ➔ `dikirim` ➔ `selesai`) secara otomatis.

### 6. Gerbang Pembayaran Digital (Midtrans Payment Gateway)
- **Pembayaran Online**: Integrasi dengan Midtrans Snap untuk pembayaran aman via Virtual Account, E-Wallet (GoPay, ShopeePay), Kartu Kredit, atau QRIS.
- **Simulasi Pembayaran (Staging/Local)**: Fitur pembayaran simulasi offline khusus untuk lingkungan lokal (pengujian) sehingga tidak membutuhkan saldo riil.
- **Webhook Callback Status**: Midtrans webhook yang otomatis memperbarui status pembayaran (`pending` ➔ `berhasil` / `gagal` / `daluwarsa`).
- **Pengembalian Stok Otomatis**: Jika transaksi pembayaran dibatalkan atau kedaluwarsa, stok produk akan dikembalikan secara otomatis oleh sistem.

### 7. Konsultasi & Chat Real-Time (Live Chat Support)
- **Live Chat**: Komunikasi langsung (dua arah) antara Admin dengan Agen untuk mempermudah konsultasi kemitraan atau kendala teknis.
- **WebSocket Engine**: Didukung oleh Laravel Reverb (WebSocket server) dan Laravel Echo di sisi frontend untuk transfer pesan yang instan dan tanpa reload.

### 8. Wilayah Administrasi Indonesia (Wilayah.id API)
- **Dropdown Lokasi Dinamis**: Sinkronisasi data provinsi, kabupaten/kota, kecamatan, hingga desa secara dinamis saat agen melengkapi profil alamat untuk akurasi alamat pengiriman.

### 9. Manajemen Blog & Laporan Penjualan (Dashboard & Report)
- **Blog Informasi**: Dashboard untuk menyajikan berita industri tani, tips agro, atau promo terbaru.
- **Laporan Keuangan & Penjualan**: Laporan transaksi terperinci bagi Admin untuk melihat rekapitulasi penjualan, total pendapatan, dan log transaksi.

---

## 🛠️ API & Layanan Pihak Ketiga (Integrations)

Aplikasi AGRIS memanfaatkan beberapa layanan eksternal untuk memperkaya fungsionalitas dan otomasinya:

1. **Midtrans Payment Gateway API**
   - **Endpoint Integrasi**: `https://app.sandbox.midtrans.com/snap/v1/transactions` (Sandbox) / `https://app.midtrans.com/snap/v1/transactions` (Production)
   - **Kegunaan**: Pembuatan snap token transaksi, pengecekan status pembayaran real-time, pembatalan/pengembalian (refund) pembayaran, dan penanganan webhook callback.
   - **Library**: `midtrans/midtrans-php`

2. **Biteship Courier Aggregator API**
   - **Endpoint Integrasi**: `https://api.biteship.com/v1` (atau sandbox)
   - **Kegunaan**: Pencarian area koordinat ID, kalkulasi tarif pengantar (jne, sicepat, jnt, tiki, lion, ninja, anteraja), pemesanan kurir (courier booking), live tracking resi, dan penanganan status update melalui webhook.
   - **Client**: `Illuminate\Support\Facades\Http`

3. **Wilayah.id API**
   - **Endpoint Integrasi**: `https://wilayah.id/api`
   - **Kegunaan**: Pengambilan data wilayah administratif Indonesia secara hierarkis (Provinsi ➔ Kabupaten ➔ Kecamatan ➔ Desa).
   - **Client**: `Illuminate\Support\Facades\Http`

4. **Google OAuth API**
   - **Kegunaan**: Autentikasi agen menggunakan akun Google pihak ketiga secara aman.
   - **Library**: `laravel/socialite`

5. **Laravel Reverb (Pusher Protocol)**
   - **Kegunaan**: Mesin broker WebSocket real-time lokal untuk fungsionalitas Live Chatting.
   - **Library**: `laravel/reverb` & `pusher/pusher-php-server`

6. **Laravel Mailer (SMTP)**
   - **Kegunaan**: Mengirimkan kode OTP verifikasi pendaftaran akun dan tautan ganti kata sandi.

---

## 💻 Tech Stack

- **Backend Framework**: Laravel 13.x (PHP 8.3+)
- **Database**: MySQL / MariaDB (Database Session, Queue, Cache)
- **Frontend Utility**: Tailwind CSS v4, Alpine.js, Axios, AOS (Animate on Scroll)
- **Real-Time Client**: Laravel Echo & Pusher JS
- **Testing Tool**: Pest PHP

---

## ⚙️ Panduan Instalasi & Pengembangan Lokal

Ikuti langkah-langkah di bawah ini untuk memasang aplikasi AGRIS di komputer lokal Anda:

### Prasyarat (Prerequisites)
- PHP >= 8.3
- Composer
- Node.js & NPM
- MySQL / MariaDB Server

### Langkah-Langkah

1. **Clone Repository & Masuk ke Direktori**
   ```bash
   git clone <repository-url>
   cd Agris
   ```

2. **Jalankan Setup Otomatis**
   Aplikasi telah dilengkapi dengan script setup bawaan untuk mempercepat proses pemasangan:
   ```bash
   composer run setup
   ```
   *Script ini secara otomatis akan menjalankan `composer install`, menyalin `.env.example` ke `.env`, membuat `APP_KEY`, menjalankan migrasi tabel database, memasang paket Node.js, dan melakukan build aset frontend via Vite.*

3. **Konfigurasi Environment (`.env`)**
   Buka berkas `.env` yang baru dibuat dan isi konfigurasi koneksi database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=agris
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

   Tambahkan API key dan kredensial untuk layanan pihak ketiga yang dibutuhkan:
   ```env
   # Midtrans Credentials
   MIDTRANS_SERVER_KEY=your_midtrans_server_key
   MIDTRANS_CLIENT_KEY=your_midtrans_client_key
   MIDTRANS_IS_PRODUCTION=false

   # Biteship Credentials
   BITESHIP_API_KEY=your_biteship_api_key

   # Google Socialite Credentials
   GOOGLE_CLIENT_ID=your_google_client_id
   GOOGLE_CLIENT_SECRET=your_google_client_secret
   GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

   # Mail/SMTP Credentials (untuk kirim OTP)
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_mailtrap_username
   MAIL_PASSWORD=your_mailtrap_password
   ```

4. **Isi Data Awal Database (Seeding)**
   Jalankan seeder untuk memasang data kategori produk dasar dan akun Admin utama:
   ```bash
   php artisan db:seed
   ```
   > [!NOTE]
   > **Akun Admin Default**:
   > - **Email**: `agrisagroindustri@gmail.com`
   > - **Password**: `admin123`

5. **Jalankan Server Pengembangan**
   Untuk memulai server web, queue, Vite bundler, dan server WebSocket Reverb secara bersamaan, jalankan satu perintah berikut:
   ```bash
   composer run dev
   ```
   Atau jika menggunakan NPM:
   ```bash
   npm run dev
   ```
   *Server web Anda akan berjalan di `http://localhost:8000` (atau port terdekat yang tersedia).*

---

## 🧪 Pengujian (Testing)

Untuk menjalankan unit dan integrasi testing dengan Pest PHP, gunakan perintah berikut:
```bash
composer run test
```
