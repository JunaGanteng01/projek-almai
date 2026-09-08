# Deployment CRM Monitoring ke cPanel

## Sebelum upload

1. Backup seluruh file aplikasi dan database production.
2. Pastikan versi production berasal dari basis kode yang sama. Paket ini berisi file penuh, bukan patch baris-per-baris.
3. Jangan menimpa file `.env` production dengan `.env` lokal.

## Upload

Ekstrak isi ZIP pada root aplikasi CodeIgniter (folder yang berisi `app`, `public`, `writable`, dan `spark`). Struktur folder di dalam ZIP sudah mengikuti struktur aplikasi.

## Konfigurasi environment

Tambahkan atau perbarui nilai berikut pada `.env` production menggunakan kredensial nomor WhatsApp yang dipilih. Jangan menyimpan nilai rahasia di source code atau ZIP.

```dotenv
BALESOTOMATIS_SECRET_KEY=...
BALESOTOMATIS_LICENSES_KEY=...
BALESOTOMATIS_WEBHOOK_KEY=...
NO_OTP=...
```

Pastikan `app.baseURL` tetap memakai domain production, bukan localhost atau URL `trycloudflare.com`.

## Instalasi schema

Dari Terminal cPanel pada root aplikasi:

```bash
php spark crm:install
php spark crm:diagnose
```

Command `crm:install` hanya memasang migration CRM sehingga tidak menjalankan migration lama lain yang masih tertunda.

## Cron queue dan SLA

Tambahkan Cron Job setiap menit. Ganti `/home/USERNAME/path-aplikasi` dengan path aplikasi yang sebenarnya:

```cron
* * * * * cd /home/USERNAME/path-aplikasi && /usr/local/bin/php spark crm:process >> writable/logs/crm-worker.log 2>&1
```

Jika lokasi PHP berbeda, cek melalui Terminal cPanel:

```bash
which php
```

## Webhook production

Gunakan URL domain production, bukan tunnel lokal:

```text
https://domain-production/webhook/balesotomatis
```

Pilih device/nomor WhatsApp yang sama dengan kredensial di `.env`, method `POST`, event pesan masuk (`incoming_chat`), dan status aktif.

## Verifikasi

1. Login sebagai Admin atau Superadmin.
2. Buka `/admin/crm` atau `/superadmin/crm`.
3. Pastikan summary dan daftar percakapan tampil.
4. Registrasikan satu akun pengujian dengan nomor WhatsApp valid.
5. Pastikan welcome terkirim dan sesi berubah dari `NEW` ke `AUTO_REPLIED`.
6. Balas dari WhatsApp dan pastikan status menjadi `UNREAD`.
7. Buka chat sebagai CS, kirim balasan, lalu pastikan status menjadi `FOLLOW_UP`.

## Rollback file

Kembalikan file dari backup sebelum upload. Migration CRM sengaja mempertahankan tabel dan histori pada rollback agar data chat tidak hilang tanpa sengaja.
