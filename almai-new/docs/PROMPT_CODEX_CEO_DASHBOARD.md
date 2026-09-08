# Prompt Codex — Integrasi CEO Executive Dashboard ALMAI

Salin seluruh prompt di bawah ini ke task Codex baru pada proyek `C:\projek-almaii`.

---

Anda adalah senior full-stack engineer dan system analyst. Kerjakan langsung sampai tuntas pada proyek ALMAI yang sudah ada; jangan hanya membuat desain, mockup, atau rencana.

## Konteks proyek

- Aplikasi utama: `C:\projek-almaii\almai-new`
- Referensi UI/prototipe CEO: `C:\projek-almaii\almai-ceo`
- Stack utama: PHP 8.1+, CodeIgniter 4, MySQL, JavaScript, Tailwind/CSS yang sudah digunakan proyek.
- Pertahankan identitas visual ALMAI: latar obsidian, kartu gelap, aksen hijau `#33e818`, tipografi dan komponen yang konsisten dengan aplikasi utama.
- Prototipe `almai-ceo` hanya referensi UX. Jangan menyalin data demo/hardcoded ke implementasi produksi.
- Modul `/ea` yang sudah ada adalah fondasi terkait executive assistant/CEO dan harus diaudit sebelum membuat modul duplikat.

## Tujuan utama

Bangun dan integrasikan **CEO Executive Dashboard** yang menggunakan data nyata dari seluruh modul ALMAI. Semua kartu, grafik, filter, kalender, tombol, modal, notifikasi, approval, ekspor, dan drill-down harus berfungsi end-to-end, aman, responsif, serta terhubung ke sumber data yang benar.

Dashboard harus membantu CEO menjawab dalam kurang dari 10 detik:

1. Bagaimana kondisi pendapatan, kas, pertumbuhan pengguna, dan operasional saat ini?
2. Keputusan apa yang harus saya ambil hari ini?
3. Agenda, tagihan, deadline, webinar, dan risiko apa yang paling mendesak?
4. Bagian mana yang memburuk dibanding periode sebelumnya?
5. Siapa penanggung jawab dan apa tindak lanjutnya?

## Cara kerja wajib

1. Audit terlebih dahulu struktur proyek, `Routes.php`, filter autentikasi, `LevelModel`, controller/view `/ea`, model, migration, seeder, serta skema database yang tersedia. Gunakan implementasi yang sudah ada jika layak; jangan membuat fitur paralel yang tumpang tindih.
2. Tulis ringkasan audit singkat sebelum implementasi: komponen yang dapat dipakai ulang, bug/inconsistency, tabel yang ada, tabel yang belum memiliki migration, dan perubahan yang akan dibuat.
3. Lanjutkan implementasi langsung tanpa berhenti setelah audit.
4. Pertahankan perubahan pengguna yang tidak terkait. Jangan melakukan reset, menghapus data, atau mengubah kredensial/konfigurasi produksi.
5. Jangan mengarang angka. Jika sumber data belum tersedia, tampilkan empty state yang jujur dan buat migration/model yang memang diperlukan.
6. Jangan menggunakan query SQL mentah jika Query Builder/model CI4 dapat digunakan dengan aman. Hindari N+1 query dan hitung KPI secara efisien.
7. Setelah selesai, jalankan migration/test/lint atau pemeriksaan setara yang tersedia, lalu laporkan hasilnya dengan jujur.

## Temuan awal yang wajib diverifikasi dan diperbaiki

- `CeoSeeder` membuat level CEO dengan ID `10`, dan `App\Controllers\Ea\Auth` memanggil `LevelModel::LEVEL_CEO`, tetapi `App\Models\LevelModel` saat ini belum mendefinisikan `LEVEL_CEO` beserta mapping dashboard, nama role, badge, string role, dan izin. Jadikan `LevelModel` single source of truth.
- Route `/ea` tampak didefinisikan lebih dari sekali. Rapikan tanpa memutus URL lama.
- Model `EaApprovalModel`, `EaMeetingModel`, `EaTaskModel`, dan `EaReminderModel` sudah ada. Pastikan migration tabelnya tersedia dan skemanya cocok dengan `allowedFields`.
- `Ea\Dashboard` masih memiliki nilai dummy seperti `active_projects` dan `pending_tasks`. Hilangkan seluruh KPI dummy dari jalur produksi.
- Sejumlah aksi delete lama masih memakai GET. Jangan meniru pola tersebut untuk modul CEO; aksi mutasi wajib memakai POST/PATCH/DELETE, CSRF, validasi server, dan otorisasi.

