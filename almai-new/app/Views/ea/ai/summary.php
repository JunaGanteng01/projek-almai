<?= $this->extend('ea/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = $title ?? 'AI Summary';
$activeMenu = $activeMenu ?? 'ai-summary'; 
?>

<!-- Header -->
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold mb-2 flex items-center gap-2">
            <i class="fas fa-robot text-purple-400"></i> <?= $pageTitle ?>
        </h2>
        <p class="text-gray-400">Ringkasan harian dan insight berbasis kecerdasan buatan.</p>
    </div>
    <button class="bg-purple-600 hover:bg-purple-500 text-white px-4 py-2 rounded-lg transition shadow-lg shadow-purple-500/20">
        <i class="fas fa-sync-alt mr-2"></i> Generate Ulang
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Kolom Utama -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Executive Summary -->
        <div class="bg-gradient-to-br from-[#111] to-black border border-white/10 rounded-2xl p-6 shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 p-6 opacity-10">
                <i class="fas fa-brain text-8xl text-purple-500"></i>
            </div>
            
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white">Ringkasan Eksekutif (Hari Ini)</h3>
            </div>
            
            <div class="prose prose-invert max-w-none text-gray-300">
                <p>
                    Berdasarkan analisis data operasional dan keuangan terkini, performa perusahaan menunjukkan tren positif yang stabil. Pendapatan Q3 sedikit melampaui ekspektasi awal dengan pertumbuhan 4.2% minggu ini.
                </p>
                <ul class="mt-4 space-y-2">
                    <li><strong class="text-white">Fokus Utama:</strong> Penyelesaian 3 <em>Strategic Projects</em> yang statusnya mendekati deadline bulan ini.</li>
                    <li><strong class="text-white">Risiko Terdeteksi:</strong> Terdapat indikasi <em>Server Load</em> yang tinggi pada jam sibuk, disarankan eskalasi ke tim IT.</li>
                    <li><strong class="text-white">Peluang:</strong> Tren pasar menunjukkan peningkatan minat pada layanan konsultasi premium kita.</li>
                </ul>
            </div>
        </div>

        <!-- Action Items (AI Suggested) -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
            <h3 class="text-lg font-bold text-white mb-4">Rekomendasi Tindakan</h3>
            
            <div class="space-y-4">
                <div class="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/5">
                    <div class="mt-1 w-2 h-2 rounded-full bg-red-500"></div>
                    <div>
                        <h4 class="font-semibold text-white">Review & Approve Laporan Q3</h4>
                        <p class="text-sm text-gray-400 mt-1">Laporan dari tim keuangan butuh persetujuan Anda segera untuk rilis ke stakeholder.</p>
                        <a href="<?= base_url('ea/approval-waiting') ?>" class="inline-block mt-3 text-sm text-purple-400 hover:text-purple-300 transition">Lihat Approval <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/5">
                    <div class="mt-1 w-2 h-2 rounded-full bg-yellow-500"></div>
                    <div>
                        <h4 class="font-semibold text-white">Jadwalkan Rapat IT Infrastruktur</h4>
                        <p class="text-sm text-gray-400 mt-1">Sistem mendeteksi anomali pada server load. Disarankan meeting dengan Head of IT.</p>
                        <a href="<?= base_url('ea/meetings/create') ?>" class="inline-block mt-3 text-sm text-purple-400 hover:text-purple-300 transition">Jadwalkan Meeting <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Kolom Kanan: Insight Metrik -->
    <div class="space-y-6">
        
        <!-- Sentimen & Kesehatan Bisnis -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
            <h3 class="text-lg font-bold text-white mb-4">Business Health Score</h3>
            <div class="flex items-center justify-center py-4">
                <div class="relative w-32 h-32 flex items-center justify-center">
                    <svg class="transform -rotate-90 w-32 h-32">
                        <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="12" fill="transparent" class="text-gray-800" />
                        <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="12" fill="transparent" stroke-dasharray="351.85" stroke-dashoffset="45" class="text-emerald-500" />
                    </svg>
                    <div class="absolute text-3xl font-bold text-white">87<span class="text-lg">%</span></div>
                </div>
            </div>
            <p class="text-center text-sm text-gray-400">Kesehatan operasional dan finansial berada pada status <strong>Excellent</strong>.</p>
        </div>

        <!-- Highlight Data -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Top AI Insights</h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <span class="text-gray-300">Produktivitas Tim</span>
                    </div>
                    <span class="font-bold text-white">+12%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-500/20 text-red-400 flex items-center justify-center text-sm">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <span class="text-gray-300">Waktu Respon (SLA)</span>
                    </div>
                    <span class="font-bold text-white">-5%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-sm">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="text-gray-300">Tugas Selesai</span>
                    </div>
                    <span class="font-bold text-white">45 Task</span>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
