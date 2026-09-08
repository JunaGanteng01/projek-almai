# Flow Event Absensi + Notifikasi WhatsApp

Flow check-in yang sudah ada tetap dipakai. Setelah absensi tercatat, pengguna mengirim konfirmasi WhatsApp terlebih dahulu. BalesOtomatis baru membalas setelah webhook menerima pesan tersebut.

```text
Peserta scan QR / membuka link absensi
                |
                v
Validasi login, QR, masa berlaku, dan duplikasi
                |
                v
Simpan absensi + berikan 100 poin
                |
                v
Event memakai template WhatsApp? -- tidak --> Dashboard absensi
                |
               ya
                |
                v
Buka wa.me dengan teks konfirmasi yang sopan
                |
                v
Pengguna menekan tombol kirim di WhatsApp
                |
                v
Webhook BalesOtomatis menerima pesan pengguna
                |
                v
BalesOtomatis mengirim balasan sesuai template event
                |
                v
Tandai balasan terkirim (anti-duplikat 24 jam)
```

## Konfigurasi

Pastikan kredensial berikut tersedia di `.env`:

```dotenv
BALESOTOMATIS_SECRET_KEY=...
BALESOTOMATIS_LICENSES_KEY=...
BALESOTOMATIS_WEBHOOK_KEY=...
```

Admin membuat template di **Superadmin > WhatsApp Gateway > Template**, kemudian memilihnya pada form buat/edit absensi.

Placeholder template yang tersedia:

- `{nama}` atau `{name}`: nama peserta
- `{event}` atau `{acara}`: nama kegiatan
- `{tanggal}`: tanggal kegiatan dalam Bahasa Indonesia
- `{waktu_absen}`: waktu check-in
- `{poin}`: poin yang didapat

Contoh yang disarankan:

```text
Yth. {nama},

Terima kasih telah mengonfirmasi kehadiran pada kegiatan {event} yang diselenggarakan pada {tanggal}.

Absensi Anda telah tercatat pada {waktu_absen}. Sebanyak {poin} poin telah ditambahkan ke akun Anda.

Kami mengucapkan terima kasih atas partisipasi Anda dalam kegiatan ALMAI.
```

Pengiriman balasan WhatsApp bersifat best effort. Kegagalan API tidak membatalkan absensi atau pemberian poin.

## Step-by-step tes di local

### A. Persiapan aplikasi

1. Buka PowerShell dan masuk ke folder aplikasi:

   ```powershell
   cd C:\projek-almaii\almai-new
   ```

2. Pastikan PHP, Composer, dan database lokal tersedia:

   ```powershell
   php -v
   composer --version
   php spark routes
   ```

3. Jalankan MySQL/MariaDB, misalnya melalui Laragon.

4. Isi konfigurasi lokal di `.env`. Jangan menambahkan tanda kutip pada kredensial BalesOtomatis:

   ```dotenv
   CI_ENVIRONMENT = development
   app.baseURL = 'http://127.0.0.1:8080/'

   database.default.hostname = localhost
   database.default.database = nama_database_local
   database.default.username = root
   database.default.password =
   database.default.DBDriver = MySQLi

   BALESOTOMATIS_SECRET_KEY=secret_yang_valid
   BALESOTOMATIS_LICENSES_KEY=license_yang_valid
   BALESOTOMATIS_WEBHOOK_KEY=number_id_atau_webhook_key
   NO_OTP=628xxxxxxxxxx
   ```

   `NO_OTP` adalah nomor tujuan pada flow `wa.me` lama. Nomor peserta di tabel `users.phone` harus merupakan nomor WhatsApp aktif.

5. Jika database lokal masih kosong, jalankan migrasi dan seeder proyek:

   ```powershell
   php spark migrate
   php spark db:seed DatabaseSeeder
   ```

   Lewati langkah ini bila memakai salinan database development yang tabel event, `absensi_peserta`, `points`, dan `whatsapp_templates`-nya sudah tersedia.

6. Jalankan aplikasi:

   ```powershell
   php spark serve --host 127.0.0.1 --port 8080
   ```

7. Buka `http://127.0.0.1:8080` dan login sebagai Superadmin. Gunakan akun lokal yang memang tersedia di database Anda.

