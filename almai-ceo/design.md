# ALMAI Executive Suite - Design Specification (`design.md`)

Dokumen spesifikasi desain (*Design Specification*) untuk **ALMAI CEO Executive Suite** (`almai-ceo`) yang diselaraskan secara penuh dengan **Platform ALMAI** (`almai-new`).

---

## 1. Keselarasan Desain (*Design Alignment*)

Platform **CEO Executive Suite** (`almai-ceo`) menggunakan sistem desain terpadu (*Unified Design System*) dari `almai-new` dengan penyempurnaan khusus untuk tingkat pengambil keputusan puncak (*C-Level Executive*):

### Identitas Visual Bersama (*Shared Identity*):
- **Background Utama**: `#050505` (Obsidian Dark) & `#0C0C0C` (Card Background).
- **Brand Accent**: `#33E818` (Electric Neon Green) — Konsisten di seluruh ekosistem ALMAI.
- **Tipografi**: `Montserrat` (Headings, Branding, Navigasi) & `sans-serif`.
- **Sistem Kartu**: `.dash-card` dengan border halus `rgba(255, 255, 255, 0.08)` dan `.dash-card-interactive` dengan efek glow `#33E818`.
- **Sidebar Active Style**: Indikator garis tepi kiri `border-left: 3px solid #33e818` dengan efek drop-shadow ikon menyala.

---

## 2. Prioritas Alur Informasi (*Executive Flow*)

$$\Large \textbf{Today} \longrightarrow \textbf{Upcoming} \longrightarrow \textbf{Calendar} \longrightarrow \textbf{Bills} \longrightarrow \textbf{Invoices} \longrightarrow \textbf{Webinar} \longrightarrow \textbf{Approval} \longrightarrow \textbf{Reports}$$

1. **Top Pulse Strip (Ringkasan 5 Detik)**:
   - 📅 **Agenda Hari Ini**: 3 Agenda *(Rapat berikutnya: 14:00 WIB)*
   - ⚡ **Persetujuan Menunggu**: 4 Keputusan *(Total Rp 138,5 Juta)*
   - 💳 **Tagihan < 7 Hari**: 3 Tagihan *(Total Rp 28,45 Juta)*
   - 🎙️ **Webinar Terdekat**: 2 Event *(1.420 Peserta Terdaftar)*
2. **Today's Executive Focus**: Hero meeting box dengan tombol instan **"Masuk Rapat"** (Google Meet/Zoom) dan checklist prioritas harian.
3. **Calendar & Upcoming Agenda**: Kalender bulanan interaktif dengan filter kategori instan (*Rapat Direksi, Webinar, Tagihan, Deadline MOU, Event*) dan sidebar agenda terdekat.
4. **Tagihan Operasional (Operational Bills)**: Monitoring biaya server AWS, internet Biznet, listrik PLN, Cloudflare, dan WABA dengan tombol cepat *Bayar / Review*.
5. **Pusat Faktur & Invoice Perusahaan (Invoices Hub)**:
   - Manajemen invoice resmi **PT. Alma Indonesia Raya**:
     - *Tagihan Masuk Vendor (Payable)*: AWS, Biznet, Cloudflare, Ivosights.
     - *Faktur Kemitraan Keluar (Receivable)*: Lisensi Korporat Mandiri Sekuritas, Indo Premier Trade Engine.
   - Fitur modal invoice resmi: Rincian item, PPN 11%, Subtotal, Grand Total, Verifikasi QR Seal, Cetak / Unduh PDF, dan 1-klik *Otorisasi & Bayar*.
6. **Webinar Hub**: Jadwal tayang, target kuota pendaftar, dan link kontrol host live session.
7. **Approval Center**: Antrean keputusan C-Level (*Cluster Server, Komisi Mentor, Diskon Korporat, Hiring*) dengan tombol reaktif *Setujui / Tolak* yang langsung memperbarui badge counter.
8. **Executive Quick Actions Toolbar**: Toolbar melayang (`+ Tambah Agenda`, `📄 Invoice`, `💳 Cek Tagihan`, `⚡ Batch Approval`, `📊 Laporan`).
9. **Command Palette (`Ctrl + K`)**: Pencarian instan untuk melompat langsung ke agenda, no invoice, vendor tagihan, atau permintaan persetujuan.

---

## 3. Struktur File Proyek (`almai-ceo`)

```
almai-ceo/
├── index.html              # Antarmuka Dashboard Eksekutif & Invoice Hub
├── css/
│   └── ceo-style.css       # Styling terpadu dengan warna #33e818 & Obsidian Dark
├── js/
│   ├── ceo-data.js         # Master data agenda, tagihan, & invoice resmi
│   ├── ceo-calendar.js     # Engine kalender interaktif bulanan & filter
│   └── ceo-app.js          # Controller aplikasi, invoice modal, dan reactive state
├── design.md               # Dokumentasi spesifikasi desain
└── README.md               # Panduan teknis & cara menjalankan prototype
```

---

*© 2026 PT. Alma Indonesia Raya (ALMAI) — Executive Suite Architecture.*
