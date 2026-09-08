# 🎙️ Panduan & Naskah Presentasi: ALMAI CEO Executive Suite (Calendar-First Experience)

Dokumen ini berisi panduan lengkap, alur demonstrasi interaktif (*interactive demo flow*), dan naskah presentasi (*presentation script*) siap pakai untuk mempresentasikan **ALMAI CEO Executive Suite (`almai-ceo`)** dengan paradigma baru **Calendar-First Dashboard** di hadapan Dewan Direksi, Dewan Komisaris, Stakeholder, atau Klien.

---

## 📋 Ringkasan Cepat Persiapan Demo

| Parameter | Keterangan |
| :--- | :--- |
| **URL Demo Prototype** | [http://localhost:8090](http://localhost:8090) |
| **Durasi Ideal** | 5 – 8 Menit |
| **Mode Tampilan** | Tekan `F11` pada peramban untuk mode Layar Penuh (*Fullscreen*) |
| **Shortcut Utama** | Tekan `Ctrl + K` untuk demo pencarian kilat (*Command Palette*) |
| **Konsep Utama** | **Calendar-First Architecture**: Kalender sebagai satu-satunya dashboard komando eksekutif tanpa navigasi berlapis |
| **Palet Desain** | `#33e818` (Electric Neon Green), `#3b82f6` (Electric Blue), `#f43f5e` (Rose Red), `#050505` (Obsidian Dark) |

---

## 🔄 Alur Demonstrasi 5 Langkah (*The 5-Step Calendar Demo Flow*)

$$\Large \textbf{1. Overview Kalender} \longrightarrow \textbf{2. 3 Indikator Harian} \longrightarrow \textbf{3. Day Detail Sheet} \longrightarrow \textbf{4. Otorisasi \& Auto-Done} \longrightarrow \textbf{5. Empty State \& Batch Tools}$$

---

## 📜 Naskah Presentasi Lengkap (Siap Dibaca / Dipresentasikan)

### 1. Pembukaan (Opening & Filosofi Calendar-First)
> *"Selamat pagi/siang Bapak/Ibu Dewan Direksi dan Rekan-rekan sekalian. Hari ini saya mempersembahkan inovasi terbaru pada **ALMAI CEO Executive Suite**.*
>
> *Bagi seorang Chief Executive Officer, waktu adalah aset paling berharga. Kami menyadari bahwa navigasi antar-halaman yang rumit dan banyaknya kartu-kartu terpisah sering kali memperlambat pengambilan keputusan.*
>
> *Oleh karena itu, kami merancang ulang antarmuka ini dengan pendekatan **Calendar-First Experience** — di mana kalender eksekutif menjadi **satu-satunya dashboard utama** yang merangkum seluruh denyut nadi perusahaan: mulai dari rapat dewan direksi, persetujuan strategis, notifikasi mendesak, webinar akbar, hingga tagihan operasional.*
>
> *Desainnya mengusung gaya **Obsidian Dark Minimalist** dengan aksen **Electric Neon Green (`#33e818`)** yang harmonis dengan ekosistem platform ALMAI."*

---

### 2. Langkah 1: Kalender Eksekutif & 3 Indikator Status Terpadu
*(Tunjukkan tampilan kalender bulanan dan arahkan kursor ke tanggal yang memiliki indikator di pojok kanan atas sel)*

> *"Mari kita perhatikan tampilan kalender di layar utama. Setiap kotak tanggal bukan sekadar penunjuk hari, melainkan ringkasan aktivitas harian yang sangat intuitif melalui **3 Indikator Status Cepat**:*
>
> 1. 🟢 **Green ✓ (Checkmark)**: Menandakan notifikasi atau agenda yang **sudah disetujui / dibaca (Done)**.
> 2. 🔵 **Blue Number (Angka Biru)**: Menunjukkan jumlah **persetujuan C-Level yang telah diselesaikan** pada hari tersebut.
> 3. 🔴 **Red Dot / Number (Titik Merah Berkedip)**: Menandakan adanya **notifikasi baru atau proposal yang masih menunggu tindakan eksekutif (Unread/Pending)**.
>
> *Di bawah kalender, kami menyematkan **Legend Bar** yang menjelaskan arti dari ketiga indikator tersebut sehingga siapapun yang melihat layar langsung memahaminya dalam 3 detik."*

---

### 3. Langkah 2: Interaksi Tanggal Hari Ini (Bottom Sheet / Day Detail Modal)
*(Klik pada tanggal hari ini: **22 Agustus 2026**)*

> *"Ketika CEO ingin menindaklanjuti agenda pada tanggal tertentu, **cukup klik tanggal tersebut**.*
>
> *Sistem tidak akan memindahkan kita ke halaman lain yang memakan waktu loading, melainkan langsung membuka **Day Details Panel** yang responsif (Bottom Sheet di mobile dan Modal Eksekutif di desktop).*
>
> *Di bagian atas panel, kita dapat langsung melihat ringkasan harian: berapa yang selesai, berapa yang disetujui, dan berapa yang perlu tindakan.*
>
> *Tersedia pula tab filter instan: **Semua**, **Persetujuan**, **Notifikasi**, **Agenda & Rapat**, dan **Tagihan & Invoice**."*

---

### 4. Langkah 3: Otorisasi 1-Klik & Mekanisme Otomatis Selesai (*Auto-Mark Done*)
*(Klik tombol hijau **'Setujui Proposal'** pada item persetujuan penambahan server cluster AWS, lalu klik **'Tandai Selesai'** pada notifikasi)*

> *"Salah satu keunggulan terbesar sistem ini adalah **Reaktivitas Instan**:*
>
> - *Lihat pengajuan penambahan cluster AWS senilai Rp 45 Juta dari CTO. CEO cukup menekan **'Setujui Proposal'**.*
> - *Proposal langsung berstatus disetujui, dan sistem **secara otomatis menandai notifikasi terkait sebagai Selesai (Done)**.*
> - *Begitu juga pada notifikasi yang belum dibaca: saat dibuka atau ditekan 'Tandai Selesai', indikator merah otomatis berubah menjadi **Green ✓** secara real-time di kalender!*
> - *Jika ada rapat virtual, CEO cukup mengklik tombol biru **'Masuk Rapat'** untuk langsung melompat ke Google Meet atau Zoom."*

*(Tutup panel detail dan tunjukkan kotak tanggal 22 di kalender yang indikatornya langsung ter-update)*

---

### 5. Langkah 4: Demonstrasi Empty State yang Bersih
*(Klik tanggal kosong yang tidak memiliki aktivitas, contohnya tanggal **18** atau **19 Agustus 2026**)*

> *"Bagaimana jika pada tanggal tertentu tidak ada jadwal atau tagihan?*
>
> *Sistem kami menampilkan **Empty State** yang elegan dan informatif. Tidak ada data yang membingungkan — hanya pesan bersih bahwa tanggal ini bebas aktivitas, dilengkapi tombol cepat `+ Tambah Agenda untuk Tanggal Ini` jika CEO ingin menjadwalkan kegiatan baru."*

*(Tutup modal kembali)*

---

### 6. Langkah 5: Fitur Tambahan & Bilah Aksi Cepat (*Quick Actions*)
*(Demokan bilah melayang di bawah dan shortcut keyboard)*

> *"Untuk mendukung fleksibilitas eksekutif, kami menyediakan fitur pendukung:*
>
> 1. **Floating Quick Actions Bar**:
>    - `+ Tambah Agenda`: Form cepat untuk menambahkan agenda ke tanggal kalender.
>    - `⚡ Batch Approval`: Otorisasi massal — menyetujui seluruh proposal terverifikasi sekaligus dalam 1 klik.
>    - `📄 Faktur Resmi`: Membuka dokumen faktur resmi PT. Alma Indonesia Raya lengkap dengan PPN 11% dan verifikasi QR.
>    - `📊 Laporan Eksekutif`: Generator laporan dengan opsi cetak bersih 1 halaman PDF atau ekspor Excel (.csv).
> 2. **Command Palette (`Ctrl + K`)**:
>    *(Tekan `Ctrl + K` dan ketik 'AWS' atau 'Mandiri')*
>    *Pencarian kilat berbasis keyboard untuk melompat langsung ke agenda, no. faktur, atau nama pejabat pengaju proposal.*
> 3. **Mode Tampilan**: Dilengkapi toggle tema Dark Obsidian dan Light Clean sesuai preferensi kenyamanan mata."*

---

### 🏆 7. Penutup (Closing Statement)

> *"Bapak/Ibu sekalian, dengan **ALMAI CEO Calendar-First Suite**, kita berhasil menyatukan seluruh tata kelola eksekutif ke dalam satu layar yang ringkas, modern, dan sangat cepat dioperasikan:*
>
> 1. **Zero Clutter**: Tanpa halaman terpisah yang membingungkan.
> 2. **Full Visibility**: Memantau meeting, tagihan, persetujuan, dan notifikasi dalam satu kalender terpadu.
> 3. **Action-Oriented**: Keputusan strategis dapat diselesaikan langsung di tempat (*1-click approval*).
>
> *Terima kasih atas perhatian Bapak/Ibu sekalian. Saya membuka sesi tanya jawab untuk masukan dan diskusi lebih lanjut."*

---

## 💡 Panduan Menjawab Pertanyaan Dewan (*Q&A Prep*)

| Pertanyaan yang Sering Diajukan | Jawaban yang Direkomendasikan |
| :--- | :--- |
| **"Mengapa dashboard ini diubah menjadi Calendar-First?"** | *"Berdasarkan audit alur kerja eksekutif, CEO lebih sering merencanakan waktu dan mengevaluasi prioritas berdasarkan tanggal. Mengintegrasikan notifikasi, approval, dan rapat langsung ke tanggal kalender memangkas waktu pencarian informasi hingga 70%."* |
| **"Bagaimana jika ada proposal dengan nominal sangat besar?"** | *"Di dalam Day Detail Sheet maupun Invoice Modal, seluruh rincian anggaran, lampiran PDF, dan departemen pengaju tercantum lengkap sebelum CEO memutuskan untuk menyetujui atau menolak."* |
| **"Apakah tampilan ini nyaman dibuka dari tablet atau smartphone?"** | *"Sangat responsif. Pada perangkat mobile, panel rincian harian otomatis bertransformasi menjadi Bottom Sheet modern yang dapat digeser naik-turun dengan mudah."* |

---

*© 2026 PT. Alma Indonesia Raya (ALMAI) — Executive Suite Presentation Guide.*