## Struktur akses dan routing

- Sediakan route utama `/ceo/dashboard` dengan filter khusus CEO.
- Pertahankan kompatibilitas URL `/ea` yang masih digunakan. Tentukan secara aman apakah `/ea` tetap menjadi area Executive Assistant atau redirect ke route CEO yang sesuai; jangan membuat dua sumber data/dashboard yang berbeda.
- Hanya user CEO level 10 yang dapat membuka halaman dan endpoint CEO. Superadmin tidak otomatis boleh melakukan aksi CEO kecuali ada kebijakan eksplisit yang tercatat.
- Tambahkan `LEVEL_CEO = 10` dan seluruh mapping terkait ke `LevelModel`, termasuk dashboard path, role name, badge, konversi legacy `ceo`, dan helper permission.
- Pastikan login melakukan regenerasi session ID dan logout membersihkan session dengan benar.
- Tambahkan pengujian akses: guest ditolak, role non-CEO ditolak, CEO diterima.

## Arsitektur yang diharapkan

Buat pemisahan tanggung jawab yang jelas, misalnya:

- `Ceo\Dashboard` untuk halaman dan endpoint read-only.
- `CeoDashboardService` untuk agregasi KPI lintas modul.
- `ExecutiveCalendarService` untuk menyatukan agenda dari meeting, event, tagihan, task, reminder, dan deadline.
- `ExecutiveApprovalService` untuk workflow keputusan dan transaksi database.
- Model/migration khusus hanya jika data belum terwakili oleh tabel yang ada.
- Endpoint JSON di bawah `/ceo/api/...` untuk filter periode, grafik, kalender, detail, dan aksi.

Controller harus tipis. Logika bisnis, definisi KPI, perubahan status, notifikasi, dan audit trail harus berada di service/model yang dapat diuji.

## Modul dan fungsi yang harus dibuat

### 1. Executive Pulse / ringkasan utama

Gunakan data nyata dan sertakan pembanding periode sebelumnya:

- Pendapatan hari ini, MTD, YTD, dan growth versus periode sebelumnya dari transaksi berstatus `confirmed`.
- Jumlah transaksi paid/confirmed, pending, expired, failed/cancelled.
- Arus kas dan saldo akun kas/bank dari `akun` dan `jurnal` jika modul akuntansi tersedia.
- Pengeluaran/tagihan yang jatuh tempo dan total nominalnya.
- Pengguna baru, user aktif bila indikator aktivitas tersedia, total WPA, CWPA, User Pro, dan konversi pengguna ke pembeli.
- Withdrawal pending/approved serta nominal yang membutuhkan perhatian.
- Approval pending, urgent, overdue, dan total nominal.
- Webinar/event terdekat, pendaftar, kapasitas, occupancy rate, serta pendapatan event.
- Ringkasan CRM: conversation open, unassigned, overdue/SLA breach, conversion, dan response time bila datanya tersedia.

Setiap KPI harus memiliki tooltip definisi, periode data, status terakhir diperbarui, dan dapat diklik untuk membuka drill-down dengan filter yang sama.

### 2. Grafik kinerja

- Tren revenue, transaksi, user growth, conversion rate, dan revenue per kategori.
- Filter periode: hari ini, 7 hari, 30 hari, bulan berjalan, kuartal, tahun, serta rentang tanggal khusus.
- Perbandingan dengan periode sebelumnya.
- Semua filter harus memperbarui kartu, grafik, dan tabel yang relevan tanpa reload penuh jika memungkinkan.
- Tangani timezone `Asia/Singapore`/zona bisnis yang dikonfigurasi secara konsisten dan hindari perhitungan tanggal berbasis string SQL yang tidak portable bila ada alternatif.

