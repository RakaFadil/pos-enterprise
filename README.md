# 🚀 POS Enterprise - Advanced Point of Sale System

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white)

**POS Enterprise** adalah sistem manajemen kasir modern yang dirancang untuk efisiensi bisnis ritel. Dibangun di atas Laravel 11 dengan arsitektur Dockerized untuk memastikan stabilitas dan kemudahan deployment.

---

## ✨ Fitur Utama

- 🛒 **Terminal Kasir Cepat:** Antarmuka transaksi yang intuitif dan responsif.
- 📦 **Manajemen Inventori:** Pencatatan stok otomatis dengan log audit setiap barang masuk/keluar.
- 💸 **Cash Flow Management:** Kelola pengeluaran operasional dan pemasukan non-transaksi.
- 📉 **Dashboard Visual:** Grafik tren pendapatan 30 hari terakhir (Chart.js GL).
- 🛡️ **Fraud Prevention:** Sistem pembatalan (Void) transaksi dengan alasan audit wajib.
- 📊 **Laba Bersih Aktual:** Kalkulasi otomatis pendapatan setelah dikurangi biaya operasional.
- 🖨️ **Struk & Faktur:** Cetak bukti transaksi dan export laporan keuangan ke Excel.

---

## 🛠️ Tech Stack

- **Framework:** [Laravel 11](https://laravel.com)
- **Database:** MySQL 8.x
- **Environment:** Docker (Laravel Sail) + WSL 2
- **UI:** AdminLTE 3 + Bootstrap 5
- **Visual:** Chart.js

---

## 🚀 Instalasi Cepat

Pastikan Anda sudah menginstal **Docker Desktop** & **WSL 2** di komputer Anda.

1. **Clone Repository**
   ```bash
   git clone https://github.com/RakaFadil/pos-enterprise.git
   cd pos-enterprise

2. Siapkan Environment
cp .env.example .env
# Edit .env, sesuaikan DB_CONNECTION menjadi mysql

3. Nyalakan Mesin Docker
./vendor/bin/sail up -d

4. Inisialisasi Database
./vendor/bin/sail artisan migrate:fresh --seed
Akses Aplikasi Buka http://localhost di browser Anda

5. Akses Aplikasi Buka http://localhost di browser Anda.