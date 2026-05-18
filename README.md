<div align="center">
  <div style="background-color: #F5C400; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 50px;">
    🍧
  </div>
  <h1 align="center">Es Teler Gembira</h1>
  <p align="center">
    <strong>Sistem Pemesanan Cerdas & Segar untuk Es Teler Favorit Anda!</strong>
  </p>
</div>

---

## 🌟 Tentang Proyek

**Es Teler Gembira** adalah aplikasi web pemesanan makanan dan minuman (berbasis Laravel) yang dirancang untuk memudahkan pelanggan setia memesan Es Teler kesukaan mereka. Aplikasi ini menyediakan pengalaman berbelanja yang interaktif, terintegrasi dengan WhatsApp untuk pemesanan cepat, serta dilengkapi sistem manajemen order (Dashboard Admin) yang komprehensif.

## ✨ Fitur Utama

### 🧑‍💻 Untuk Pelanggan (User):
- **E-Menu & Keranjang Belanja**: Tampilan menu interaktif dan keranjang belanja dinamis secara real-time.
- **Checkout Cepat (WhatsApp)**: Kemudahan finalisasi pesanan yang langsung terhubung ke WhatsApp penjual.
- **Integrasi Payment Gateway**: Dukungan pembayaran otomatis (seperti Midtrans) dengan status yang tersinkronisasi.
- **Dashboard Pelanggan**: Pantau riwayat pesanan, status pembayaran, dan kelola profil akun.
- **Desain Responsif**: Tampilan UI yang cantik dan tetap rapi baik di Desktop maupun *Smartphone*.

### 🔐 Untuk Administrator (Admin):
- **Manajemen Pesanan Terpusat**: Pantau, verifikasi, dan kelola seluruh pesanan masuk dengan status yang jelas.
- **Sistem Keamanan Data (Soft Delete)**: Riwayat transaksi aman; jika *user* menyembunyikan pesanan, data aslinya tetap aman untuk rekap admin.
- **Manajemen Menu Lengkap (CRUD)**: Tambah, edit gambar, sesuaikan harga, dan hapus menu dengan mudah.

## 🚀 Teknologi yang Digunakan

Aplikasi ini dibangun menggunakan *stack* teknologi modern untuk memastikan performa dan pengalaman pengguna yang maksimal:

- **Backend Framework**: [Laravel 11+](https://laravel.com/) (PHP)
- **Frontend & Styling**: [Tailwind CSS v4](https://tailwindcss.com/) & Vanilla CSS
- **Interaktivitas UI**: [Alpine.js](https://alpinejs.dev/)
- **Ikon**: [FontAwesome 6](https://fontawesome.com/)
- **Payment Gateway**: Midtrans API

## 🛠️ Panduan Instalasi Lokal

Ingin mengembangkan atau menjalankan aplikasi ini di komputer Anda? Ikuti langkah-langkah berikut:

1. **Clone Repository**
   ```bash
   git clone <url-repository-anda>
   cd es_teller_gembira
   ```

2. **Install Dependensi (PHP & Node.js)**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env`, lalu atur konfigurasi *database* (misalnya MySQL atau SQLite) dan kredensial pihak ketiga.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi & Seeding Database**
   Siapkan struktur tabel beserta data awal (admin/menu *dummy*).
   ```bash
   php artisan migrate --seed
   ```

5. **Jalankan Server Pengembangan**
   Buka dua tab terminal dan jalankan perintah berikut secara bersamaan:
   ```bash
   # Terminal 1 (Menjalankan server PHP Laravel)
   php artisan serve

   # Terminal 2 (Menjalankan Vite untuk mem-build Tailwind/Asset)
   npm run dev
   ```
   Aplikasi sekarang dapat diakses melalui browser pada `http://localhost:8000`.

---
<div align="center">
  Dibuat untuk memenuhi Tugas Mata Kuliah Pemrograman Web (Tubes PWEB) 🚀🍧
</div>
