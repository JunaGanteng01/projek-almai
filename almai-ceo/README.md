# ALMAI - CEO Executive Suite Prototype (`almai-ceo`)

Prototype Dashboard Eksekutif khusus untuk **Chief Executive Officer (CEO) & C-Level Management** di **ALMAI Group Nusantara (PT. Alma Indonesia Raya)**.

Dashboard ini dirancang terpisah dan lebih sederhana dari Super Admin dengan fokus pada informasi strategis, kalender, agenda, tagihan, faktur resmi, webinar, dan persetujuan mendesak (*Executive Glancability*).

---

## 📖 Panduan Presentasi Siap Pakai
Untuk mempresentasikan dashboard ini kepada Direksi, Dewan Komisaris, atau Klien, silakan buka dokumen panduan lengkap:
👉 **[`PANDUAN_PRESENTASI.md`](file:///c:/projek-almaii/almai-ceo/PANDUAN_PRESENTASI.md)** *(Naskah presentasi 7 langkah, alur demo, dan cara menjawab pertanyaan)*

---

## 🌟 Fitur Utama

1. **Executive Pulse Strip (Ringkasan 5 Detik)**:
   - 📅 Agenda hari ini & jadwal rapat terdekat
   - ⚡ Permintaan persetujuan C-Level tertunda (dengan total nilai nominal)
   - 💳 Tagihan operasional kritis (< 7 hari jatuh tempo)
   - 🎙️ Jadwal webinar & jumlah peserta terdaftar
2. **Today's Executive Focus**:
   - Hero meeting card dengan tombol instan **"Masuk Rapat"** (Google Meet/Zoom)
   - Checklist prioritas kegiatan harian
3. **Interactive Calendar Engine (Fitur Utama)**:
   - Kalender bulanan dengan filter kategori: *Rapat Direksi, Webinar, Tagihan, Deadline MOU, Event Perusahaan*
   - Panel **Upcoming Agenda** terdekat dalam 7–14 hari ke depan
4. **Tagihan Operasional & Pusat Faktur/Invoice Perusahaan**:
   - Pemantauan tagihan server AWS, Biznet, PLN, Cloudflare, dan WhatsApp gateway
   - Dokumen Faktur Resmi **PT. Alma Indonesia Raya** lengkap dengan PPN 11% dan QR-Code verifikasi
   - **Fitur Cetak Bersih 1 Halaman A4** (bebas error overflow) dan 1-klik bayar/otorisasi
5. **Webinar & Event Publik Hub**:
   - Monitoring kuota pendaftaran peserta dengan visual progress bar
   - Tautan kontrol host room
6. **Approval Center (Antrean Keputusan C-Level)**:
   - Evaluasi proposal penambahan cluster AWS, komisi mentor WPA, diskon kemitraan korporat, dan hiring eksekutif
   - Tombol reaktif *Setujui / Tolak* yang langsung memperbarui badge counter
7. **Floating Quick Actions Toolbar**:
   - `+ Tambah Agenda` (Form tambah agenda baru langsung masuk ke kalender)
   - `📄 Invoice` (Smooth-scroll & highlight ke modul invoice)
   - `💵 Cek Tagihan` (Smooth-scroll & highlight ke tagihan operasional)
   - `✓ Batch Approval` (Modal otorisasi multi-select dan persetujuan massal Rp 138,5 Juta sekaligus)
   - `📥 Laporan` (Generator Laporan Eksekutif dengan opsi Cetak PDF 1 Halaman dan Ekspor Excel `.csv`)
8. **Command Palette (`Ctrl + K`)**:
   - Pencarian kilat untuk agenda, invoice, tagihan, atau persetujuan

---

## 🎨 Spesifikasi Desain Terpadu

- **Aksen Utama**: `#33e818` (Electric Neon Green ALMAI)
- **Latar Belakang**: `#050505` (Obsidian Dark) & `#0C0C0C` (Card Background)
- **Tipografi**: `Montserrat` (Headings & Branding) & `sans-serif`
- **Sistem Kartu**: `.dash-card` & `.dash-card-interactive`

---

## 📂 Struktur File

```
almai-ceo/
├── index.html              # Antarmuka Dashboard Eksekutif & Invoice Hub
├── css/
│   └── ceo-style.css       # Styling terpadu #33e818 & Obsidian Dark
├── js/
│   ├── ceo-data.js         # Master data agenda, tagihan, invoice & approval
│   ├── ceo-calendar.js     # Engine kalender interaktif bulanan & filter
│   └── ceo-app.js          # Controller aplikasi, invoice modal, quick actions, dan reactive state
├── PANDUAN_PRESENTASI.md   # Naskah & panduan alur presentasi lengkap
├── design.md               # Dokumentasi spesifikasi arsitektur desain
└── README.md               # Dokumentasi ringkas sistem
```

---

## 🚀 Cara Menjalankan Prototype

### Opsi 1: Server Lokal yang Sedang Aktif
Prototype saat ini aktif berjalan di port **8090**:
👉 **[http://localhost:8090](http://localhost:8090)**

### Opsi 2: Buka File Langsung
Klik ganda file [`c:\projek-almaii\almai-ceo\index.html`](file:///c:/projek-almaii/almai-ceo/index.html) di peramban (Chrome / Edge / Firefox).

### Opsi 3: Terminal PowerShell
```powershell
cd c:\projek-almaii\almai-ceo
php -S localhost:8090
```

---

*© 2026 PT. Alma Indonesia Raya (ALMAI) — Executive Suite Architecture.*
