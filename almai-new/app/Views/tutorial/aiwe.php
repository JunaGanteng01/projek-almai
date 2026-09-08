<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Use CSS for smooth scrolling and scroll margins -->
<style>
    html {
        scroll-behavior: smooth;
    }
    .scroll-mt-header {
        scroll-margin-top: 100px; /* Adjust according to your navbar height */
    }
    .bg-grid {
        background-image: linear-gradient(to right, rgba(255,255,255,0.02) 1px, transparent 1px),
                          linear-gradient(to bottom, rgba(255,255,255,0.02) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .glass-card {
        background: rgba(17, 17, 17, 0.6);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 1.5rem;
    }
    .accent-glow {
        box-shadow: 0 0 30px rgba(51, 232, 24, 0.05);
    }
    #doc-nav a.active {
        color: #33E818;
        background: rgba(51, 232, 24, 0.05);
        border-left-color: #33E818;
    }
    #doc-nav a {
        transition: all 0.2s ease-in-out;
    }
    .checklist-item input:checked + span {
        text-decoration: line-through;
        color: rgba(255, 255, 255, 0.2);
    }
</style>

<!-- Hero Section -->
<section class="relative pt-32 pb-20 overflow-hidden bg-black border-b border-white/5">
    <div class="absolute inset-0 bg-grid opacity-20"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-accent/10 border border-accent/20 rounded-full text-accent text-[10px] font-bold tracking-widest uppercase mb-6">
                Official Comprehensive Guide
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 tracking-tight">
                AIWE <span class="text-accent">MASTER DOCS</span>
            </h1>
            <p class="text-lg text-gray-400 leading-relaxed mb-10 max-w-2xl mx-auto">
                Panduan pengguna terlengkap AIWE Expert Advisor dari PT. ALMA INDONESIA RAYA. Pelajari setiap detail teknis untuk memaksimalkan hasil trading Anda.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#quick-start" class="px-8 py-3 bg-accent text-black font-bold rounded-xl hover:scale-105 transition-transform shadow-[0_0_20px_rgba(51,232,24,0.2)]">
                    Quick Start Guide
                </a>
                <a href="#pengenalan" class="px-8 py-3 bg-white/5 border border-white/10 text-white font-bold rounded-xl hover:bg-white/10 transition-colors">
                    Baca Selengkapnya
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Content Layout -->
<section class="py-20 bg-[#050505]">
    <div class="container mx-auto px-6">
        <div class="flex flex-col lg:flex-row gap-12">
            
            <!-- Sidebar Nav -->
            <aside class="lg:w-64 shrink-0">
                <div class="sticky top-28 space-y-6">
                    <div class="bg-black/40 border border-white/5 rounded-2xl p-5 max-h-[70vh] overflow-y-auto custom-scrollbar">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-4 pl-3">Daftar Isi</p>
                        <nav id="doc-nav" class="flex flex-col gap-1">
                            <?php 
                            $nav = [
                                'pengenalan' => '1. Pengenalan',
                                'instalasi' => '2. Instalasi',
                                'aktivasi' => '3. Aktivasi License',
                                'pengaturan' => '4. Pengaturan EA',
                                'mode' => '5. Mode Trading',
                                'cara-pakai' => '6. Cara Menggunakan',
                                'dashboard' => '7. Dashboard',
                                'kontrol' => '8. Tombol Kontrol',
                                'tips' => '9. Tips Trading',
                                'troubleshooting' => '10. Troubleshooting',
                                'faq' => '11. FAQ',
                                'disclaimer' => 'Disclaimer',
                                'checklist' => 'Checklist Trading',
                                'quick-start' => 'Quick Start Guide'
                            ];
                            foreach ($nav as $id => $label): ?>
                                <a href="#<?= $id ?>" class="px-3 py-2 text-sm text-gray-400 hover:text-accent border-l-2 border-transparent hover:bg-white/5 rounded-r-lg">
                                    <?= $label ?>
                                </a>
                            <?php endforeach; ?>
                        </nav>
                    </div>

                    <div class="bg-gradient-to-br from-accent/5 to-transparent border border-accent/20 rounded-2xl p-5">
                        <p class="text-xs text-white font-bold mb-2">Kontak Support</p>
                        <p class="text-[10px] text-gray-500 mb-4">Senin - Jumat | 09:00 - 17:00 WIB</p>
                        <a href="mailto:support@almai.id" class="block w-full text-center py-2 bg-white/5 border border-white/10 text-white text-[10px] font-bold rounded-lg mb-2">support@almai.id</a>
                        <a href="#" class="block w-full text-center py-2.5 bg-accent text-black text-[10px] font-bold rounded-lg hover:brightness-110 transition-all uppercase tracking-widest">Chat WhatsApp</a>
                    </div>
                </div>
            </aside>

            <!-- Main Documentation -->
            <div class="flex-1 max-w-4xl space-y-24">
                
                <!-- 1. Pengenalan -->
                <article id="pengenalan" class="scroll-mt-header" data-aos="fade-up">
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-white mb-4">1. Pengenalan</h2>
                        <div class="h-1 w-20 bg-accent rounded-full"></div>
                    </div>
                    <div class="glass-card p-8 accent-glow leading-relaxed text-gray-400">
                        <p class="text-lg mb-6">
                            AIWE Expert Advisor adalah sistem trading otomatis yang dikembangkan oleh <strong>PT. ALMA INDONESIA RAYA</strong>, perusahaan penasihat berjangka resmi di Indonesia.
                        </p>
                        
                        <div class="mb-8">
                            <h4 class="text-white font-bold mb-4 flex items-center gap-2">
                                <i class="fas fa-landmark text-accent"></i> Izin Resmi
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs uppercase tracking-wider">
                                <div class="p-4 bg-black/40 rounded-xl border border-white/5">
                                    <i class="fas fa-university text-accent mb-1 inline-block"></i> <br>
                                    <strong>BAPPEBTI</strong><br><span class="text-[10px] opacity-60">Komoditi</span>
                                </div>
                                <div class="p-4 bg-black/40 rounded-xl border border-white/5">
                                    <i class="fas fa-shield-alt text-accent mb-1 inline-block"></i> <br>
                                    <strong>OJK</strong><br><span class="text-[10px] opacity-60">Saham Derivatif</span>
                                </div>
                                <div class="p-4 bg-black/40 rounded-xl border border-white/5">
                                    <i class="fas fa-landmark text-accent mb-1 inline-block"></i> <br>
                                    <strong>Bank Indonesia</strong><br><span class="text-[10px] opacity-60">Pasar Uang & Valas</span>
                                </div>
                            </div>
                        </div>

                        <h4 class="text-white font-bold mb-4">Fitur Utama</h4>
                        <div class="grid md:grid-cols-2 gap-4">
                            <?php 
                            $features = [
                                ['icon' => 'fa-check-circle', 'text' => 'Trading otomatis berbasis Moving Average'],
                                ['icon' => 'fa-shield-alt', 'text' => 'Risk Management dengan SL Risk Percentage'],
                                ['icon' => 'fa-layer-group', 'text' => 'Sistem Layering (Manual & Otomatis)'],
                                ['icon' => 'fa-project-diagram', 'text' => '3 Mode Trading (Normal, Layering, Hybrid)'],
                                ['icon' => 'fa-tachometer-alt', 'text' => 'Dashboard real-time'],
                                ['icon' => 'fa-chart-line', 'text' => 'Pyramiding untuk maximize profit'],
                                ['icon' => 'fa-key', 'text' => 'Sistem License Online']
                            ];
                            foreach ($features as $f): ?>
                                <div class="flex items-center gap-3 bg-black/40 p-3 rounded-xl border border-white/5 text-sm">
                                    <i class="fas <?= $f['icon'] ?> text-accent"></i>
                                    <span><?= $f['text'] ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </article>

                <!-- 2. Instalasi -->
                <article id="instalasi" class="scroll-mt-header" data-aos="fade-up">
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-white mb-4">2. Instalasi</h2>
                        <div class="h-1 w-20 bg-accent rounded-full"></div>
                    </div>
                    <div class="space-y-8">
                        <div class="glass-card p-8 bg-accent/5 border-accent/20 border-dashed">
                            <p class="text-accent text-xs font-bold uppercase mb-4 flex items-center gap-2">
                                <i class="fas fa-box"></i> File yang Anda Terima:
                            </p>
                            <div class="grid grid-cols-2 gap-4 font-mono text-[10px]">
                                <div class="text-gray-400">1. AIWE Expert Advisor.ex5</div><div class="text-gray-600 italic">// File EA</div>
                                <div class="text-gray-400">2. open.wav</div><div class="text-gray-600 italic">// Sound Open Posisi</div>
                                <div class="text-gray-400">3. profit.wav</div><div class="text-gray-600 italic">// Sound Profit</div>
                                <div class="text-gray-400">4. stop loss.wav</div><div class="text-gray-600 italic">// Sound SL</div>
                                <div class="text-gray-400">5. minus.wav</div><div class="text-gray-600 italic">// Sound Loss</div>
                            </div>
                        </div>

                        <div class="grid gap-6">
                            <div class="glass-card p-8">
                                <h4 class="text-white font-bold mb-4 flex items-center gap-3">
                                    <span class="w-8 h-8 bg-accent text-black rounded-full flex items-center justify-center font-black">1</span>
                                    Copy File EA
                                </h4>
                                <ol class="text-sm text-gray-500 space-y-3 list-decimal pl-5">
                                    <li>Buka MT5, klik menu <strong>File > Open Data Folder</strong>.</li>
                                    <li>Masuk ke folder <strong>MQL5 > Experts</strong>.</li>
                                    <li>Copy file <code>AIWE Expert Advisor.ex5</code> ke sini.</li>
                                </ol>
                                <div class="mt-4 p-3 bg-black rounded-lg border border-white/5 font-mono text-[9px] text-gray-700">C:\Users\...\MQL5\Experts\</div>
                            </div>

                            <div class="glass-card p-8">
                                <h4 class="text-white font-bold mb-4 flex items-center gap-3">
                                    <span class="w-8 h-8 bg-accent text-black rounded-full flex items-center justify-center font-black">2</span>
                                    Copy File Sound
                                </h4>
                                <ol class="text-sm text-gray-500 space-y-3 list-decimal pl-5">
                                    <li>Dari folder MQL5 tadi, masuk ke folder <strong>Files</strong>.</li>
                                    <li>Copy semua file <code>.wav</code> (open, profit, stop loss, minus) ke folder ini.</li>
                                </ol>
                                <div class="mt-4 p-3 bg-black rounded-lg border border-white/5 font-mono text-[9px] text-gray-700">C:\Users\...\MQL5\Files\</div>
                            </div>

                            <div class="glass-card p-8 border-accent/30 bg-accent/5">
                                <h4 class="text-white font-bold mb-4 flex items-center gap-3 border-b border-accent/10 pb-4">
                                    <span class="w-8 h-8 bg-accent text-black rounded-full flex items-center justify-center font-black">3</span>
                                    Web Request (PENTING!)
                                </h4>
                                <ol class="text-sm text-gray-500 space-y-4 list-decimal pl-5">
                                    <li>Klik menu <strong>Tools > Options</strong>.</li>
                                    <li>Pilih tab <strong>Expert Advisors</strong>.</li>
                                    <li>Centang <strong>"Allow WebRequest for listed URL"</strong>.</li>
                                    <li>Klik <strong>Add</strong> dan masukkan: <code class="text-accent">https://almai.id</code></li>
                                </ol>
                                <p class="mt-4 text-[10px] text-red-500 font-bold uppercase italic tracking-widest text-center flex items-center justify-center gap-2">
                                    <i class="fas fa-exclamation-triangle"></i> Tanpa langkah ini, license tidak bisa divalidasi!
                                </p>
                            </div>

                            <div class="glass-card p-8">
                                <h4 class="text-white font-bold mb-4 flex items-center gap-3">
                                    <span class="w-8 h-8 bg-accent text-black rounded-full flex items-center justify-center font-black">4</span>
                                    Refresh Navigator
                                </h4>
                                <ol class="text-sm text-gray-500 space-y-3 list-decimal pl-5">
                                    <li>Di jendela <strong>Navigator</strong> (Ctrl+N).</li>
                                    <li>Klik kanan pada <strong>Expert Advisors</strong>, pilih <strong>Refresh</strong>.</li>
                                    <li>EA `AIWE Expert Advisor` akan muncul di daftar.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- 3. Aktivasi License -->
                <article id="aktivasi" class="scroll-mt-header" data-aos="fade-up">
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-white mb-4">3. Aktivasi License</h2>
                        <div class="h-1 w-20 bg-accent rounded-full"></div>
                    </div>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="glass-card p-8">
                            <h4 class="text-white font-bold mb-4 flex items-center gap-2">
                                <i class="fas fa-key text-accent"></i> Mendapatkan License
                            </h4>
                            <ul class="text-sm text-gray-500 space-y-4">
                                <li><strong>1.</strong> Hubungi admin PT. ALMA INDONESIA RAYA.</li>
                                <li><strong>2.</strong> Pilih paket (Trial, Monthly, Lifetime).</li>
                                <li><strong>3.</strong> Lakukan pembayaran & terima key.</li>
                                <li class="p-3 bg-black rounded-xl border border-white/10 text-xs font-mono">Format: AIWE-2024-XXXXX-XXXXX</li>
                            </ul>
                        </div>
                        <div class="glass-card p-8">
                            <h4 class="text-white font-bold mb-4 flex items-center gap-2">
                                <i class="fas fa-rocket text-accent"></i> Aktivasi EA
                            </h4>
                            <ul class="text-sm text-gray-500 space-y-3">
                                <li><strong>1.</strong> Drag EA ke chart.</li>
                                <li><strong>2.</strong> Buka tab <strong>Inputs</strong>.</li>
                                <li><strong>3.</strong> Paste key di field <strong>License Key</strong>.</li>
                                <li><strong>4.</strong> Klik <strong>OK</strong>. Dashboard harus berubah jadi hijau <strong>VALID</strong>.</li>
                            </ul>
                            <div class="mt-4 p-3 bg-accent/10 border border-accent/20 rounded-xl">
                                <p class="text-[10px] text-accent leading-relaxed italic flex items-center gap-2 text-center justify-center">
                                    <i class="fas fa-lightbulb"></i> License key otomatis tersimpan. Anda tidak perlu input lagi di chart berikutnya.
                                </p>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- 4. Pengaturan EA -->
                <article id="pengaturan" class="scroll-mt-header" data-aos="fade-up">
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-white mb-4">4. Pengaturan EA</h2>
                        <div class="h-1 w-20 bg-accent rounded-full"></div>
                    </div>
                    
                    <div class="space-y-12">
                        <!-- Common Settings -->
                        <div class="glass-card p-8">
                            <h4 class="text-white font-bold mb-4 uppercase text-xs tracking-widest text-accent">General Setup</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm text-gray-500">
                                <div class="p-4 bg-black rounded-xl border border-white/5">✅ <strong>Allow Algo Trading</strong><br><span class="text-[10px]">Wajib untuk otomatis</span></div>
                                <div class="p-4 bg-black rounded-xl border border-white/5">✅ <strong>Allow DLL imports</strong><br><span class="text-[10px]">Untuk tombol HELP</span></div>
                            </div>
                        </div>

                        <!-- Parameter Table -->
                        <div class="glass-card p-0 overflow-hidden border-white/10">
                            <div class="bg-white/5 px-8 py-4 border-b border-white/5 flex justify-between items-center text-[10px] font-black uppercase tracking-widest">
                                <span class="text-white">Detailed Parameter Reference</span>
                                <span class="text-accent">Inputs Tab</span>
                            </div>
                            <div class="p-8">
                                <table class="w-full text-[11px] font-mono text-gray-400">
                                    <thead class="text-gray-500 uppercase">
                                        <tr class="text-left border-b border-white/5"><th class="pb-4">Parameter</th><th class="pb-4">Default</th><th class="pb-4 hidden lg:table-cell">Notes</th></tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5">
                                        <!-- License -->
                                        <tr><td class="py-4 text-white">License Key</td><td class="py-4">-</td><td class="py-4 hidden lg:table-cell italic text-gray-600">Input license resmi Anda</td></tr>
                                        <tr><td class="py-4 text-white">API URL</td><td class="py-4">almai.id/...</td><td class="py-4 hidden lg:table-cell italic text-red-800">⚠️ JANGAN DIUBAH</td></tr>
                                        <!-- Trading -->
                                        <tr><td class="py-4 text-accent">Trading Mode</td><td class="py-4">Normal</td><td class="py-4 hidden lg:table-cell italic text-gray-600">Normal / Layering / Hybrid</td></tr>
                                        <tr><td class="py-4 text-accent">TP Rasio</td><td class="py-4">8.0</td><td class="py-4 hidden lg:table-cell italic text-gray-600">Range: 1.0 - 20.0</td></tr>
                                        <tr><td class="py-4 text-accent">SL Risk %</td><td class="py-4">5.0%</td><td class="py-4 hidden lg:table-cell italic text-gray-600">Risk per balance (1-10%)</td></tr>
                                        <tr><td class="py-4 text-accent">SL Distance</td><td class="py-4">3000</td><td class="py-4 hidden lg:table-cell italic text-gray-600">Jarak SL (points)</td></tr>
                                        <tr><td class="py-4 text-accent">Close By Signal</td><td class="py-4">true</td><td class="py-4 hidden lg:table-cell italic text-gray-600">Close saat trend berbalik</td></tr>
                                        <!-- Pyramiding -->
                                        <tr><td class="py-4 text-blue-400">Pyramiding</td><td class="py-4">false</td><td class="py-4 hidden lg:table-cell italic text-gray-600">Menambah posisi saat profit</td></tr>
                                        <tr><td class="py-4 text-blue-400">Profit Step</td><td class="py-4">50</td><td class="py-4 hidden lg:table-cell italic text-gray-600">Equity step untuk add pos</td></tr>
                                        <!-- Layering -->
                                        <tr><td class="py-4 text-purple-400">Layer Count</td><td class="py-4">3</td><td class="py-4 hidden lg:table-cell italic text-gray-600">Count x 2 order pending</td></tr>
                                        <tr><td class="py-4 text-purple-400">Group Target %</td><td class="py-4">5.0%</td><td class="py-4 hidden lg:table-cell italic text-gray-600">Close all layer group profit</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- 5. Mode Trading -->
                <article id="mode" class="scroll-mt-header" data-aos="fade-up">
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-white mb-4">5. Mode Trading</h2>
                        <div class="h-1 w-20 bg-accent rounded-full"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="glass-card p-6 border-white/5 hover:border-accent/30 transition-all">
                            <h5 class="text-white font-bold mb-3 flex items-center gap-2">🟢 Normal</h5>
                            <p class="text-[11px] text-gray-500 leading-relaxed">EA trading otomatis (AIWE). Layering tetap tersedia untuk manual trading. Rekomendasi untuk pemula.</p>
                        </div>
                        <div class="glass-card p-6 border-white/5 hover:border-accent/30 transition-all">
                            <h5 class="text-white font-bold mb-3 flex items-center gap-2">🟢 Layering Only</h5>
                            <p class="text-[11px] text-gray-500 leading-relaxed">Otomatisasi mati. Hanya tombol layering manual yang aktif. Cocok untuk trader berpengalaman.</p>
                        </div>
                        <div class="glass-card p-6 border-accent/20 accent-glow">
                            <h5 class="text-white font-bold mb-3 flex items-center gap-2">🟢 Hybrid</h5>
                            <p class="text-[11px] text-gray-500 leading-relaxed">Otomatisasi aktif. Saat EA open AIWE, dia akan otomatis memasang layering juga. Maximize profit!</p>
                        </div>
                    </div>
                </article>

                <!-- 6. Cara Menggunakan -->
                <article id="cara-pakai" class="scroll-mt-header" data-aos="fade-up">
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-white mb-4">6. Cara Menggunakan</h2>
                        <div class="h-1 w-20 bg-accent rounded-full"></div>
                    </div>
                    
                    <!-- Daily Routine -->
                    <div class="glass-card p-8 mb-8 bg-gradient-to-br from-[#111] to-black">
                        <h4 class="text-white font-bold mb-6 text-sm uppercase tracking-widest text-accent flex items-center gap-2">
                            <i class="fas fa-calendar-alt"></i> Tips Rutinitas Harian
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="p-5 bg-black rounded-2xl border border-white/5">
                                <p class="text-white font-bold text-xs mb-3 flex items-center gap-2">
                                    <i class="fas fa-sun text-accent"></i> Pagi (Start)
                                </p>
                                <ul class="text-[10px] text-gray-500 space-y-2">
                                    <li>- Cek high impact news</li>
                                    <li>- Aktifkan EA</li>
                                    <li>- Set Daily Target realistis</li>
                                </ul>
                            </div>
                            <div class="p-5 bg-black rounded-2xl border border-white/5">
                                <p class="text-white font-bold text-xs mb-3 flex items-center gap-2">
                                    <i class="fas fa-sun text-yellow-500"></i> Siang (Monitor)
                                </p>
                                <ul class="text-[10px] text-gray-500 space-y-2">
                                    <li>- Cek dashboard 2-3x</li>
                                    <li>- Matikan EA jika ada News</li>
                                    <li>- Pantau floating P/L</li>
                                </ul>
                            </div>
                            <div class="p-5 bg-black rounded-2xl border border-white/5">
                                <p class="text-white font-bold text-xs mb-3 flex items-center gap-2">
                                    <i class="fas fa-moon text-blue-400"></i> Malam (Review)
                                </p>
                                <ul class="text-[10px] text-gray-500 space-y-2">
                                    <li>- Review trade history</li>
                                    <li>- Matikan EA atau VPS</li>
                                    <li>- Adjust setting jika perlu</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card p-8">
                        <h4 class="text-white font-bold mb-4">Mekanisme Trading</h4>
                        <div class="grid md:grid-cols-2 gap-8">
                            <div>
                                <h5 class="text-accent text-[11px] font-black uppercase mb-3">Otomatis (Sinyal)</h5>
                                <p class="text-xs text-gray-500 leading-relaxed">Sinyal BUY muncul saat MA10 (Hijau Tebal) memotong MA30 dari bawah ke atas. Sinyal SELL muncul saat sebaliknya. EA akan auto-exit jika trend berbalik.</p>
                            </div>
                            <div>
                                <h5 class="text-accent text-[11px] font-black uppercase mb-3">Manual (Layering)</h5>
                                <p class="text-xs text-gray-500 leading-relaxed">Klik BUY/SELL LAYERING. EA akan langsung membuka total 6 order sekaligus (1 Market + 2 Limit + 3 Stop) dengan jarak yang sudah disetting.</p>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- 7. Dashboard -->
                <article id="dashboard" class="scroll-mt-header" data-aos="fade-up">
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-white mb-4">7. Dashboard</h2>
                        <div class="h-1 w-20 bg-accent rounded-full"></div>
                    </div>
                    <div class="space-y-8">
                        <div class="glass-card p-8 accent-glow border-accent/20">
                            <h4 class="text-white font-bold mb-6 flex items-center justify-between">
                                <span class="flex items-center gap-2"><i class="fas fa-chart-bar"></i> Statistik Real-Time</span>
                                <span class="px-2 py-1 bg-accent/20 text-accent text-[8px] rounded uppercase">Active Tracking</span>
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-[10px] uppercase font-mono tracking-tight">
                                <div class="space-y-1"><span class="text-gray-600 block">Trade</span><span class="text-white text-lg font-bold">Total Close Pos</span></div>
                                <div class="space-y-1"><span class="text-gray-600 block">Profits</span><span class="text-accent text-lg font-bold">Net Profit ($)</span></div>
                                <div class="space-y-1"><span class="text-gray-600 block">Equity</span><span class="text-white text-lg font-bold">Current Value</span></div>
                                <div class="space-y-1"><span class="text-gray-600 block">Timer</span><span class="text-accent text-lg font-bold">HH:MM:SS</span></div>
                            </div>
                        </div>

                        <div class="glass-card p-8">
                            <h4 class="text-white font-bold mb-4">Chart Labels</h4>
                            <ul class="text-sm text-gray-500 space-y-4">
                                <li class="flex items-start gap-3"><i class="fas fa-arrow-right text-accent mt-1"></i> <i class="fas fa-scroll text-accent"></i> <strong>Label Harga:</strong> Floating P/L muncul di harga open posisi dengan countdown.</li>
                                <li class="flex items-start gap-3"><i class="fas fa-arrow-right text-accent mt-1"></i> <i class="fas fa-money-bill-wave text-accent"></i> <strong>Target TP:</strong> Nominal dollar target profit ditampilkan di garis TP.</li>
                                <li class="flex items-start gap-3"><i class="fas fa-arrow-right text-accent mt-1"></i> <i class="fas fa-shield-alt text-accent"></i> <strong>Potensi SL:</strong> Nominal dollar kerugian ditampilkan di garis SL.</li>
                            </ul>
                        </div>
                    </div>
                </article>

                <!-- 8. Tombol Kontrol -->
                <article id="kontrol" class="scroll-mt-header" data-aos="fade-up">
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-white mb-4">8. Tombol Kontrol</h2>
                        <div class="h-1 w-20 bg-accent rounded-full"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="glass-card p-6 border-red-500/20">
                            <span class="px-2 py-1 bg-red-600/20 text-red-500 text-[9px] font-black rounded mb-2 inline-block">EXIT</span>
                            <h5 class="text-white font-bold mb-2">Close All AIWE</h5>
                            <p class="text-[10px] text-gray-500 leading-relaxed">Menutup semua posisi trading otomatis di pair ini. Digunakan saat market bergejolak atau target tercapai.</p>
                        </div>
                        <div class="glass-card p-6 border-accent/20">
                            <span class="px-2 py-1 bg-accent/20 text-accent text-[9px] font-black rounded mb-2 inline-block">HELP</span>
                            <h5 class="text-white font-bold mb-2">Tutorial Online</h5>
                            <p class="text-[10px] text-gray-500 leading-relaxed">Membuka langsung halaman panduan ini di browser default Anda (Perlu DLL Import).</p>
                        </div>
                        <div class="glass-card p-6 border-blue-500/20">
                            <span class="px-2 py-1 bg-blue-600/20 text-blue-400 text-[9px] font-black rounded mb-2 inline-block">CLEAR PENDING</span>
                            <h5 class="text-white font-bold mb-2">Cancel Orders</h5>
                            <p class="text-[10px] text-gray-500 leading-relaxed">Menghapus semua pending order (Limit/Stop) yang belum tereksekusi tanpa menutup posisi open.</p>
                        </div>
                        <div class="glass-card p-6 border-purple-500/20">
                            <span class="px-2 py-1 bg-purple-600/20 text-purple-400 text-[9px] font-black rounded mb-2 inline-block">CLOSE LAYER</span>
                            <h5 class="text-white font-bold mb-2">Cleanup Layers</h5>
                            <p class="text-[10px] text-gray-500 leading-relaxed">Menutup seluruh posisi layering (open & pending) secara instan di pair ini.</p>
                        </div>
                    </div>
                </article>

                <!-- 9. Tips Trading -->
                <article id="tips" class="scroll-mt-header" data-aos="fade-up">
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-white mb-4">9. Tips Trading</h2>
                        <div class="h-1 w-20 bg-accent rounded-full"></div>
                    </div>
                    
                    <div class="space-y-8">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="glass-card p-6">
                                <h4 class="text-white font-bold mb-4 flex items-center gap-2">
                                    <i class="fas fa-chart-line text-accent"></i> Pair & Timeframe
                                </h4>
                                <ul class="text-[11px] text-gray-500 space-y-4">
                                    <li><strong>XAUUSD (Gold):</strong> Paling direkomendasikan & diuji.</li>
                                    <li><strong>EURUSD/GBPUSD:</strong> Spread rendah, trend lebih stabil.</li>
                                    <li><strong>M5-M15:</strong> Sinyal ideal (REKOMENDASI).</li>
                                    <li><strong>H1-H4:</strong> Strategi swing jangka panjang.</li>
                                </ul>
                            </div>
                            <div class="glass-card p-6 border-accent/20 accent-glow">
                                <h4 class="text-white font-bold mb-4 flex items-center gap-2">
                                    <i class="fas fa-trophy text-accent"></i> Prop Firm Settings
                                </h4>
                                <p class="text-[10px] text-gray-600 mb-4 italic">Setting khusus untuk lolos Challenge FTMO/MFF:</p>
                                <ul class="text-[11px] text-gray-500 space-y-2 font-mono">
                                    <li>- SL Risk: 1-2%</li>
                                    <li>- Daily Target: 3-5%</li>
                                    <li>- Equity Protection: 5-8%</li>
                                    <li>- Trailing Stop: Aktif</li>
                                </ul>
                            </div>
                        </div>

                        <div class="glass-card p-8 bg-red-600/5 border-red-500/20">
                            <h4 class="text-red-500 font-bold mb-4 uppercase text-xs flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle"></i> Larangan Keras
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-[11px] text-gray-600 italic">
                                <div>- Menjalankan EA tanpda VPS atau koneksi standby.</div>
                                <div>- Terlalu sering Force Close manual di luar tombol EA.</div>
                                <div>- Set SL Risk di atas 10% untuk balance kecil.</div>
                                <div>- Membiarkan EA running saat berita High Impact (NFP).</div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- 10. Troubleshooting -->
                <article id="troubleshooting" class="scroll-mt-header" data-aos="fade-up">
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-white mb-4">10. Troubleshooting</h2>
                        <div class="h-1 w-20 bg-accent rounded-full"></div>
                    </div>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="glass-card p-6 border-white/5">
                            <h5 class="text-white font-bold mb-2">EA Wajah Sedih / Merah?</h5>
                            <p class="text-[10px] text-gray-600 leading-relaxed">Klik menu <strong>Common</strong> di properties EA, centang <strong>"Allow Algo Trading"</strong>.</p>
                        </div>
                        <div class="glass-card p-6 border-white/5">
                            <h5 class="text-white font-bold mb-2">License INVALID Tapi Key Benar?</h5>
                            <p class="text-[10px] text-gray-600 leading-relaxed">Pastikan <strong>WebRequest URL</strong> almai.id sudah ditambahkan di Tools Options.</p>
                        </div>
                        <div class="glass-card p-6 border-white/5">
                            <h5 class="text-white font-bold mb-2">Dashboard Terpotong?</h5>
                            <p class="text-[10px] text-gray-600 leading-relaxed">Resize jendela chart Anda. EA akan auto-scale UI sesuai pixel layar.</p>
                        </div>
                        <div class="glass-card p-6 border-white/5">
                            <h5 class="text-white font-bold mb-2">HELP Button Tidak Buka Browser?</h5>
                            <p class="text-[10px] text-gray-600 leading-relaxed">Wajib mencentang <strong>"Allow DLL imports"</strong> di tab Common Properties EA.</p>
                        </div>
                    </div>
                </article>

                <!-- 11. FAQ -->
                <article id="faq" class="scroll-mt-header" data-aos="fade-up">
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-white mb-4">11. FAQ</h2>
                        <div class="h-1 w-20 bg-accent rounded-full"></div>
                    </div>
                    <div class="space-y-3">
                        <?php 
                        $faqs = [
                            ['q' => 'Berapa modal minimum yang direkomendasikan?', 'a' => 'Minimum $100 (lot 0.01), tapi idealnya $500 - $1000 agar ketahanan margin lebih aman saat layer terbuka.'],
                            ['q' => 'Ada garansi profit tetap harian?', 'a' => 'TIDAK ADA GARANSI. Hasil trading mengikuti market. Gunakan risk management yang bijak.'],
                            ['q' => 'Apakah EA bisa dijalankan di HP?', 'a' => 'Tidak bisa secara langsung. EA hanya jalan di PC/VPS. Anda bisa memonitor hasilnya via aplikasi MT5 di HP.'],
                            ['q' => 'Bagaimana jika saya ingin pindah broker?', 'a' => 'Selama broker mengijinkan penggunaan EA (MT5), Anda bisa memindahkan file EA dan melink-kan license Anda kembali.'],
                            ['q' => 'Bagaimana cara backup setting EA?', 'a' => 'Di tab Inputs, klik tombol **Save** di pojok kanan bawah, beri nama file .set Anda.'],
                            ['q' => 'Apakah support full 24 jam?', 'a' => 'Tim teknis standby pada jam operasional. Diluar jam tersebut, gunakan dokumentasi ini untuk bantuan cepat.'],
                        ];
                        foreach ($faqs as $f): ?>
                            <details class="group glass-card p-6 cursor-pointer list-none overflow-hidden">
                                <summary class="flex justify-between items-center text-white font-bold text-sm">
                                    <span><?= $f['q'] ?></span>
                                    <i class="fas fa-chevron-down group-open:rotate-180 transition-transform"></i>
                                </summary>
                                <div class="mt-4 text-[11px] text-gray-500 leading-relaxed border-t border-white/5 pt-4">
                                    <?= $f['a'] ?>
                                </div>
                            </details>
                        <?php endforeach; ?>
                    </div>
                </article>

                <!-- Checklist -->
                <article id="checklist" class="scroll-mt-header" data-aos="fade-up">
                    <div class="p-10 bg-[#111] rounded-[2rem] border border-accent/20 accent-glow">
                        <h3 class="text-white font-bold mb-6 uppercase text-xs tracking-widest text-accent flex items-center gap-2">
                            <i class="fas fa-clipboard-list"></i> Checklist Sebelum Trading
                        </h3>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <?php 
                            $checklist = [
                                ['icon' => 'fa-file-download', 'text' => 'File .ex5 di folder Experts'],
                                ['icon' => 'fa-volume-up', 'text' => 'File sound di folder Files'],
                                ['icon' => 'fa-globe', 'text' => 'WebRequest almai.id aktif'],
                                ['icon' => 'fa-shield-alt', 'text' => 'License status VALID'],
                                ['icon' => 'fa-smile', 'text' => 'Algo Trading Hijau'],
                                ['icon' => 'fa-vial', 'text' => 'Demo test min. 1 minggu'],
                                ['icon' => 'fa-newspaper', 'text' => 'No News High Impact'],
                                ['icon' => 'fa-dollar-sign', 'text' => 'Margin mencukupi']
                            ];
                            foreach ($checklist as $c): ?>
                                <label class="checklist-item flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" class="w-4 h-4 rounded-md border-white/20 bg-black checked:bg-accent focus:ring-accent transition-all">
                                    <i class="fas <?= $c['icon'] ?> text-accent/40 group-hover:text-accent transition-colors text-[10px]"></i>
                                    <span class="text-[11px] text-gray-400 group-hover:text-white transition-all"><?= $c['text'] ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </article>

                <!-- Disclaimer -->
                <article id="disclaimer" class="scroll-mt-header" data-aos="fade-up">
                    <div class="p-8 bg-red-600/5 border border-red-600/20 rounded-[2rem]">
                        <h3 class="text-white font-bold mb-4 uppercase text-[10px] tracking-widest flex items-center gap-2">
                            <i class="fas fa-gavel text-red-500"></i> Penting - Harap Dibaca
                        </h3>
                        <div class="grid gap-4 text-[10px] text-gray-600 italic leading-relaxed">
                            <p><strong>1. Risiko Trading:</strong> Trading derivatif mengandung risiko tinggi. Anda bisa kehilangan seluruh modal yang disetorkan.</p>
                            <p><strong>2. Tidak Ada Garansi:</strong> Kami tidak menjamin profit harian maupun bulanan. Masa lalu tidak menjamin kinerja masa depan.</p>
                            <p><strong>3. Tanggung Jawab:</strong> PT ALMA INDONESIA RAYA tidak bertanggung jawab atas segala kerugian finansial yang Anda alami.</p>
                        </div>
                    </div>
                </article>

                <!-- Quick Start Guide -->
                <article id="quick-start" class="scroll-mt-header" data-aos="fade-up">
                    <div class="p-10 bg-accent rounded-[2rem] text-black">
                        <h3 class="font-black text-2xl uppercase tracking-tighter mb-6 flex items-center gap-3 italic">
                            <i class="fas fa-rocket"></i> Quick Start: 5 Menit Setup
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <div class="flex gap-4"><span class="font-black">01</span><p class="text-[11px] font-bold">Copy EA ke folder Experts & Sound ke folder Files. Restart MT5.</p></div>
                                <div class="flex gap-4"><span class="font-black">02</span><p class="text-[11px] font-bold">Aktifkan "Allow WebRequest" dan add URL almai.id di Tools Options.</p></div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex gap-4"><span class="font-black">03</span><p class="text-[11px] font-bold">Buka Chart XAUUSD, Drag EA, Input License Key Anda.</p></div>
                                <div class="flex gap-4"><span class="font-black">04</span><p class="text-[11px] font-bold">Klik tombol Algo Trading (Wajib Smiley Hijau <i class="fas fa-smile"></i>). Selesai!</p></div>
                            </div>
                        </div>
                        <div class="mt-10 pt-6 border-t border-black/10 text-center">
                            <p class="text-[9px] font-bold tracking-widest uppercase">EA Siap Trading Otomatis Sekarang!</p>
                        </div>
                    </div>
                </article>

                <div class="text-center pt-10 pb-20">
                    <p class="text-[9px] text-gray-700 uppercase tracking-widest font-black">&copy; 2026 PT. ALMA INDONESIA RAYA - PRESTIGE DOCUMENTATION SYSTEM</p>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sections = document.querySelectorAll('article');
        const navLinks = document.querySelectorAll('#doc-nav a');

        function setActiveLink() {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (window.pageYOffset >= sectionTop - 250) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').substring(1) === current) {
                    link.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', setActiveLink);
        setActiveLink();
    });
</script>
<?= $this->endSection() ?>