### B. Siapkan template WhatsApp

1. Buka `http://127.0.0.1:8080/superadmin/whatsapp-gateway/templates`.
2. Klik **Tambah Template**.
3. Isi nama, misalnya `Konfirmasi Absensi Event`.
4. Gunakan isi pesan berikut:

   ```text
   Halo {nama}, absensi Anda untuk {event} pada {tanggal} sudah tercatat.
   Waktu check-in: {waktu_absen}.
   Anda memperoleh {poin} poin. Terima kasih.
   ```

5. Simpan template.

### C. Buat event absensi

1. Buka `http://127.0.0.1:8080/superadmin/absensi/create`.
2. Isi jenis kegiatan, nama kegiatan, tanggal, masa berlaku QR, dan data wajib lainnya.
3. Pada **Format Notif WA**, pilih `Konfirmasi Absensi Event`.
4. Simpan event.
5. Dari halaman daftar/detail absensi, salin link check-in atau scan QR yang dibuat. Format link-nya:

   ```text
   http://127.0.0.1:8080/absensi/checkin/KODE_QR
   ```

### D. Tes flow pengguna mengirim WhatsApp terlebih dahulu

1. Buka browser privat/incognito atau logout dari akun Superadmin.
2. Login sebagai peserta yang kolom `users.phone`-nya berisi nomor WhatsApp aktif.
3. Buka link check-in dari langkah C.
4. Klik **Check-in Sekarang**.
5. Sistem membuka `wa.me` dengan teks konfirmasi tanpa emoji dan tanpa tanda tanya, misalnya:

   ```text
   Halo Tim ALMAI.

   Saya Arjuna ingin mengonfirmasi kehadiran pada acara Seminar Trading Local yang diselenggarakan pada Rabu, 26 Agustus 2026.

   Mohon bantuannya untuk mencatat konfirmasi kehadiran saya.

   Terima kasih.
   ```

6. Pada tahap ini BalesOtomatis belum boleh mengirim balasan.
7. Tekan **Kirim** dari aplikasi WhatsApp pengguna.
8. Setelah webhook menerima pesan, BalesOtomatis membalas menggunakan template event.
9. Hasil yang diharapkan:

   - satu baris baru tersimpan di `absensi_peserta`;
   - satu transaksi `absensi_reward` sebesar `100` tersimpan di `points`;
   - pesan pengguna tampil lebih dahulu di WhatsApp;
   - balasan BalesOtomatis tampil setelah pesan pengguna terkirim;
   - semua placeholder pada balasan sudah berubah menjadi data aktual;
   - tidak ada emoji rusak, karakter `��`, atau tanda tanya pada pesan konfirmasi;
   - kegagalan balasan WhatsApp tidak membatalkan absensi dan poin.

10. Pantau log aplikasi dari PowerShell kedua:

   ```powershell
   cd C:\projek-almaii\almai-new
   Get-Content (Get-ChildItem .\writable\logs\log-*.log | Sort-Object LastWriteTime -Descending | Select-Object -First 1).FullName -Wait
   ```

   Setelah pengguna menekan **Kirim**, log sukses yang dicari:

   ```text
   Balesotomatis Webhook received msg: ...
   Attendance confirmation sent via webhook for check-in ID ...
   ```

   Bila gagal, cari:

   ```text
   Attendance confirmation failed via webhook for check-in ID ...
   ```

### E. Tes validasi flow absensi lama

1. Kembali ke link check-in yang sama menggunakan peserta yang sama.
2. Klik check-in lagi.
3. Sistem harus menolak dengan pesan bahwa peserta sudah melakukan absensi.
4. Pastikan tidak ada tambahan 100 poin dan tidak ada notifikasi WhatsApp kedua.

### F. Tes webhook dan anti-duplikat

Webhook BalesOtomatis hanya bisa memanggil localhost melalui URL HTTPS publik. Gunakan tunnel yang tersedia di komputer, misalnya Cloudflare Tunnel atau ngrok, menuju `http://127.0.0.1:8080`.

1. Jalankan tunnel dan catat URL HTTPS, misalnya:

   ```text
   https://contoh-tunnel.trycloudflare.com
   ```

