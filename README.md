# GameShop - Platform Top-up Game Online

GameShop adalah platform top-up game online yang dibangun dengan Laravel. Aplikasi ini memungkinkan pengguna untuk melakukan top-up berbagai game populer seperti Mobile Legends, Free Fire, PUBG Mobile, dan Genshin Impact.

## 🚀 Fitur Utama

### Untuk User
- **Dashboard Interaktif**: Statistik transaksi, riwayat terbaru, dan aksi cepat
- **Sistem Notifikasi**: Notifikasi real-time untuk status transaksi
- **Sistem Promo**: Kode promo dengan berbagai jenis diskon (persentase, nominal tetap, bonus)
- **Riwayat Transaksi**: Detail lengkap semua transaksi dengan filter
- **Profil User**: Manajemen data pribadi
- **Game Detail**: Informasi lengkap game dan paket top-up

### Untuk Admin
- **Dashboard Admin**: Statistik lengkap sistem
- **Manajemen User**: CRUD user dengan role management
- **Manajemen Game**: Tambah, edit, hapus game dan paket top-up
- **Manajemen Transaksi**: Monitor dan proses semua transaksi
- **Sistem Promo**: Buat dan kelola kode promo

### Untuk Operator
- **Dashboard Operator**: Fokus pada transaksi pending
- **Proses Transaksi**: Update status transaksi dengan catatan
- **Riwayat Diproses**: Track transaksi yang telah diproses
- **Detail Transaksi**: Informasi lengkap untuk setiap transaksi

## 🎮 Game yang Didukung

- **Mobile Legends**: Diamond
- **Free Fire**: UC (Unknown Cash)
- **PUBG Mobile**: UC
- **Genshin Impact**: Genesis Crystal

## 💰 Sistem Promo

### Jenis Promo
1. **Persentase**: Diskon berdasarkan persentase (contoh: 10%)
2. **Nominal Tetap**: Potongan harga tetap (contoh: Rp 5.000)
3. **Bonus**: Bonus item game (contoh: +100 Diamond)

### Kode Promo Demo
- `WELCOME10`: Diskon 10% untuk transaksi pertama
- `GAMING5000`: Potongan Rp 5.000 untuk semua game
- `BONUS100`: Bonus 100 Diamond untuk Mobile Legends

## 🔔 Sistem Notifikasi

- **Notifikasi Real-time**: Update status transaksi
- **Badge Counter**: Jumlah notifikasi belum dibaca
- **Dropdown Preview**: Preview notifikasi terbaru
- **Halaman Notifikasi**: Manajemen lengkap notifikasi

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 10
- **Frontend**: Bootstrap 5, Font Awesome
- **Database**: MySQL/SQLite
- **Authentication**: Laravel Breeze
- **Role Management**: Custom middleware

## 📦 Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd gameshop
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup Database**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Serve Application**
   ```bash
   php artisan serve
   ```

## 👥 Akun Default

### Admin
- Email: `admin@gameshop.com`
- Password: `password`

### Operator
- Email: `operator@gameshop.com`
- Password: `password`

## 📊 Struktur Database

### Tabel Utama
- `users`: Data pengguna dengan role (admin, operator, user)
- `games`: Data game yang didukung
- `topup_packages`: Paket top-up untuk setiap game
- `transactions`: Transaksi top-up
- `notifications`: Sistem notifikasi
- `promos`: Kode promo dan diskon

### Relasi
- User → Transactions (one-to-many)
- Game → TopupPackages (one-to-many)
- TopupPackage → Transactions (one-to-many)
- User → Notifications (one-to-many)

## 🔐 Role dan Permission

### Admin
- Akses penuh ke semua fitur
- Manajemen user, game, dan transaksi
- Buat dan kelola promo

### Operator
- Proses transaksi pending
- Update status transaksi
- Lihat riwayat transaksi yang diproses

### User
- Top-up game
- Lihat riwayat transaksi
- Kelola profil
- Terima notifikasi

## 🎨 UI/UX Features

- **Responsive Design**: Optimized untuk mobile dan desktop
- **Modern Interface**: Clean dan user-friendly
- **Interactive Elements**: Hover effects, animations
- **Color Scheme**: Orange theme (#ff6b35)
- **Icons**: Font Awesome untuk visual yang menarik

## 📱 Fitur Mobile

- **Mobile-First Design**: Responsive untuk semua device
- **Touch-Friendly**: Button dan form yang mudah digunakan
- **Fast Loading**: Optimized performance

## 🔄 Workflow Transaksi

1. **User memilih game** → Lihat detail game dan paket
2. **Pilih paket top-up** → Pilih nominal yang diinginkan
3. **Input data player** → ID dan nama player
4. **Terapkan promo** (opsional) → Masukkan kode promo
5. **Konfirmasi pembayaran** → Review transaksi
6. **Admin/Operator proses** → Update status transaksi
7. **User terima notifikasi** → Update status real-time

## 🚀 Deployment

### Requirements
- PHP 8.1+
- MySQL 5.7+ atau SQLite
- Composer
- Node.js & NPM

### Production Setup
1. Set environment variables
2. Run migrations
3. Seed database
4. Configure web server
5. Set up SSL certificate

## 🤝 Contributing

1. Fork repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## 📄 License

This project is licensed under the MIT License.

## 📞 Support

Untuk bantuan dan pertanyaan, silakan hubungi:
- Email: support@gameshop.com
- WhatsApp: +62 812-3456-7890

---

**GameShop** - Platform top-up game online terpercaya! 🎮✨