### 3. Executive Calendar terpadu

Satukan dalam satu kalender:

- `ea_meetings`
- `layanan_event`
- tagihan dan jatuh tempo invoice
- `ea_tasks`
- `ea_reminders`
- approval deadline
- agenda/deadline perusahaan lain yang memang memiliki sumber data

Fitur kalender:

- tampilan bulan dan daftar agenda terdekat;
- filter kategori;
- detail tanggal dalam bottom sheet/modal;
- tambah/edit agenda, meeting, task, dan reminder sesuai izin;
- tombol masuk Zoom/Google Meet hanya jika URL tervalidasi;
- deteksi bentrok jadwal;
- reminder H-1 dan sebelum acara;
- perubahan agenda membuat notification dan audit log.

### 4. Approval Center

Gunakan atau perluas `ea_approvals` secara aman:

- status minimal: draft, submitted, pending, approved, rejected, cancelled, expired;
- requester, approver, module/source, reference ID, amount, priority, due date, reason, attachment, timestamps;
- detail lengkap sebelum CEO mengambil keputusan;
- approve/reject wajib POST/PATCH, CSRF, validasi, komentar/alasan, database transaction, optimistic locking atau pengecekan status terbaru, idempotency, audit log, dan notification;
- batch approval hanya untuk item yang eligible dan harus menampilkan ringkasan total serta konfirmasi;
- keputusan approval harus memperbarui modul sumber secara atomik bila memang ada aksi bisnis yang terkait;
- approval tagihan berarti otorisasi internal, bukan otomatis mengirim pembayaran nyata kecuali integrasi payment resmi memang tersedia dan secara eksplisit dikonfirmasi.

### 5. Keuangan, invoice, dan tagihan

Hubungkan dengan `transaksi`, `CustomerInvoiceModel`/modul invoice, `withdrawals`, `akun`, dan `jurnal` yang tersedia:

- receivable dan payable dipisahkan jelas;
- daftar invoice dengan status, pihak, tanggal, jatuh tempo, subtotal, pajak, total, dan dokumen;
- tagihan overdue dan due soon;
- detail invoice, cetak/unduh PDF menggunakan Dompdf yang sudah ada;
- ekspor CSV/XLSX menggunakan PhpSpreadsheet yang sudah ada;
- jangan hardcode PPN 11%; ambil dari konfigurasi/tanggal berlaku dan simpan snapshot tarif pada invoice;
- rekonsiliasi status transaksi dan invoice dengan sumber data tunggal;
- jangan pernah mengekspos nomor rekening, bukti transfer, atau PII penuh di ringkasan tanpa kebutuhan.

### 6. Webinar dan event

Gunakan `layanan_event` dan transaksi terkait:

- event mendatang, status publikasi, kapasitas, jumlah peserta aktual, occupancy, revenue, refund/cancel bila tersedia;
- drill-down peserta dan attendance hanya untuk user berizin;
- tombol host room tervalidasi dan tidak ditampilkan kepada role lain;
- perubahan jadwal event otomatis muncul di kalender dan notification;
- jangan hanya mempercayai `current_participants` jika dapat dihitung dari transaksi/registrasi; tetapkan satu sumber kebenaran dan dokumentasikan.

### 7. CRM executive snapshot

Gunakan service/repository CRM yang sudah ada, bukan menggandakan query:

- open conversation, unassigned, waiting customer, waiting agent, closed;
- SLA breach dan conversation tertua;
- lead-to-paid conversion bila relasi data memungkinkan;
- daftar kasus kritis dengan link ke detail CRM;
- dashboard CEO bersifat ringkas; operasi percakapan tetap dilakukan di modul CRM.

### 8. Strategic Goals / OKR

Tambahkan modul ringan untuk sasaran kuartalan:

- objective, owner, periode, target, actual, unit, progress, confidence, status, dan update note;
- progress dapat manual atau bersumber dari KPI yang dipilih;
- tampilkan target yang off-track dan overdue di Executive Pulse;
- semua perubahan memiliki audit trail.

### 9. Risk & Alert Center

Buat aturan alert yang dapat dijelaskan, bukan AI yang mengarang:

- revenue turun melewati ambang;
- tagihan/approval overdue;
- event occupancy rendah mendekati tanggal;
- withdrawal pending terlalu lama;
- CRM SLA breach melonjak;
- saldo kas di bawah threshold jika datanya valid.

Setiap alert harus menunjukkan alasan, nilai aktual, threshold, sumber data, waktu evaluasi, severity, owner, dan link tindakan.

### 10. CEO Daily Briefing

- Buat ringkasan deterministik dari KPI dan alert terlebih dahulu.
- Bila integrasi AI memang sudah dikonfigurasi, AI hanya merangkum payload data terstruktur yang diberikan; jangan mengirim PII atau membuat angka baru.
- Tampilkan sumber angka dan waktu pembaruan.
- Jika AI gagal/tidak dikonfigurasi, fallback deterministik tetap berfungsi.

### 11. Notifikasi, laporan, dan pencarian

- Notification center terpadu untuk approval, agenda, tagihan, event, risiko, dan tugas.
- Mark as read/read all harus berfungsi dan dibatasi ke pemilik notifikasi.
- Command palette `Ctrl+K` mencari agenda, approval, invoice, event, dan menu, lalu menuju detail nyata.
- Laporan eksekutif PDF dan XLSX mengikuti filter periode yang aktif dan mencantumkan waktu generate.
- Sediakan scheduled data refresh/cron hanya jika diperlukan; dokumentasikan command dan mekanismenya, jangan mengandalkan request halaman untuk pekerjaan berat.

## Keterhubungan fitur yang wajib

Implementasikan alur berikut secara nyata:

1. Meeting/event/task/reminder baru → muncul di kalender → membuat notifikasi kepada pihak terkait → tercatat di audit log.
2. Transaksi berubah menjadi confirmed → masuk ke revenue → memperbarui metrik event/produk yang benar → invoice dapat dibuka → dashboard memperlihatkan hasil setelah refresh.
3. Tagihan diajukan → approval CEO dibuat → keputusan CEO memperbarui approval dan status tagihan secara atomik → requester menerima notifikasi → audit log menyimpan before/after.
4. Withdrawal bernilai/berumur di atas threshold → tampil sebagai alert/approval → keputusan tetap mengikuti workflow withdrawal yang sudah ada.
5. Klik KPI atau alert → membuka daftar detail dengan filter periode/status/sumber yang konsisten, bukan halaman generik.
6. Perubahan filter periode → Executive Pulse, grafik, tabel, dan export menggunakan parameter serta definisi data yang sama.

## UX dan tampilan

- Jadikan `almai-ceo` referensi visual, tetapi implementasikan sebagai view/layout aplikasi utama yang dapat dipelihara.
- Prioritaskan executive glancability: angka utama, perubahan, sebab, dan aksi.
- Responsif desktop/tablet/mobile, sidebar mobile berfungsi, keyboard accessible, focus state terlihat, label/ARIA tepat, warna tidak menjadi satu-satunya indikator.
- Gunakan loading state, skeleton secukupnya, empty state, error state, toast, dan konfirmasi untuk aksi penting.
- Format rupiah dan tanggal Indonesia secara konsisten.
- Jangan memenuhi halaman dengan terlalu banyak kartu; detail ditempatkan di drill-down.
- Tidak boleh ada tombol mati, link `#` tanpa fungsi, data hardcoded, modal palsu, atau aksi yang hanya mengubah DOM tanpa tersimpan di server.

## Keamanan dan integritas data