2. Di dashboard BalesOtomatis, daftarkan endpoint:

   ```text
   https://contoh-tunnel.trycloudflare.com/webhook/balesotomatis
   ```

3. Pilih event pesan masuk `incoming_chat` dan pastikan Number ID yang dikirim pada header `BLS-OTO-NUMBERID` sama dengan `BALESOTOMATIS_WEBHOOK_KEY` di `.env`.
4. Selesaikan check-in, lalu kirim teks konfirmasi yang sudah disiapkan halaman `wa.me`.
5. Webhook harus menerima pesan dan mengirim satu balasan konfirmasi.
6. Kirim ulang teks konfirmasi yang sama dalam waktu 24 jam.
7. Webhook boleh menerima pesan kedua, tetapi tidak boleh mengirim balasan konfirmasi kedua karena check-in sudah ditandai terkirim.
8. Log webhook yang dapat diperiksa:

   ```text
   Balesotomatis Webhook received msg: ...
   ```

   Hanya boleh ada satu log `Attendance confirmation sent via webhook` untuk check-in tersebut.

### G. Tes kegagalan balasan webhook

Gunakan event atau peserta baru agar tidak terkena validasi sudah check-in.

1. Hentikan `php spark serve`.
2. Kosongkan sementara `BALESOTOMATIS_SECRET_KEY` dan `BALESOTOMATIS_LICENSES_KEY`, lalu jalankan kembali server.
3. Check-in pada event baru. Absensi dan poin harus tetap sukses.
4. Kirim teks konfirmasi dari halaman `wa.me`.
5. Webhook menerima pesan, tetapi tidak dapat mengirim balasan karena kredensial kosong. Absensi dan poin tidak boleh dibatalkan.
6. Isi kembali kredensial yang valid dan restart server.
7. Kirim ulang teks konfirmasi dari WhatsApp peserta.
8. Karena pengiriman sebelumnya belum ditandai sukses, webhook akan mencoba lagi dan mengirim satu balasan.
9. Pastikan log berikut muncul:

   ```text
   Attendance confirmation sent via webhook for check-in ID ...
   ```

### H. Tes webhook manual tanpa tunnel

Tes ini memvalidasi parsing webhook lokal. Ganti nomor dan nilai header dengan data local yang sesuai:

```powershell
$body = @{
    type = 'incoming_chat'
    data = @{
        chat_id = '628xxxxxxxxxx@c.us'
        message_body = 'Halo Tim ALMAI. Saya Arjuna ingin mengonfirmasi kehadiran pada acara Event Local. Mohon bantuannya untuk mencatat konfirmasi kehadiran saya. Terima kasih.'
        is_from_me = $false
        message_id = 'local-attendance-test-001'
    }
} | ConvertTo-Json -Depth 5

Invoke-RestMethod `
    -Method Post `
    -Uri 'http://127.0.0.1:8080/webhook/balesotomatis' `
    -Headers @{ 'BLS-OTO-NUMBERID' = 'nilai_BALESOTOMATIS_WEBHOOK_KEY' } `
    -ContentType 'application/json' `
    -Body $body
```

Nomor pada `chat_id` harus cocok dengan peserta yang baru check-in. Jika header salah, respons yang benar adalah HTTP `401 Unauthorized`. Jika payload tidak memiliki `type`, respons yang benar adalah HTTP `400 Invalid payload`.

## Checklist hasil tes

- [ ] QR/link absensi dapat dibuka.
- [ ] Peserta wajib login sebelum check-in.
- [ ] Absensi tercatat satu kali.
- [ ] Peserta memperoleh tepat 100 poin.
- [ ] Placeholder template berubah menjadi data aktual.
- [ ] Redirect `wa.me` menampilkan teks yang sopan tanpa emoji dan tanda tanya.
- [ ] Pengguna mengirim pesan WhatsApp terlebih dahulu.
- [ ] BalesOtomatis baru membalas setelah webhook menerima pesan pengguna.
- [ ] Pesan webhook kedua tidak menghasilkan balasan duplikat.
- [ ] Kegagalan WhatsApp tidak membatalkan absensi atau poin.
