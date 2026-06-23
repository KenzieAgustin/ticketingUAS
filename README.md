# ticketingUAS — Pekan Raya Jakarta 2026

## Tech Stack
- **Backend:** PHP 8.2, Laravel 12
- **Database:** MySQL
- **Payment Gateway:** Midtrans Snap (sandbox)
- **QR Code:** endroid/qr-code
- **Email:** Gmail SMTP

## Fitur Utama

| Modul | Fitur |
| Auth | Register + OTP email, Login, Forgot password, Account lockout |
| Ticket & Token | Jenis tiket, zona konser, pricing rule, quota tracker, waitlist, booking code & QR, check-in kamera |
| Order & Payment | Checkout, Midtrans Snap, webhook, voucher, poin reward |
| Operational | Gate management, staff assignment, check-in monitoring, dashboard |
| Support | Tiket support customer ↔ admin, notifikasi |
| Report | Sales report harian & per zona |

## Tim Pengembang

| Yohanes Phandry | 535250054 | Auth & User Management |
| Jessica | 535250093 | Event & Konser |
| Kenzie Agustin | 535250079 | Ticket & Token |
| Nicholous Salim | 535250095 | Order & Payment |
| Chatrina Tricia | 535250096 | Operational & Report |

## Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- MySQL
- Node.js & npm

### Langkah Setup

```bash
# 1. Clone repository
git clone https://github.com/KenzieAgustin/ticketingUAS.git
cd ticketingUAS

# 2. Install dependencies
composer install
npm install

# 3. Konfigurasi environment
cp .env.example .env
php artisan key:generate
```

Edit `.env` sesuai konfigurasi lokal:

```env
DB_DATABASE=ticketing_uas_db
DB_USERNAME=root
DB_PASSWORD=

MIDTRANS_SERVER_KEY=your_sandbox_server_key
MIDTRANS_CLIENT_KEY=your_sandbox_client_key
MIDTRANS_IS_PRODUCTION=false

MAIL_USERNAME=your_gmail@gmail.com
MAIL_PASSWORD=your_gmail_app_password
MAIL_FROM_ADDRESS=your_gmail@gmail.com
```

> **Gmail:** Gunakan [App Password](https://myaccount.google.com/apppasswords), bukan password akun biasa.

```bash
# 4. Migrasi & seeder
php artisan migrate
php artisan db:seed

# 5. Build assets
npm run dev

# 6. Jalankan server
php artisan serve
```

Akses di `http://127.0.0.1:8000`.

### Midtrans Webhook (lokal)

Gunakan ngrok untuk expose localhost agar webhook Midtrans bisa masuk:

```bash
ngrok http 8000
```

Set URL webhook di [Midtrans Dashboard](https://dashboard.sandbox.midtrans.com):
```
https://<ngrok-url>/payment/webhook
```

## Role & Akses

| Role | Akses |
|---|---|
| `customer` | Beli tiket, lihat order, support, ulasan |
| `staff_gate` | Scan QR check-in |
| `admin` | Semua fitur + manajemen user, gate, laporan |

Ubah role user via `/admin/users` (login sebagai admin).

## Catatan

- Folder `public/qrcodes/` di-generate otomatis saat transaksi pertama berhasil.
- Debug route `/debug/fix-tokens/{orderNumber}` hanya aktif di `APP_ENV=local` dan role admin.
- Untuk production, set `APP_ENV=production` dan `APP_DEBUG=false`.