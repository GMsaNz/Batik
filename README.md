# 📌 Deskripsi Proyek
E-Commerce Batik Alomani adalah platform penjualan online khusus produk batik tradisional Indonesia. Sistem ini dibangun untuk memudahkan pelaku usaha batik dalam memasarkan produknya secara digital dengan fitur-fitur lengkap e-commerce.

## 🌟 Fitur Utama
Katalog Produk dengan kategori batik daerah

Sistem Pembayaran terintegrasi (Midtrans)

Manajemen Order admin

Tracking Resi pengiriman

Laporan Penjualan otomatis

Multi-user (Admin, Seller, Customer)

Live Chat

## 🛠 Teknologi Digunakan
Frontend:

Bootstrap 5

HTML5, CSS3

JavaScript (jQuery)

Backend:

PHP 7.4+

MySQL

Libraries:

Midtrans Payment Gateway

PHPMailer (notifikasi email)

## 📦 Struktur Database
Tabel utama:

tb_produk - Menyimpan data produk batik

tb_order - Transaksi pembelian

tb_order_detail - Detail Order

tb_users - Data pengguna

tb_keranjang - menyimpan ke keranjang

tb_kategori - Kategori batik (Batik Solo, Batik Pekalongan, dll)

## 🚀 Instalasi
Clone repositori:

bash
git clone https://github.com/GMsaNz/Batik.git
Buat database dan import file SQL:


bash
mysql -u username -p nama_database < database.sql
Konfigurasi koneksi database di config/database.php

Install dependency:

bash
composer install
Konfigurasi Midtrans di config/midtrans.php

⚙ Konfigurasi
Salin file .env.example menjadi .env

Sesuaikan setting:

env
DB_HOST=localhost
DB_NAME=db_alomani
DB_USER=root
DB_PASS=

MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_PROD=false
### 📋 Cara Penggunaan
Admin:

Akses /admin

Fitur: Kelola produk, lihat order, update resi

Customer:

Register/Login

Belanja produk dan checkout

### 📸 Screenshot
https://github.com/GMsaNz/Batik/blob/master/Cuplikan%20layar%202025-06-26%20233541.png - Admin Dashboard
https://github.com/GMsaNz/Batik/blob/master/Cuplikan%20layar%202025-06-26%20233937.png - User Dashboard

## 📜 Lisensi
Proyek ini dilisensikan dibawah MIT License

## 🤝 Kontribusi
Pull request dipersilakan. Untuk perubahan besar, buka issue terlebih dahulu.

## ✉ Kontak
Email: wahyupamungkasjk@gmail.com


