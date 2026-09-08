<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<style>
    /* ── Animated gradient header ── */
    .roadmap-hero {
        background: linear-gradient(135deg, #0d0d0d 0%, #111827 50%, #0d0d0d 100%);
        position: relative;
        overflow: hidden;
    }
    .roadmap-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 20% 50%, rgba(51,232,24,0.06) 0%, transparent 60%),
                    radial-gradient(ellipse at 80% 20%, rgba(99,102,241,0.07) 0%, transparent 60%),
                    radial-gradient(ellipse at 60% 80%, rgba(251,191,36,0.05) 0%, transparent 60%);
        pointer-events: none;
    }

    /* ── Version card glow effects ── */
    .card-v1 { border-color: rgba(51,232,24,0.25); }
    .card-v1:hover { border-color: rgba(51,232,24,0.5); box-shadow: 0 0 40px rgba(51,232,24,0.08); }
    .card-v2 { border-color: rgba(99,102,241,0.25); }
    .card-v2:hover { border-color: rgba(99,102,241,0.5); box-shadow: 0 0 40px rgba(99,102,241,0.08); }
    .card-v3 { border-color: rgba(251,191,36,0.25); }
    .card-v3:hover { border-color: rgba(251,191,36,0.5); box-shadow: 0 0 40px rgba(251,191,36,0.08); }

    /* ── Badge pill ── */
    .badge-v1 { background: rgba(51,232,24,0.12); color: #33e818; border: 1px solid rgba(51,232,24,0.25); }
    .badge-v2 { background: rgba(99,102,241,0.12); color: #818cf8; border: 1px solid rgba(99,102,241,0.25); }
    .badge-v3 { background: rgba(251,191,36,0.12); color: #fbbf24; border: 1px solid rgba(251,191,36,0.25); }

    /* ── Checklist item ── */
    .check-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        transition: all 0.2s ease;
    }
    .check-item:last-child { border-bottom: none; }
    .check-item:hover { transform: translateX(4px); }

    .check-done .check-icon { background: rgba(51,232,24,0.15); color: #33e818; }
    .check-pending .check-icon { background: rgba(255,255,255,0.05); color: #4b5563; }

    .check-icon {
        width: 22px;
        height: 22px;
        min-width: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        margin-top: 2px;
    }

    /* ── Timeline connector ── */
    .timeline-line {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        width: 2px;
        background: linear-gradient(to bottom, rgba(51,232,24,0.4), rgba(99,102,241,0.4), rgba(251,191,36,0.4));
        top: 0; bottom: 0;
        z-index: 0;
    }

    /* ── Progress bar ── */
    .progress-bar-fill {
        height: 4px;
        border-radius: 9999px;
        transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ── Floating version badge ── */
    .version-badge {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.08em;
        padding: 4px 12px;
        border-radius: 9999px;
    }

    /* ── Animate on load ── */
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-card { animation: fadeSlideUp 0.5s ease forwards; }
    .delay-1 { animation-delay: 0.1s; opacity: 0; }
    .delay-2 { animation-delay: 0.25s; opacity: 0; }
    .delay-3 { animation-delay: 0.4s; opacity: 0; }
</style>

<!-- ══ HERO HEADER ══ -->
<div class="roadmap-hero rounded-3xl p-8 md:p-12 mb-10 border border-white/8 relative">
    <div class="relative z-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-accent/10 border border-accent/20 rounded-full mb-4">
                    <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                    <span class="text-xs font-bold text-accent uppercase tracking-widest">System Development Blueprint</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-black text-white leading-tight mb-3">
                    Roadmap Pengembangan<br>
                    <span class="text-accent">Finance ERP</span> PT ALMA
                </h1>
                <p class="text-gray-400 text-sm md:text-base max-w-xl">
                    Cetak biru transformasi sistem keuangan — dari stabilisasi bug kritis hingga kecerdasan analitik berbasis AI.
                </p>
            </div>

            <!-- Overall Progress -->
            <div class="bg-black/40 border border-white/10 rounded-2xl p-6 min-w-56 backdrop-blur-sm">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-4">Progress Keseluruhan</p>
                <div class="flex items-end gap-3 mb-4">
                    <span class="text-5xl font-black text-white">52</span>
                    <span class="text-gray-500 text-lg font-bold mb-1">/ 100%</span>
                </div>
                <div class="w-full h-2 bg-white/5 rounded-full overflow-hidden mb-2">
                    <div class="progress-bar-fill bg-gradient-to-r from-accent via-indigo-400 to-amber-400" id="overallBar" style="width:0%"></div>
                </div>
                <p class="text-xs text-gray-600">3 versi · 15 fitur · <span class="text-accent font-bold">7 selesai</span></p>
            </div>
        </div>
    </div>
</div>

<!-- ══ VERSION CARDS ══ -->
<div class="space-y-6">

    <!-- ═══ V1.0 ═══ -->
    <div class="bg-[#0d0d0d] border rounded-3xl overflow-hidden transition-all duration-300 animate-card delay-1 card-v1">
        <!-- Card Header -->
        <div class="p-6 md:p-8 border-b border-white/5">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-accent/10 border border-accent/20 flex items-center justify-center text-2xl flex-shrink-0">
                        🚀
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="version-badge badge-v1">VERSI 1.0</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-accent/20 text-accent font-bold border border-accent/30 animate-pulse">
                                ● FASE AKTIF
                            </span>
                        </div>
                        <h2 class="text-xl font-black text-white">Stabilisasi &amp; Integritas Akuntansi</h2>
                        <p class="text-sm text-gray-500 mt-1">Memperbaiki bug fatal, menjaga akurasi pembukuan, dan mengamankan sistem.</p>
                    </div>
                </div>
                <div class="flex-shrink-0 text-right">
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-1">Selesai</p>
                    <p class="text-3xl font-black text-accent">100<span class="text-base">%</span></p>
                    <div class="w-32 h-1.5 bg-white/5 rounded-full overflow-hidden mt-2 ml-auto">
                        <div class="progress-bar-fill bg-accent" id="v1bar" style="width:0%"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Feature List -->
        <div class="p-6 md:p-8">
            <div class="space-y-0">
                <?php
                $v1items = [
                    ['done' => true,  'text' => 'Perbaikan fatal crash <code class="text-accent/80 font-mono text-xs bg-accent/10 px-1.5 py-0.5 rounded">$this->db</code> pada transaksi transfer Kas &amp; Bank.'],
                    ['done' => true,  'text' => 'Implementasi validasi ketat <span class="text-accent font-bold">Debit = Kredit</span> pada Jurnal Umum manual.'],
                    ['done' => true,  'text' => 'Penutupan celah keamanan bypass CSRF pada manajemen pengguna.'],
                    ['done' => true,  'text' => 'Pembuatan filter otorisasi keuangan eksklusif Level 6 (Accounting).'],
                    ['done' => true,  'text' => 'Penyusunan Laporan Neraca Saldo (Trial Balance) dinamis untuk pengecekan pra-laporan.'],
                ];
                foreach ($v1items as $item):
                ?>
                <div class="check-item <?= $item['done'] ? 'check-done' : 'check-pending' ?>">
                    <div class="check-icon">
                        <?php if ($item['done']): ?>
                            <i class="fas fa-check"></i>
                        <?php else: ?>
                            <i class="fas fa-clock"></i>
                        <?php endif; ?>
                    </div>
                    <p class="text-sm text-gray-300 leading-relaxed"><?= $item['text'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ═══ V2.0 ═══ -->
    <div class="bg-[#0d0d0d] border rounded-3xl overflow-hidden transition-all duration-300 animate-card delay-2 card-v2">
        <div class="p-6 md:p-8 border-b border-white/5">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-2xl flex-shrink-0">
                        🔄
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="version-badge badge-v2">VERSI 2.0</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-indigo-500/10 text-indigo-400 font-bold border border-indigo-500/20">
                                UPCOMING
                            </span>
                        </div>
                        <h2 class="text-xl font-black text-white">Otomatisasi ERP &amp; Integrasi Transaksi</h2>
                        <p class="text-sm text-gray-500 mt-1">Mengurangi double entry dengan menghubungkan modul operasional langsung ke jurnal.</p>
                    </div>
                </div>
                <div class="flex-shrink-0 text-right">
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-1">Selesai</p>
                    <p class="text-3xl font-black text-indigo-400">40<span class="text-base">%</span></p>
                    <div class="w-32 h-1.5 bg-white/5 rounded-full overflow-hidden mt-2 ml-auto">
                        <div class="progress-bar-fill bg-indigo-500" id="v2bar" style="width:0%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-6 md:p-8">
            <div class="space-y-0">
                <?php
                $v2items = [
                    ['done' => true,  'text' => 'Pengembangan modul Jurnal Otomatis saat Faktur Penjualan (Invoice) dibuat / dilunasi.'],
                    ['done' => true,  'text' => 'Pengembangan modul Jurnal Otomatis saat Faktur Pembelian (Pengeluaran) dicatat / dibayar.'],
                    ['done' => false, 'text' => 'Pembuatan sistem <span class="text-indigo-400 font-semibold">Tutup Buku Bulanan</span> (Closing Period) untuk mengunci transaksi masa lalu.'],
                    ['done' => false, 'text' => 'Refactoring Laporan Arus Kas menjadi dinamis berbasis mutasi riil Kas &amp; Bank <span class="text-indigo-400 font-semibold">(Direct Method)</span>.'],
                    ['done' => false, 'text' => 'Penambahan Audit Trail (<code class="text-indigo-400/80 font-mono text-xs bg-indigo-500/10 px-1.5 py-0.5 rounded">created_by</code>, <code class="text-indigo-400/80 font-mono text-xs bg-indigo-500/10 px-1.5 py-0.5 rounded">updated_by</code>) pada setiap log pembukuan.'],
                ];
                foreach ($v2items as $item):
                ?>
                <div class="check-item <?= $item['done'] ? 'check-done' : 'check-pending' ?>">
                    <div class="check-icon" style="<?= $item['done'] ? 'background:rgba(99,102,241,0.15);color:#818cf8;' : '' ?>">
                        <?php if ($item['done']): ?>
                            <i class="fas fa-check"></i>
                        <?php else: ?>
                            <i class="fas fa-clock"></i>
                        <?php endif; ?>
                    </div>
                    <p class="text-sm <?= $item['done'] ? 'text-gray-300' : 'text-gray-500' ?> leading-relaxed"><?= $item['text'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ═══ V3.0 ═══ -->
    <div class="bg-[#0d0d0d] border rounded-3xl overflow-hidden transition-all duration-300 animate-card delay-3 card-v3">
        <div class="p-6 md:p-8 border-b border-white/5">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-2xl flex-shrink-0">
                        🧠
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="version-badge badge-v3">VERSI 3.0</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-400 font-bold border border-amber-500/20">
                                MASA DEPAN
                            </span>
                        </div>
                        <h2 class="text-xl font-black text-white">Analitik Cerdas &amp; Restrukturisasi Akuntansi</h2>
                        <p class="text-sm text-gray-500 mt-1">Memisahkan detail akuntansi demi performa skala besar dan menghadirkan prediksi arus kas.</p>
                    </div>
                </div>
                <div class="flex-shrink-0 text-right">
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-1">Selesai</p>
                    <p class="text-3xl font-black text-amber-400">0<span class="text-base">%</span></p>
                    <div class="w-32 h-1.5 bg-white/5 rounded-full overflow-hidden mt-2 ml-auto">
                        <div class="progress-bar-fill bg-amber-400" id="v3bar" style="width:0%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-6 md:p-8">
            <div class="space-y-0">
                <?php
                $v3items = [
                    ['done' => false, 'text' => 'Pemisahan tabel jurnal menjadi arsitektur master-detail (<code class="text-amber-400/80 font-mono text-xs bg-amber-500/10 px-1.5 py-0.5 rounded">jurnal_header</code> &amp; <code class="text-amber-400/80 font-mono text-xs bg-amber-500/10 px-1.5 py-0.5 rounded">jurnal_detail</code>).'],
                    ['done' => false, 'text' => 'Implementasi dashboard analisis profitabilitas proyek menggunakan visualisasi <span class="text-amber-400 font-semibold">Chart.js</span> mutakhir.'],
                    ['done' => false, 'text' => 'Modul penaksir kewajiban perpajakan otomatis (<span class="text-amber-400 font-semibold">PPN &amp; PPh</span>) terintegrasi pada faktur.'],
                    ['done' => false, 'text' => 'Ekspor Laporan Keuangan standar SAK lengkap (Neraca, Laba Rugi, Perubahan Modal, CALK, Arus Kas) ke <span class="text-amber-400 font-semibold">Excel &amp; PDF</span> dengan template premium siap cetak untuk keperluan RUPS/Pajak.'],
                    ['done' => false, 'text' => 'Modul rekonsiliasi bank otomatis melalui pencocokan file mutasi rekening <code class="text-amber-400/80 font-mono text-xs bg-amber-500/10 px-1.5 py-0.5 rounded">.csv</code> dengan buku kas bank ERP.'],
                ];
                foreach ($v3items as $item):
                ?>
                <div class="check-item check-pending">
                    <div class="check-icon">
                        <i class="fas fa-lock" style="font-size:8px;"></i>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed"><?= $item['text'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>

<!-- ══ BOTTOM STATS ROW ══ -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
    <div class="bg-[#0d0d0d] border border-accent/15 rounded-2xl p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-check-double text-accent text-lg"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Fitur Selesai</p>
            <p class="text-2xl font-black text-white">7 <span class="text-sm text-gray-600 font-normal">dari 15</span></p>
        </div>
    </div>
    <div class="bg-[#0d0d0d] border border-indigo-500/15 rounded-2xl p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-code-branch text-indigo-400 text-lg"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Versi Aktif</p>
            <p class="text-2xl font-black text-white">1.0 <span class="text-sm text-gray-600 font-normal">→ 2.0 next</span></p>
        </div>
    </div>
    <div class="bg-[#0d0d0d] border border-amber-500/15 rounded-2xl p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-rocket text-amber-400 text-lg"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Estimasi V3.0</p>
            <p class="text-2xl font-black text-white">Q4 <span class="text-sm text-gray-600 font-normal">2026</span></p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Animate progress bars after a short delay
    setTimeout(() => {
        document.getElementById('overallBar').style.width = '52%';
        document.getElementById('v1bar').style.width = '100%';
        document.getElementById('v2bar').style.width = '40%';
        document.getElementById('v3bar').style.width = '0%';
    }, 300);
});
</script>
<?= $this->endSection() ?>
