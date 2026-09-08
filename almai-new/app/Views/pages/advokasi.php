<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* TIMELINE STYLES SYNCED WITH WPA DETAIL */
    .phase-timeline-wpa {
        display: flex;
        flex-direction: column;
        gap: 0;
        padding: 1rem 0;
    }

    .phase-item-wpa {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        gap: 1rem;
        position: relative;
        padding-bottom: 2rem;
        width: 100%;
        text-align: left;
    }

    .phase-item-wpa:last-child {
        padding-bottom: 0;
    }

    .phase-circle-wpa {
        position: relative;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #1a1a1a;
        border: 2px solid #2a2a2a;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.3s;
        z-index: 10;
    }

    .phase-connector-wpa {
        position: absolute;
        background: #2a2a2a;
        transition: background 0.3s;
        z-index: 0;
        top: 40px;
        left: 20px;
        width: 2px;
        height: calc(100% - 40px);
        transform: translateX(-50%);
    }

    .phase-title-wpa {
        font-size: 0.9rem;
        color: #888;
        padding-top: 0.5rem;
        font-weight: 500;
        line-height: 1.2;
    }

    .phase-connector-wpa.active {
        background: #33E818;
    }

    .phase-item-wpa.active .phase-circle-wpa {
        background: #1a1a1a;
        border-color: #33E818;
        box-shadow: 0 0-15px rgba(51, 232, 24, 0.3);
    }

    .phase-item-wpa.completed .phase-circle-wpa {
        border-color: #33E818;
    }

    .phase-item-wpa.completed .phase-circle-wpa .icon {
        color: #33E818;
    }

    .phase-item-wpa.active .phase-title-wpa {
        color: #33E818;
        font-weight: 700;
    }

    .phase-item-wpa.completed .phase-title-wpa {
        color: #fff;
    }

    .phase-circle-wpa .icon {
        font-size: 0.9rem;
        color: #666;
    }

    .running-gif {
        width: 24px;
        height: 24px;
        object-fit: contain;
    }

    @media (min-width: 1024px) {
        .phase-timeline-wpa {
            flex-direction: row;
            overflow-x: auto;
            padding-bottom: 20px;
            gap: 0;
            justify-content: center;
        }

        .phase-item-wpa {
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 180px;
            padding-bottom: 0;
            flex-shrink: 0;
            gap: 0.5rem;
        }

        .phase-circle-wpa {
            width: 60px;
            height: 60px;
            border-width: 3px;
        }

        .phase-connector-wpa {
            top: 30px;
            left: 50%;
            width: 100%;
            height: 3px;
            transform: none;
        }

        .phase-title-wpa {
            font-size: 0.85rem;
            padding-top: 0;
            min-height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .phase-circle-wpa .icon {
            font-size: 1.25rem;
        }

        .running-gif {
            width: 35px;
            height: 35px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- SECTION 1 - HERO -->
<section class="relative min-h-screen flex items-center justify-center py-16 sm:py-18 md:py-20 overflow-hidden bg-black">

    <!-- Grid Pattern -->
    <div class="absolute inset-0 bg-grid z-[1] pointer-events-none opacity-50"></div>
    
    <!-- Glow Effect -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 
                w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full 
                pointer-events-none z-[1]"></div>

    <!-- CONTENT -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">

        <div class="flex flex-col items-center gap-3 sm:gap-4 md:gap-5 lg:gap-6">

<!-- WRAPPER -->
<div class="flex flex-col items-center translate-y-6 sm:translate-y-8 md:translate-y-10">
    <?php if (isset($referralWpa) && $referralWpa): ?>
        <!-- Referral Badge -->
        <div class="inline-flex items-center gap-2 bg-white/5 backdrop-blur-md px-3 py-1.5 rounded-full border border-accent/30 mb-1 mt-2" data-aos="fade-down" data-aos-delay="400">
            <?php 
                $photo = $referralWpa['photo'];
                if ($photo && strpos($photo, 'http') !== 0) {
                    $photo = base_url('file/' . ltrim(preg_replace('/^writable\//', '', $photo), '/'));
                }
            ?>
            <img src="<?= $photo ?: base_url('images/almai-full.png') ?>" alt="<?= esc($referralWpa['name']) ?>" class="w-7 h-7 rounded-full border border-accent/50 object-cover">
            <div class="flex flex-col items-start leading-none">
                <span class="text-[10px] text-accent font-bold uppercase tracking-widest"><?= esc($referralType ?? 'WPA') ?></span>
                <span class="text-xs text-white font-black"><?= esc($referralWpa['name']) ?></span>
            </div>
        </div>
    <?php endif; ?>

<!-- BADGE -->
<div class="inline-block mt-4 sm:mt-5 md:mt-6 mb-0.5" data-aos="fade-down">
    <span class="bg-black border border-accent/30 text-accent font-bold 
                 px-5 py-1.5 rounded-full uppercase tracking-widest text-xs 
                 flex items-center gap-2">
        <i class="fas fa-radiation"></i>PROGRAM
    </span>
</div>

    <!-- TITLE -->
    
    <h1 
        class="text-5xl sm:text-7xl md:text-9xl lg:text-[10rem] xl:text-[11rem] 
               font-black leading-tight tracking-tight text-white
               -mt-1 sm:-mt-2"
        data-aos="fade-up"
    >
        <span class="blur-text-container">
            <span>A</span><span>D</span><span>V</span><span>O</span><span>K</span><span>A</span><span>S</span><span>I</span>
        </span>
    </h1>

    <!-- SUBTITLE -->
    <p 
        class="text-sm sm:text-base md:text-xl lg:text-2xl 
               text-gray-300 font-semibold 
               max-w-2xl sm:max-w-5xl lg:max-w-6xl mx-auto 
               leading-snug tracking-wide
               -mt-2 sm:-mt-3 md:-mt-4
               whitespace-normal lg:whitespace-nowrap"
    >
        Perdagangan Derivatif & Aset Keuangan Digital
    </p>

</div>

            <!-- QUOTE -->
            <p 
                class="text-sm sm:text-base md:text-lg lg:text-xl 
                       text-gray-400 
                       max-w-3xl sm:max-w-5xl lg:max-w-6xl mx-auto 
                       leading-snug lg:leading-relaxed px-2 -mt-1"
            >
                <span class="text-white italic">
                    "Program resmi berbasis edukasi, pendampingan, dan perlindungan hukum bagi trader Forex, Crypto, dan Derivatif yang terintegrasi dengan regulator serta ekosistem industri."
                </span>
            </p>

            <!-- CTA -->
            <div 
                class="flex flex-col sm:flex-row items-center justify-center 
                       gap-2 sm:gap-3 pt-2"
                data-aos="fade-up" 
                data-aos-delay="200"
            >
                <?php 
                $refCode = (isset($referralWpa) && $referralWpa && !empty($referralWpa['code_referral'])) ? $referralWpa['code_referral'] : '';
                $refUrl = !empty($refCode) ? '?ref=' . urlencode($refCode) : '';
                $targetUrl = base_url('daftar-advokasi' . $refUrl);
                ?>
                <a href="<?= $targetUrl ?>" 
                   class="w-full sm:w-auto px-8 py-4 bg-accent text-black font-bold rounded-xl 
                          hover:bg-green-500 transition-all transform hover:scale-105 
                          shadow-[0_0_20px_rgba(51,232,24,0.3)] flex items-center justify-center gap-2">
                    <i class="fas fa-rocket"></i> Daftar Advokasi
                </a>

                <a href="https://ceklegalitas.bappebti.go.id/" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="w-full sm:w-auto px-8 py-4 bg-accent/10 text-accent font-bold rounded-xl border border-accent/20 hover:bg-accent hover:text-black transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-search-dollar"></i> Cek Broker Anda
                </a>
            </div>

            <!-- Social Proof -->
            <div class="mt-8 flex flex-col md:flex-row items-center justify-center gap-4" data-aos="fade-up" data-aos-delay="300">
                
                <div class="flex items-center gap-3 px-5 py-2 bg-white/5 backdrop-blur-md rounded-full border border-white/10 shadow-lg hover:bg-white/10 transition duration-300">
                    <div class="flex -space-x-3">
                        <?php for ($i = 0; $i < 3; $i++): ?>
                            <div class="w-9 h-9 rounded-full border-2 border-black flex items-center justify-center text-sm text-white font-bold transition-all duration-500" id="badge-initial-<?= $i ?>" style="background: linear-gradient(135deg, #33e818 0%, #1a7a0c 100%);"></div>
                        <?php endfor; ?>
                    </div>
                    <div class="flex flex-col items-start justify-center">
                        <p class="text-[9px] uppercase tracking-wider text-gray-400 font-bold leading-none">Total Users</p>
                        <p class="text-xl sm:text-2xl text-accent font-black leading-none">
                            <?= number_format($totalUsers ?? 0) ?>+
                        </p>
                    </div>
                </div>

                <div id="testimony-container" class="flex items-center gap-3 px-6 py-2 bg-white/5 backdrop-blur-md rounded-full border border-white/10 shadow-lg hover:bg-white/10 transition-all duration-300 w-full max-w-[290px] h-[90px] overflow-hidden">
                    <div class="flex flex-col items-start w-full h-full justify-center">
                        <div class="flex items-center justify-between w-full mb-0.5">
                            <span id="testimony-name" class="text-white font-bold text-[10px] tracking-tight uppercase">Andi Wijaya</span>
                            <div class="flex items-center gap-0.5 text-[6px] text-yellow-500">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                        </div>
                        <p id="testimony-text" class="text-[9px] text-gray-400 font-medium leading-[1.2] italic w-full text-left">
                            Bimbingan Almai sangat membantu saya<br>memahami market dengan lebih objektif.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Toast -->
            <div class="flex justify-center mt-1">
                <div id="user-toast" class="h-7 flex items-center gap-2 transition-all duration-500 opacity-0 transform translate-y-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                    <p class="text-[10px] text-gray-400">
                        <span id="toast-name" class="font-bold text-white text-sm">User</span> <span class="opacity-70">baru bergabung</span>
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- SECTION - STORY FAQ -->
<section class="py-20 relative bg-[#0a0a0a] border-t border-white/5 overflow-hidden">
    <div class="container mx-auto px-6 max-w-4xl">

        <!-- HEADER -->
        <div class="text-center mb-14 max-w-3xl mx-auto" data-aos="fade-up">
            
            <h3 class="text-red-500 font-semibold uppercase tracking-widest text-sm mb-3">
                Realita Dunia Trading
            </h3>
            <h2 class="text-2xl md:text-4xl font-bold text-white mb-5 leading-snug">
                "Industri berkembang sangat cepat, —
                <span class="text-red-500">namun perlindungan trader nyaris diam di tempat."</span>
            </h2><br>
            <p class="text-gray-400 text-sm md:text-base leading-relaxed">
                Di balik aktivitas trading yang terlihat “normal”, terdapat berbagai risiko tersembunyi yang sering tidak disadari trader — <span class="text-white font-semibold">mulai dari broker bermasalah, manipulasi sistem, hingga tidak adanya perlindungan saat terjadi sengketa.</span>
                <br><br>
                Berikut adalah alur yang paling sering terjadi — <span class="text-white font-semibold">dari awal masuk hingga akhirnya, buntu!</span>
            </p>
        </div>
        <br>

        <!-- STORY FLOW -->
        <div class="space-y-4">

            <!-- 1 -->
            <details class="group bg-black border border-red-500/20 rounded-xl p-5">
                <summary class="flex justify-between items-center cursor-pointer text-white font-semibold">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11a2 2 0 100-4 2 2 0 000 4zM15.5 10.3l1.9-1.9M19 6c0-1.12-.88-2-2-2a2 2 0 00-2 2M15 9.5c0-.828.672-1.5 1.5-1.5a1.5 1.5 0 010 3M11 19a8 8 0 100-16 8 8 0 000 16z"></path></svg>
                        <span>1. Masuk ke Platform yang Salah</span>
                    </span>
                    <span class="group-open:rotate-180 transition">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </summary>
                <p class="text-gray-400 mt-3 text-sm">
                    Tanpa sadar, Anda trading di platform yang tidak transparan. Harga terasa aneh, spread melebar, 
                    dan posisi sering terkena stop tanpa alasan jelas. Anda mulai curiga… tapi belum yakin.
                </p>
            </details>

            <!-- 2 -->
            <details class="group bg-black border border-red-500/20 rounded-xl p-5">
                <summary class="flex justify-between items-center cursor-pointer text-white font-semibold">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>2. Profit Ada. Tapi Tidak Bisa Ditarik</span>
                    </span>
                    <span class="group-open:rotate-180 transition">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </summary>
                <p class="text-gray-400 mt-3 text-sm">
                    Profit terlihat di akun, namun saat withdraw muncul berbagai kendala: verifikasi berbelit, 
                    penarikan ditunda, hingga akun dibatasi sepihak. Saat itu Anda sadar — uang Anda tidak benar-benar Anda miliki.
                </p>
            </details>

            <!-- 3 -->
            <details class="group bg-black border border-red-500/20 rounded-xl p-5">
                <summary class="flex justify-between items-center cursor-pointer text-white font-semibold">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"></path></svg>
                        <span>3. Hak Anda Diabaikan</span>
                    </span>
                    <span class="group-open:rotate-180 transition">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </summary>
                <p class="text-gray-400 mt-3 text-sm">
                    Komisi referral tidak dibayar, dipotong sepihak, atau hilang tanpa penjelasan. 
                    Tidak ada transparansi, tidak ada kejelasan.
                </p>
            </details>

            <!-- 4 -->
            <details class="group bg-black border border-red-500/20 rounded-xl p-5">
                <summary class="flex justify-between items-center cursor-pointer text-white font-semibold">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                        <span>4. Trading Tanpa Arah</span>
                    </span>
                    <span class="group-open:rotate-180 transition">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </summary>
                <p class="text-gray-400 mt-3 text-sm">
                    Ganti strategi, ikut sinyal, coba indikator — tapi tanpa pendampingan, 
                    tidak ada evaluasi dan sistem. Anda hanya mengulang kesalahan yang sama dengan cara berbeda.
                </p>
            </details>

            <!-- 5 -->
            <details class="group bg-black border border-red-500/20 rounded-xl p-5">
                <summary class="flex justify-between items-center cursor-pointer text-white font-semibold">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                        <span>5. Tidak Paham Regulasi</span>
                    </span>
                    <span class="group-open:rotate-180 transition">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </summary>
                <p class="text-gray-400 mt-3 text-sm">
                    Anda mulai bertanya soal legalitas — tapi sudah terlambat. 
                    Tidak tahu mana broker resmi, mana ilegal, dan kemana harus mengadu.
                </p>
            </details>

            <!-- 6 -->
            <details class="group bg-black border border-red-500/20 rounded-xl p-5">
                <summary class="flex justify-between items-center cursor-pointer text-white font-semibold">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.99 7.99 0 0121 13a8.001 8.001 0 01-3.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14a4.5 4.5 0 003.111 4.110"></path></svg>
                        <span>6. Terjebak Scam & Rug Pull (Crypto)</span>
                    </span>
                    <span class="group-open:rotate-180 transition">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </summary>
                <p class="text-gray-400 mt-3 text-sm">
                    Project terlihat meyakinkan — komunitas ramai, influencer bicara. 
                    Tapi tiba-tiba developer hilang, likuiditas lenyap, dan harga jatuh ke nol.
                </p>
            </details>

            <!-- 7 -->
            <details class="group bg-black border border-red-500/20 rounded-xl p-5">
                <summary class="flex justify-between items-center cursor-pointer text-white font-semibold">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <span>7. Aset Hilang Karena Hack & Phishing</span>
                    </span>
                    <span class="group-open:rotate-180 transition">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </summary>
                <p class="text-gray-400 mt-3 text-sm">
                    Satu klik link salah atau approval wallet yang tidak dipahami — 
                    aset Anda bisa hilang dalam hitungan detik tanpa bisa dibatalkan.
                </p>
            </details>

            <!-- 8 -->
            <details class="group bg-black border border-red-500/20 rounded-xl p-5">
                <summary class="flex justify-between items-center cursor-pointer text-white font-semibold">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        <span>8. Buntu Mengadu</span>
                    </span>
                    <span class="group-open:rotate-180 transition">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </summary>
                <p class="text-gray-400 mt-3 text-sm">
                    Saat masalah terjadi, tidak ada tempat pengaduan yang benar-benar independen, 
                    responsif, dan membela trader. Anda terjebak tanpa solusi.
                </p>
            </details>

        </div>

        <!-- CLOSING -->
        <div class="text-center mt-14 max-w-4xl mx-auto">
            <p class="text-gray-400 text-sm md:text-base leading-relaxed">
                <br>
                
                Trading bukan hanya soal skill atau strategi. Banyak Trader Gagal, Kehilangan Uang,
Bahkan Putus Asa? Masalah terbesar trader bukan di market,
                <span class="text-white font-semibold">
                    tapi pada sistem yang tidak melindungi mereka.
                </span>
            </p>
        </div>

    </div>
</section>

<!-- SECTION 4 & 5 - FRAMEWORK MODUL CORE -->
<section class="py-24 relative bg-[#0a0a0a] border-t border-white/10 border-b overflow-hidden">
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-accent/10 blur-[150px] rounded-full pointer-events-none"></div>

    <div class="container mx-auto px-6 max-w-6xl relative z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-accent font-bold tracking-widest text-xs uppercase mb-2 block">PROGRAM FRAMEWORK</span>
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">Kurikulum <span class="text-accent">Advokasi</span></h2>
            <p class="text-gray-400 max-w-4xl mx-auto">Sistem terstruktur yang dirancang untuk memastikan trader tidak hanya belajar trading, 
            tetapi juga terlindungi dari risiko industri — <span class="text-white font-semibold">dari awal hingga saat terjadi masalah.</p>
        </div>

        <div class="bg-[#111] border border-white/10 rounded-3xl p-8 md:p-12 mb-16">
            <h3 class="text-lg font-bold text-white mb-10 flex items-center gap-3">
                <i class="fas fa-calendar-alt text-accent"></i>
                <span>Timeline Program <br>(Webinar Rutin)</span>
            </h3>

            <div class="phase-timeline-wpa">
                <?php
                $currentDayNum = date('N'); // 1 (Mon) - 7 (Sun)
                
                // New logic: 
                // Sun (7), Mon (1) -> Program is Monday (1)
                // Tue (2), Wed (3) -> Program is Wednesday (2)
                // Thu (4), Fri (5), Sat (6) -> Program is Saturday (3)
                if ($currentDayNum == 7 || $currentDayNum == 1) {
                    $currentPhase = 1;
                } elseif ($currentDayNum == 2 || $currentDayNum == 3) {
                    $currentPhase = 2;
                } else {
                    $currentPhase = 3;
                }

                $phases = [
                    ['number' => 1, 'title' => 'SENIN | STEP 1', 'days' => [7, 1]],
                    ['number' => 2, 'title' => 'RABU | STEP 2', 'days' => [2, 3]],
                    ['number' => 3, 'title' => 'SABTU | STEP 3', 'days' => [4, 5, 6]],
                ];

                foreach ($phases as $index => $phase):
                    $isActive = $phase['number'] == $currentPhase;
                    $isCompleted = $phase['number'] < $currentPhase;
                    $statusClass = $isActive ? 'active' : ($isCompleted ? 'completed' : '');
                ?>
                    <div class="phase-item-wpa <?= $statusClass ?>">
                        <?php if ($index < count($phases) - 1): ?>
                            <div class="phase-connector-wpa <?= $isCompleted ? 'active' : '' ?>"></div>
                        <?php endif; ?>

                        <div class="phase-circle-wpa">
                            <?php if ($isCompleted): ?>
                                <i class="fas fa-graduation-cap icon"></i>
                            <?php elseif ($isActive): ?>
                                <img src="<?= base_url('images/lari.gif') ?>" alt="Running" class="running-gif">
                            <?php else: ?>
                                <div class="icon"><?= $phase['number'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="phase-title-wpa">
                            <?= esc($phase['title']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


        <!-- FRAMEWORK GRID -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">

            <!-- STEP 1 -->
            <div class="bg-black/40 border border-accent/20 rounded-2xl p-6 hover:scale-105 transition-all text-left">
                <span class="text-accent text-xs font-bold tracking-widest">STEP 1</span>

                <h3 class="text-white font-bold text-lg mb-3 mt-2">
                    Protect — Legalitas & Keamanan
                </h3>

                <p class="text-gray-400 text-sm mb-4">
                    Memastikan Anda masuk ke platform yang benar sebelum trading dimulai.
                </p>

                <ul class="text-gray-400 text-sm space-y-2">
                    <li>• Validasi broker & legalitas</li>
                    <li>• Identifikasi scam & manipulasi</li>
                    <li>• Pemahaman regulator</li>
                </ul>

                <p class="text-red-400 text-xs mt-4">
                    Menghindari: broker ilegal, rug pull, penipuan
                </p>
                                <p class="text-white text-xs font-semibold mt-2">
                    🎯 Outcome: Aman memilih platform & terhindar dari penipuan
                </p>
            </div>

            <!-- STEP 2 -->
            <div class="bg-black/40 border border-accent/20 rounded-2xl p-6 hover:scale-105 transition-all text-left">
                <span class="text-accent text-xs font-bold tracking-widest">STEP 2</span>

                <h3 class="text-white font-bold text-lg mb-3 mt-2">
                    Understand — Struktur Market
                </h3>

                <p class="text-gray-400 text-sm mb-4">
                    Memahami bagaimana market benar-benar bekerja agar tidak terjebak ilusi.
                </p>

                <ul class="text-gray-400 text-sm space-y-2">
                    <li>• Probabilitas trading</li>
                    <li>• Likuiditas & price movement</li>
                    <li>• Struktur market</li>
                </ul>

                <p class="text-red-400 text-xs mt-4">
                    Menghindari: trading tanpa arah, edukasi menyesatkan
                </p>
                                <p class="text-white text-xs font-semibold mt-2">
                    Outcome: Paham market secara rasional & terukur
                </p>
            </div>

            <!-- STEP 3 -->
            <div class="bg-black/40 border border-accent/20 rounded-2xl p-6 hover:scale-105 transition-all text-left">
                <span class="text-accent text-xs font-bold tracking-widest">STEP 3</span>

                <h3 class="text-white font-bold text-lg mb-3 mt-2">
                    Control — Risk & Mindset
                </h3>

                <p class="text-gray-400 text-sm mb-4">
                    Membangun sistem trading yang disiplin dan mampu melindungi modal.
                </p>

                <ul class="text-gray-400 text-sm space-y-2">
                    <li>• Risk management</li>
                    <li>• Psikologi trading</li>
                    <li>• Konsistensi & disiplin</li>
                    <li>• Mindset risk-aware trader</li>
                </ul>

                <p class="text-red-400 text-xs mt-4">
                    Menghindari: loss berulang, tanpa sistem
                </p>
                                <p class="text-white text-xs font-semibold mt-2">
                    🎯 Outcome: Konsisten, disiplin & mampu melindungi aset
                </p>
            </div>

            <!-- STEP 4 -->
            <div class="bg-gradient-to-br from-red-900/40 to-black border border-red-500/30 rounded-2xl p-6 shadow-xl text-left relative overflow-hidden">

                <div class="absolute top-0 right-0 p-4 opacity-200">
                    <i class="fas fa-radiation text-7xl text-green-500"></i>
                </div>

                <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded tracking-widest">
                    ADVANCED
                </span>

                <h3 class="text-white font-bold text-lg mb-3 mt-2">
                    Defend — Advokasi & Sengketa
                </h3>

                <p class="text-red-300 text-sm mb-4">
                    Saat masalah terjadi, Anda tidak sendirian — ada sistem untuk melindungi Anda.
                </p>

                <ul class="text-gray-300 text-sm space-y-2">
                    <li>• Kasus WD ditahan / ditolak</li>
                    <li>• Manipulasi platform</li>
                    <li>• Akun diblokir sepihak</li>
                    <li>• Jalur penyelesaian sengketa</li>
                </ul>

                <p class="text-red-400 text-xs mt-4">
                    Menjawab: broker curang, dana tertahan, buntu mengadu
                </p>

                <p class="text-white text-xs font-semibold mt-2">
                    🎯 Outcome: Siap menghadapi & menyelesaikan masalah nyata
                </p>
            </div>

        </div>
        <!-- CLOSING -->
        <div class="text-center mt-14 max-w-4xl mx-auto">
            <p class="text-gray-400 text-sm md:text-base leading-relaxed">
                <br>Framework ini memastikan Anda memiliki sesuatu yang lebih penting:
                <span class="text-white font-semibold">
                    sistem untuk bertahan, melindungi, dan menang dalam jangka panjang.
                </span>
            </p>
        </div>

    </div>
</section>


<!-- SECTION 6 & 8 - TARGET, SCALE & IMPACT -->
<section class="py-20 bg-black overflow-hidden">
    <div class="container mx-auto px-6 max-w-5xl text-center">
        <h2 class="text-3xl font-bold text-white mb-16 uppercase tracking-widest" data-aos="fade-up">Menuju Gerakan <span class="text-accent">Nasional</span></h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-16">
            <div class="p-4 md:p-6 border border-white/5 rounded-2xl bg-[#0a0a0a] hover:bg-accent/5 transition duration-500" data-aos="flip-left" data-aos-delay="0">
                <i class="fas fa-users text-3xl md:text-4xl text-accent mb-3 md:mb-4"></i>
                <h3 class="text-xl md:text-2xl font-black text-white mb-1">100K+</h3>
                <p class="text-[10px] md:text-xs text-gray-400 uppercase">Target Peserta Nasional</p>
            </div>
            <div class="p-4 md:p-6 border border-white/5 rounded-2xl bg-[#0a0a0a] hover:bg-accent/5 transition duration-500" data-aos="flip-left" data-aos-delay="100">
                <i class="fas fa-arrow-trend-down text-3xl md:text-4xl text-accent mb-3 md:mb-4"></i>
                <h3 class="text-xl md:text-2xl font-black text-white mb-1">↓ Risiko</h3>
                <p class="text-[10px] md:text-xs text-gray-400 uppercase">Penurunan Korban Investasi Bodong</p>
            </div>
            <div class="p-4 md:p-6 border border-white/5 rounded-2xl bg-[#0a0a0a] hover:bg-accent/5 transition duration-500" data-aos="flip-left" data-aos-delay="200">
                <i class="fas fa-handshake text-3xl md:text-4xl text-accent mb-3 md:mb-4"></i>
                <h3 class="text-xl md:text-2xl font-black text-white mb-1">Kolaborasi</h3>
                <p class="text-[10px] md:text-xs text-gray-400 uppercase">Regulator, Kampus & Korporat</p>
            </div>
            <div class="p-4 md:p-6 border border-white/5 rounded-2xl bg-[#0a0a0a] hover:bg-accent/5 transition duration-500" data-aos="flip-left" data-aos-delay="300">
                <i class="fas fa-globe-asia text-3xl md:text-4xl text-accent mb-3 md:mb-4"></i>
                <h3 class="text-xl md:text-2xl font-black text-white mb-1">Ekosistem</h3>
                <p class="text-[10px] md:text-xs text-gray-400 uppercase">Aman | Transparan | Terlindungi</p>
            </div>
        </div>
    </div>

<section class="py-20 bg-[#0a0a0a] relative border-t border-white/10 overflow-hidden">
    <div class="container mx-auto px-6 max-w-5xl">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            
            <div data-aos="fade-right">
                <h2 class="text-2xl font-bold text-white mb-6">Format Program yang <span class="text-accent">Mudah Diakses</span></h2>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 bg-[#111] p-4 rounded-xl border border-white/5">
                        <div class="w-10 h-10 rounded-full bg-accent/20 text-accent flex items-center justify-center shrink-0"><i class="fas fa-video"></i></div>
                        <p class="text-sm text-gray-300 font-medium">Webinar Nasional interaktif via Zoom/Youtube Live</p>
                    </div>
                    <div class="flex items-center gap-4 bg-[#111] p-4 rounded-xl border border-white/5">
                        <div class="w-10 h-10 rounded-full bg-accent/20 text-accent flex items-center justify-center shrink-0"><i class="fab fa-tiktok"></i></div>
                        <p class="text-sm text-gray-300 font-medium">Micro-learning Content via TikTok, IG Reels, & YouTube</p>
                    </div>
                    <div class="flex items-center gap-4 bg-[#111] p-4 rounded-xl border border-white/5">
                        <div class="w-10 h-10 rounded-full bg-accent/20 text-accent flex items-center justify-center shrink-0"><i class="fas fa-book"></i></div>
                        <p class="text-sm text-gray-300 font-medium">Akses E-book Modul PDF Gratifikatif & Komprehensif</p>
                    </div>
                    <div class="flex items-center gap-4 bg-[#111] p-4 rounded-xl border border-white/5">
                        <div class="w-10 h-10 rounded-full bg-accent/20 text-accent flex items-center justify-center shrink-0"><i class="fas fa-briefcase"></i></div>
                        <p class="text-sm text-gray-300 font-medium">Review & Bedah Studi Kasus Sengketa Nyata</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-900/20 to-black p-6 md:p-8 rounded-3xl border border-accent/20 text-center" data-aos="fade-left">
                <img src="<?= base_url('images/almai-full.png') ?>" alt="Almai" class="h-10 mx-auto mb-6 opacity-80" onerror="this.src=''; this.className='hidden'">
                <h3 class="text-xl font-bold text-white mb-4">The First Trading Protection<br>Ecosystem in Indonesia</h3>
                <p class="text-sm text-gray-400 leading-relaxed mb-6">
                    Almai secara konsisten memposisikan dirinya tidak hanya sebagai penyedia teknologi Expert Advisor bersertifikasi, tetapi pelopor ekosistem pelindung literasi trader skala nasional.
                </p>
                <a href="<?= base_url('about') ?>" class="text-accent text-sm font-bold hover:underline inline-flex items-center gap-2">Pelajari Legalitas Kami <i class="fas fa-chevron-right text-xs"></i></a>
            </div>

        </div>
    </div>
</section>

<!-- SECTION: PRICING -->
<section class="py-12 border-t border-white/10">
    <div class="container mx-auto px-6 max-w-4xl text-center">
        <div class="bg-[#111] border border-white/10 rounded-3xl p-8 relative overflow-hidden">

            <h2 class="text-2xl md:text-4xl font-bold text-white mb-4">Pricing <span class="text-accent">Membership</span></h2>
            <p class="text-gray-400 text-sm mb-6 max-w-lg mx-auto">Dapatkan akses penuh ke seluruh ekosistem perlindungan dan edukasi Almai</p>

            <div class="mb-6">
                <div class="flex justify-center items-baseline gap-2 mb-2">
                    <span class="text-5xl md:text-7xl font-black text-white">Rp 88.000</span>
                    <span class="text-xl text-gray-500 font-bold">/ tahun</span>
                </div>
                <p class="text-accent font-bold text-xs tracking-widest uppercase">Investasi Literasi Terbaik</p>
            </div>

            <div class="grid sm:grid-cols-2 gap-y-4 gap-x-8 max-w-2xl mx-auto mb-6 text-left bg-black/30 p-6 rounded-2xl border border-white/5">
                <div class="flex items-center gap-3 text-gray-300">
                    <i class="fas fa-check-circle text-accent text-sm"></i>
                    <span class="text-sm">Membership tahunan</span>
                </div>
                <div class="flex items-center gap-3 text-gray-300">
                    <i class="fas fa-check-circle text-accent text-sm"></i>
                    <span class="text-sm">Modul E-learning Premium</span>
                </div>
                <div class="flex items-center gap-3 text-gray-300">
                    <i class="fas fa-check-circle text-accent text-sm"></i>
                    <span class="text-sm">Webinar Rutin & Mentorship</span>
                </div>
                <div class="flex items-center gap-3 text-gray-300">
                    <i class="fas fa-check-circle text-accent text-sm"></i>
                    <span class="text-sm">Konsultasi Bedah Kasus</span>
                </div>
                <div class="flex items-center gap-3 text-gray-300">
                    <i class="fas fa-check-circle text-accent text-sm"></i>
                    <span class="text-sm">Akun Almai Portofolio</span>
                </div>
                <div class="flex items-center gap-3 text-gray-300">
                    <i class="fas fa-check-circle text-accent text-sm"></i>
                    <span class="text-sm">Almai Poin</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4" data-aos="fade-up" data-aos-delay="200">
                <?php 
                $footerRefCode = (isset($referralWpa) && $referralWpa && !empty($referralWpa['code_referral'])) ? $referralWpa['code_referral'] : '';
                $footerRefUrl = !empty($footerRefCode) ? '?ref=' . urlencode($footerRefCode) : '';
                $footerTargetUrl = base_url('daftar-advokasi' . $footerRefUrl);
                ?>
                <a href="<?= $footerTargetUrl ?>" 
                   class="w-full sm:w-auto px-8 py-4 bg-accent text-black font-bold rounded-xl 
                          hover:bg-green-500 transition-all transform hover:scale-105 
                          shadow-[0_0_20px_rgba(51,232,24,0.3)] flex items-center justify-center gap-2">
                    <i class="fas fa-rocket"></i> Daftar Advokasi
                </a>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Pass user names only (no avatars, using initials)
        let recentUsers = <?= json_encode(array_map(function ($u) {
                                return [
                                    'name' => $u['name'],
                                    'isNew' => false // Default flag
                                ];
                            }, $recentUsers ?? [])) ?>;

        // Current total tracked in JS
        let currentTotalInfo = <?= $totalUsers ?? 0 ?>;

        const toastEl = document.getElementById('user-toast');
        const nameEl = document.getElementById('toast-name');
        const statusEl = document.querySelector('#user-toast .opacity-70'); // Span for "baru/sudah bergabung"

        // Dynamic Testimonials Data
        const testimonials = [
            { name: "Andi Wijaya", text: "Bimbingan Almai sangat membantu saya<br>memahami market dengan lebih objektif." },
            { name: "Siti Nurhaliza", text: "WPA di sini sangat sabar dan detail<br>dalam menjelaskan strategi trading." },
            { name: "Budi Santoso", text: "Fitur trailing stop-nya bener-bener<br>kasih ketenangan saat trading." },
            { name: "Dewi Lestari", text: "Platform trading dengan legalitas jelas<br>dan mentornya sangat kompeten." },
            { name: "Reza Pahlevi", text: "Belajar jadi lebih fun dan nggak pusing<br>berkat pendampingan WPA Almai." }
        ];

        const testimonyNameEl = document.getElementById('testimony-name');
        const testimonyTextEl = document.getElementById('testimony-text');
        
        let index = 0;
        let testimonyIndex = 0;
        let loopTimeout;

        if (recentUsers.length > 0) {
            // Update Badge Initial State
            updateBadgeAvatars(0);

            // Initial show toast
            loopTimeout = setTimeout(() => showNextUser(), 1000);

            // POLL for new users every 60 seconds to reduce server load
            setInterval(checkForUpdates, 60000);

            function checkForUpdates() {
                fetch('<?= base_url('home/stats') ?>')
                    .then(res => res.json())
                    .then(data => {
                        if (data.total > currentTotalInfo) {
                            // NEW USER DETECTED!
                            currentTotalInfo = data.total;

                            // Update Total Count Text
                            const totalCountEl = document.querySelector('.text-2xl.text-accent');
                            if (totalCountEl) totalCountEl.innerText = new Intl.NumberFormat().format(data.total) + '+';

                            // Add new user to top of list
                            const newUser = {
                                name: data.latest.name,
                                avatar: data.latest.avatar,
                                isNew: true // Mark as NEW for specific text
                            };

                            // Prepend
                            recentUsers.unshift(newUser);

                            // Reset index to 0 to show this new user immediately next
                            index = 0;

                            // Clear current loop timeout to force immediate update if possible or let it flow
                            // Ideally, we let the loop handle it naturally, but ensure index points to new user
                        }
                    })
                    .catch(err => console.error(err));
            }

            // Color gradients for initials
            const gradients = [
                'linear-gradient(135deg, #33e818 0%, #1a7a0c 100%)',
                'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)',
                'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                'linear-gradient(135deg, #ef4444 0%, #b91c1c 100%)',
                'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%)',
                'linear-gradient(135deg, #ec4899 0%, #be185d 100%)'
            ];

            function getGradientByName(name) {
                const charCode = name.charCodeAt(0);
                return gradients[charCode % gradients.length];
            }

            function updateBadgeAvatars(currentIndex) {
                for (let i = 0; i < 3; i++) {
                    const userIndex = (currentIndex + i) % recentUsers.length;
                    const user = recentUsers[userIndex];
                    const initialEl = document.getElementById(`badge-initial-${i}`);

                    if (initialEl && user) {
                        // Add fade transition
                        initialEl.classList.add('opacity-50', 'scale-90');

                        setTimeout(() => {
                            initialEl.textContent = user.name.charAt(0).toUpperCase();
                            initialEl.style.background = getGradientByName(user.name);
                            initialEl.classList.remove('opacity-50', 'scale-90');
                        }, 200);
                    }
                }
            }

            function showNextUser() {
                const user = recentUsers[index];

                if (!user) return;

                // Fade out toast
                toastEl.classList.remove('opacity-100', 'translate-y-0');
                toastEl.classList.add('opacity-0', 'translate-y-2');

                setTimeout(() => {
                    // Update Data
                    nameEl.textContent = user.name;

                    // Update Status Text based on isNew flag
                    if (user.isNew) {
                        statusEl.textContent = 'baru bergabung';
                        statusEl.classList.add('text-green-400', 'font-bold', 'opacity-100');
                        statusEl.classList.remove('opacity-70');

                        // Remove flag after showing once so next time it says "sudah bergabung"
                        user.isNew = false;
                    } else {
                        statusEl.textContent = 'sudah bergabung';
                        statusEl.classList.remove('text-green-400', 'font-bold', 'opacity-100');
                        statusEl.classList.add('opacity-70');
                    }

                    // Update Badges with animation
                    updateBadgeAvatars(index);

                    index = (index + 1) % recentUsers.length;

                    // Update Testimonials
                    testimonyIndex = (testimonyIndex + 1) % testimonials.length;
                    
                    // Testimony Animation
                    testimonyNameEl.classList.add('opacity-0', '-translate-x-2');
                    testimonyTextEl.classList.add('opacity-0', 'translate-x-2');
                    
                    setTimeout(() => {
                        testimonyNameEl.textContent = testimonials[testimonyIndex].name;
                        testimonyTextEl.innerHTML = testimonials[testimonyIndex].text;
                        
                        testimonyNameEl.classList.remove('opacity-0', '-translate-x-2');
                        testimonyTextEl.classList.remove('opacity-0', 'translate-x-2');
                    }, 500);

                    // Fade in toast
                    toastEl.classList.remove('opacity-0', 'translate-y-2');
                    toastEl.classList.add('opacity-100', 'translate-y-0');
                }, 500); // Wait for fade out

                // Loop every 4 seconds for a nice pace
                loopTimeout = setTimeout(showNextUser, 4000);
            }
        }
    });
</script>
<?= $this->endSection() ?>

