# CRM Monitoring Chat + Balesotomatis

## Instalasi lokal (Laragon)

1. Start **Apache/Nginx** dan **MySQL** di Laragon.
2. Buka terminal pada folder proyek `C:\projek-almaii\almai-new`.
3. Jalankan schema CRM saja (aman saat masih ada migrasi lama yang tertunda):

   ```powershell
   php spark crm:install
   ```

4. Opsional, buat satu percakapan dummy:

   ```powershell
   php spark db:seed CrmDemoSeeder
   ```

5. Periksa database dan antrean:

   ```powershell
   php spark crm:diagnose
   ```

6. Login sebagai Admin/Superadmin lalu buka:

   - `http://127.0.0.1:8080/admin/crm`
   - `http://127.0.0.1:8080/superadmin/crm`

## Konfigurasi Balesotomatis

Pastikan `.env` berisi nilai berikut tanpa tanda kutip tambahan:

```dotenv
BALESOTOMATIS_SECRET_KEY=...
BALESOTOMATIS_LICENSES_KEY=...
BALESOTOMATIS_WEBHOOK_KEY=...
```

Webhook yang didaftarkan pada dashboard Balesotomatis:

```text
https://domain-anda/webhook/balesotomatis
```

Header `BLS-OTO-NUMBERID` harus sama dengan `BALESOTOMATIS_WEBHOOK_KEY`. Saat localhost, gunakan tunnel HTTPS bila Balesotomatis harus mengirim webhook dari internet.

## Worker dan SLA

Jalankan manual untuk pengujian:

```powershell
php spark crm:process
```

Pada server, jadwalkan setiap menit. Worker menangani reminder UNREAD lebih dari 5 menit, alert lebih dari 15 menit, dan workflow automation yang tertunda.

## Alur pengujian end-to-end

1. Daftarkan user baru dengan nomor WhatsApp valid.
2. Pastikan sesi dibuat sebagai `NEW`; setelah welcome berhasil dikirim status menjadi `AUTO_REPLIED` dan PIC terisi round-robin.
3. Balas pesan dari WhatsApp user. Webhook menyimpan pesan dan status menjadi `UNREAD`.
4. Buka CRM, klik percakapan, ubah ke `IN_PROGRESS`.
5. Kirim balasan. Pesan dikirim melalui Balesotomatis dan status menjadi `FOLLOW_UP`.
6. Balas lagi sebagai user; status kembali `UNREAD`.
7. Ubah status ke `DONE`. Pesan user berikutnya akan membuat sesi aktif baru.

Pesan OTP, registrasi WhatsApp, konfirmasi absensi, dan reset password tetap memakai handler operasional lama dan tidak dimasukkan sebagai percakapan CS.

## Endpoint internal

Semua endpoint dilindungi filter Admin/Superadmin dan tersedia di prefix `/admin/crm/api` atau `/superadmin/crm/api`.

- `GET /dashboard/summary`
- `GET /sessions`
- `GET /sessions/{id}`
- `POST /sessions/{id}/status`
- `POST /sessions/{id}/assign`
- `POST /sessions/{id}/messages`
- `GET /pics`
- `GET /recap`
- `POST /automations`
- `POST /automations/{id}`

## Format action workflow

```json
[
  {"action":"SEND_MESSAGE","message":"Halo, ada yang bisa kami bantu?"},
  {"action":"ASSIGN_PIC","pic_id":7},
  {"action":"CHANGE_STATUS","status":"FOLLOW_UP"}
]
```

Trigger yang didukung: `user_registered`, `message_received`, dan `status_changed`.
