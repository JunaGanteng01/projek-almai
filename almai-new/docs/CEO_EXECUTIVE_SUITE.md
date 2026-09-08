# CEO Executive Suite ALMAI

## URL dan akses

- URL kanonis: `/ceo`
- Dashboard: `/ceo/dashboard`
- URL lama `/ea/*` tetap tersedia dan menggunakan controller serta sumber data yang sama.
- Hanya pengguna dengan `level_id = 10` yang dapat mengakses route terlindungi.
- Seeder `CeoSeeder` hanya membuat level CEO; seeder tidak membuat akun atau password.
- Buat atau rotasi kredensial CEO secara aman dengan `php spark ceo:create-account`. Password acak hanya ditampilkan saat command selesai dan disimpan sebagai hash.

## Sumber data

| Bagian | Sumber |
|---|---|
| Revenue | `transaksi` berstatus `confirmed`, pembayaran poin dikecualikan |
| Kas & bank | saldo debit dikurangi kredit pada `jurnal` untuk akun kategori kas/bank |
| Approval | `ea_approvals` |
| Invoice | `customer_invoices` |
| Pencairan | `withdrawals` |
| Event | `layanan_event` |
| CRM | `cs_conversations` |
| Kalender | meeting, task, reminder, event, invoice, approval, dan tagihan |
| Alert | aturan deterministik dari sumber di atas |
| OKR | `ceo_goals` |

## Ekspor laporan

- Tombol **Ekspor Excel** menghasilkan workbook `.xlsx` native, bukan CSV yang hanya dibuka oleh Excel.
- Workbook berisi sheet Ringkasan, Tren Pendapatan, Transaksi Terkini, Approval Pending, OKR & Sasaran, serta Risk & Alert.
- Nilai rupiah, angka, persentase, tanggal, lebar kolom, filter, freeze pane, grafik tren, dan print layout sudah diformat untuk laporan eksekutif.
- Endpoint lama `/ceo/reports/export-csv` tetap tersedia sebagai alias kompatibilitas, tetapi mengirim workbook XLSX agar bookmark lama tidak rusak.

## Instalasi dan verifikasi

```powershell
php spark migrate
php spark db:seed CeoSeeder
php spark ceo:diagnose
vendor\bin\phpunit tests\unit\CeoFoundationTest.php
```

`php spark ceo:diagnose` bersifat read-only dan memeriksa role, schema, agregasi KPI, alert, serta kalender.

## Keamanan keputusan

- Mutasi memakai POST dan proteksi CSRF global.
- Approval membutuhkan alasan minimal lima karakter.
- Kolom `version` mencegah double-submit atau keputusan berdasarkan data lama.
- Perubahan approval dan modul sumber berada dalam satu transaksi database.
- Keputusan dicatat di `audit_logs` dan requester menerima notifikasi jika `requested_by_user_id` tersedia.
- Approval tagihan hanya memberi otorisasi internal; tidak mengirim pembayaran eksternal.
- Batch approval memproses maksimal 50 item dalam satu transaksi; seluruh batch dibatalkan jika salah satu item sudah berubah atau tidak eligible.

## Alur terhubung

- Form tagihan di dashboard membuat record `ceo_bills` dan approval terkait secara atomik.
- Approval tagihan memperbarui status sumber menjadi `authorized` atau `rejected`.
- Form sasaran membuat record `ceo_goals`, kemudian status off-track/overdue ikut memengaruhi Risk & Alert Center.
- Meeting, task, reminder, event, invoice jatuh tempo, approval, dan tagihan digabungkan oleh `ExecutiveCalendarService`.
- Quick action membuat notifikasi dan audit log setelah data berhasil disimpan.

## Rollback

Migration mempertahankan tabel EA yang telah ada agar riwayat operasional tidak hilang. Rollback hanya menghapus tabel baru `ceo_bills` dan `ceo_goals`. Buat backup database sebelum rollback pada lingkungan produksi.

## Catatan operasional

- Tetapkan salah satu akun yang sah ke `level_id = 10` melalui proses administrasi yang aman. Jangan menaruh password CEO dalam seeder atau repository.
- Review angka saldo kas bersama Accounting sebelum dijadikan angka publik; dashboard menampilkan saldo buku jurnal, bukan saldo bank real-time.
- `current_participants` dipakai untuk ringkasan event sampai satu sumber registrasi peserta yang lebih kuat ditetapkan.