- Terapkan autentikasi/otorisasi server-side pada setiap route dan endpoint, bukan hanya menyembunyikan tombol.
- CSRF untuk semua mutasi, validasi input CI4, escaping output, whitelist filter/sort, rate limiting untuk aksi sensitif, dan upload attachment yang aman.
- Gunakan database transaction untuk approval dan perubahan lintas tabel.
- Catat audit: actor, action, entity type/id, nilai sebelum/sesudah yang aman, IP, user agent, timestamp, dan correlation/request ID bila memungkinkan.
- Hindari aksi finansial irreversible. Dashboard hanya mengotorisasi/mengubah status sesuai workflow yang disepakati.
- Jangan menaruh secret, token, atau konfigurasi produksi di kode maupun hasil laporan.

## Performa

- Target halaman awal tetap cepat dengan agregasi efisien dan indeks database untuk kolom status/tanggal/foreign key yang sering difilter.
- Gunakan cache singkat untuk agregat read-only jika perlu, dengan invalidasi yang jelas setelah mutasi.
- Batasi dan paginasi tabel detail.
- Hindari memuat seluruh transaksi ke PHP hanya untuk agregasi; lakukan agregasi di database bila aman.

## Pengujian dan definition of done

Tambahkan pengujian minimal untuk:

- akses guest/non-CEO/CEO;
- definisi revenue dan pembanding periode;
- filter tanggal dan timezone;
- agregasi kalender lintas sumber;
- approve/reject tunggal dan batch, termasuk stale state/double-submit;
- sinkronisasi status approval dengan modul sumber;
- notification ownership;
- ekspor mengikuti filter;
- empty state serta data besar/pagination;
- endpoint menolak CSRF/input tidak valid.

Pekerjaan baru dianggap selesai jika:

- tidak ada data dummy di jalur produksi;
- tidak ada error konstanta `LEVEL_CEO`, route ganda bermasalah, tabel hilang, atau tombol mati;
- semua KPI mempunyai definisi dan sumber data yang terdokumentasi;
- seluruh alur keterhubungan di atas berhasil diuji;
- tampilan responsif dan console browser bebas error;
- migration memiliki `up()` dan `down()` yang aman;
- README/dokumen deployment menjelaskan migration, seeder CEO tanpa password hardcoded, command cron bila ada, dan langkah rollback;
- berikan daftar file yang diubah, keputusan teknis, hasil test, serta risiko/sisa pekerjaan nyata.

## Urutan implementasi

Kerjakan bertahap agar selalu ada hasil yang dapat diuji:

1. audit dan perbaikan role/auth/route/migration;
2. service agregasi dan endpoint read-only memakai data nyata;
3. dashboard Executive Pulse, grafik, drill-down, dan filter;
4. kalender terpadu;
5. approval + tagihan + audit + notifikasi;
6. event dan CRM snapshot;
7. OKR, risk alert, briefing, command palette, export;
8. security review, test, visual QA responsif, dokumentasi.

Mulai sekarang dengan membaca file proyek yang relevan. Setelah audit singkat, langsung implementasikan fase demi fase sampai semua acceptance criteria terpenuhi. Jangan berhenti pada rekomendasi atau pseudocode.

---

## Saran prioritas produk

Jika waktu perlu dibagi, gunakan urutan nilai bisnis berikut:

1. **P0 — Keamanan akses CEO dan data nyata:** perbaiki role level 10, filter route, migration, dan hapus angka dummy.
2. **P0 — Executive Pulse + drill-down:** revenue, kas, transaksi, approval, withdrawal, event, dan CRM dengan definisi konsisten.
3. **P0 — Approval + audit trail:** karena ini menyangkut keputusan dan nominal perusahaan.
4. **P1 — Kalender terpadu + notifikasi:** menjadikan dashboard alat kerja harian, bukan sekadar laporan.
5. **P1 — Risk Alert Center:** CEO melihat pengecualian dan masalah, bukan seluruh data mentah.
6. **P1 — OKR/target versus aktual:** menghubungkan aktivitas harian dengan strategi kuartalan.
7. **P2 — Daily Briefing berbantuan AI:** aktifkan setelah kualitas sumber data dan definisi KPI stabil.
8. **P2 — What-if scenario:** simulasi target revenue, biaya, conversion, dan kapasitas event tanpa mengubah data produksi.

