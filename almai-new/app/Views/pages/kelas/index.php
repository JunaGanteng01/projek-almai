<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero -->
<section class="relative pt-32 pb-8 overflow-hidden">
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="container mx-auto px-6 relative z-10 text-center">
        <p class="text-accent font-bold tracking-widest text-sm mb-4 uppercase">Tingkatkan Skill Trading</p>
        <h1 class="text-5xl md:text-6xl font-bold mb-4 tracking-tighter">Semua <span class="text-accent">Kelas</span></h1>
        <p class="text-gray-400">Belajar trading dari WPA bersertifikat BAPPEBTI</p>
    </div>
</section>

<!-- Layanan Grid -->
<section class="py-8">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <!-- Artikel -->
            <a href="<?= base_url('artikel') ?>" class="group bg-[#111] border border-white/10 rounded-2xl p-6 text-center hover:border-accent/50 hover:bg-accent/5 transition-all duration-300">
                <div class="w-14 h-14 mx-auto mb-4 bg-gradient-to-br from-blue-500/20 to-blue-600/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-newspaper text-2xl text-blue-400"></i>
                </div>
                <h3 class="font-bold text-sm mb-1">Artikel</h3>
                <p class="text-xs text-gray-500">Insight & Analisis</p>
            </a>
            
            <!-- Webinar -->
            <a href="#" class="group bg-[#111] border border-white/10 rounded-2xl p-6 text-center hover:border-accent/50 hover:bg-accent/5 transition-all duration-300">
                <div class="w-14 h-14 mx-auto mb-4 bg-gradient-to-br from-purple-500/20 to-purple-600/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-video text-2xl text-purple-400"></i>
                </div>
                <h3 class="font-bold text-sm mb-1">Webinar</h3>
                <p class="text-xs text-gray-500">Live Session</p>
            </a>
            
            <!-- Advokasi -->
            <button onclick="openLayananModal('advokasi')" class="group bg-[#111] border border-white/10 rounded-2xl p-6 text-center hover:border-accent/50 hover:bg-accent/5 transition-all duration-300 w-full">
                <div class="w-14 h-14 mx-auto mb-4 bg-gradient-to-br from-red-500/20 to-red-600/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-tie text-2xl text-red-400"></i>
                </div>
                <h3 class="font-bold text-sm mb-1">Advokasi</h3>
                <p class="text-xs text-gray-500">Pendampingan WPA</p>
            </button>
            
            <!-- Expert Advisor -->
            <button onclick="openLayananModal('ea')" class="group bg-[#111] border border-white/10 rounded-2xl p-6 text-center hover:border-accent/50 hover:bg-accent/5 transition-all duration-300 w-full">
                <div class="w-14 h-14 mx-auto mb-4 bg-gradient-to-br from-orange-500/20 to-orange-600/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-robot text-2xl text-orange-400"></i>
                </div>
                <h3 class="font-bold text-sm mb-1">Expert Advisor</h3>
                <p class="text-xs text-gray-500">Trading Bot</p>
            </button>
            
            <!-- Almai Ultimate -->
            <button onclick="openLayananModal('ultimate')" class="group bg-[#111] border border-white/10 rounded-2xl p-6 text-center hover:border-accent/50 hover:bg-accent/5 transition-all duration-300 relative overflow-hidden w-full">
                <div class="absolute top-2 right-2">
                    <span class="bg-accent text-black text-[10px] font-bold px-2 py-0.5 rounded-full">PRO</span>
                </div>
                <div class="w-14 h-14 mx-auto mb-4 bg-gradient-to-br from-accent/20 to-green-600/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-crown text-2xl text-accent"></i>
                </div>
                <h3 class="font-bold text-sm mb-1">Almai Ultimate</h3>
                <p class="text-xs text-gray-500">All-in-One</p>
            </button>
        </div>
    </div>
</section>

<!-- Layanan Modal -->
<div id="layananModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[60] hidden items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="layananModalContent">
        <div class="p-6 border-b border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div id="layananModalIcon" class="w-12 h-12 rounded-xl flex items-center justify-center"></div>
                <h3 id="layananModalTitle" class="text-xl font-bold"></h3>
            </div>
            <button onclick="closeLayananModal()" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="layananModalBody" class="p-6 overflow-y-auto" style="max-height: calc(90vh - 180px);"></div>
        <div class="p-6 border-t border-white/10">
            <a id="layananModalCta" href="#" class="block text-center py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                Hubungi Kami
            </a>
        </div>
    </div>
</div>

<script>
const layananData = {
    advokasi: {
        title: 'Advokasi',
        icon: '<i class="fas fa-user-tie text-2xl text-red-400"></i>',
        iconBg: 'bg-gradient-to-br from-red-500/20 to-red-600/20',
        content: `
            <div class="space-y-4 text-gray-300">
                <p class="text-lg font-medium text-white">Layanan Pendampingan Wakil Penasihat Berjangka</p>
                <p>Advokasi adalah layanan nasihat berupa <strong class="text-white">pelatihan atau pendampingan strategi perdagangan praktis</strong> dari seorang Wakil Penasihat Berjangka.</p>
                <p>Almai memberikan layanan hanya untuk Klien yang telah memahami mekanisme Perdagangan Berjangka yang ingin mengembangkan portfolio hingga batas yang belum bisa dicapai saat ini.</p>
                
                <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-4 mt-4">
                    <p class="text-yellow-400 text-sm"><i class="fas fa-info-circle mr-2"></i><strong>Untuk Pemula:</strong></p>
                    <p class="text-sm mt-2">Klien yang belum pernah melakukan transaksi Perdagangan Berjangka dan ingin langsung mengembangkan portofolio transaksi disarankan untuk:</p>
                    <ul class="list-disc list-inside text-sm mt-2 space-y-1">
                        <li>Mengikuti Pelatihan sekaligus membuka akun transaksi pada Perusahaan Pialang dan/atau Perusahaan Pedagang Aset Fisik Kripto yang terdaftar serta diawasi oleh <strong class="text-white">Bappebti & OJK</strong></li>
                        <li>Atau mengikuti Program <strong class="text-accent">Almai Ultimate</strong> yang menyediakan pelatihan dan monitoring transaksi</li>
                    </ul>
                </div>
            </div>
        `,
        cta: 'Konsultasi Gratis',
        ctaLink: 'https://wa.me/6285183231800?text=Halo, saya tertarik dengan layanan Advokasi Almai'
    },
    ea: {
        title: 'Expert Advisor',
        icon: '<i class="fas fa-robot text-2xl text-orange-400"></i>',
        iconBg: 'bg-gradient-to-br from-orange-500/20 to-orange-600/20',
        content: `
            <div class="space-y-4 text-gray-300">
                <p class="text-lg font-medium text-white">Layanan Nasihat Berbasis Teknologi Informasi</p>
                <p>Expert Advisor dari PT. Alma Indonesia Raya telah mendapatkan rekomendasi resmi:</p>
                
                <div class="grid md:grid-cols-2 gap-4 mt-4">
                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-certificate text-blue-400"></i>
                            <span class="font-bold text-white">EA AIWE</span>
                        </div>
                        <p class="text-sm">EA Metatrader</p>
                        <p class="text-xs text-blue-400 mt-1">Rekomendasi Bursa Komoditi JFX</p>
                    </div>
                    <div class="bg-purple-500/10 border border-purple-500/30 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-certificate text-purple-400"></i>
                            <span class="font-bold text-white">EA BIDBOX</span>
                        </div>
                        <p class="text-sm">EA Kripto</p>
                        <p class="text-xs text-purple-400 mt-1">Rekomendasi Bursa Kripto CFX</p>
                    </div>
                </div>
                
                <p class="mt-4">Klien akan mendapatkan <strong class="text-white">Software untuk diinstal secara mandiri</strong> dengan mendapatkan pelatihan dan/atau pendampingan dari Wakil Penasihat Berjangka Almai.</p>
                
                <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mt-4">
                    <p class="text-red-400 text-sm"><i class="fas fa-exclamation-triangle mr-2"></i><strong>Persyaratan Penting:</strong></p>
                    <ul class="list-disc list-inside text-sm mt-2 space-y-1">
                        <li>Kecukupan modal sebesar <strong class="text-white">> $5.000</strong></li>
                        <li>Hanya membuka akun transaksi pada perusahaan pialang atau perusahaan pedagang aset fisik kripto yang berizin serta diawasi oleh <strong class="text-white">Bappebti & OJK</strong></li>
                    </ul>
                </div>
            </div>
        `,
        cta: 'Lihat Tools',
        ctaLink: '<?= base_url("tools") ?>'
    },
    ultimate: {
        title: 'Almai Ultimate',
        icon: '<i class="fas fa-crown text-2xl text-accent"></i>',
        iconBg: 'bg-gradient-to-br from-accent/20 to-green-600/20',
        content: `
            <div class="space-y-4 text-gray-300">
                <div class="flex items-center gap-2 mb-4">
                    <span class="bg-accent text-black text-xs font-bold px-3 py-1 rounded-full">PREMIUM</span>
                    <span class="text-accent font-medium">Solusi Komprehensif</span>
                </div>
                
                <p class="text-lg font-medium text-white">Program All-in-One untuk Perdagangan Berjangka Komoditi</p>
                <p><strong class="text-accent">Ultimate</strong> adalah solusi komprehensif yang dirancang untuk memenuhi kebutuhan dan harapan Klien pada Perdagangan Berjangka Komoditi melalui layanan Nasihat secara lengkap.</p>
                
                <div class="bg-accent/10 border border-accent/30 rounded-xl p-4 mt-4">
                    <p class="text-accent font-bold mb-3"><i class="fas fa-star mr-2"></i>Yang Anda Dapatkan:</p>
                    <div class="grid md:grid-cols-2 gap-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-accent"></i>
                            <span class="text-sm">Tenaga Spesialis Bersertifikasi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-accent"></i>
                            <span class="text-sm">Teknologi Informasi Bersertifikasi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-accent"></i>
                            <span class="text-sm">Rencana Pengelolaan Transaksi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-accent"></i>
                            <span class="text-sm">Pendampingan Personal</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-accent"></i>
                            <span class="text-sm">Monitoring Transaksi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-accent"></i>
                            <span class="text-sm">Pelatihan Lengkap</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/5 rounded-xl p-4 mt-4 text-center">
                    <p class="text-sm text-gray-400">Bertujuan untuk memastikan bahwa klien dapat mencapai</p>
                    <p class="text-lg font-bold text-white mt-1">Sasaran Pertumbuhan Portfolio dengan Aman dan Efektif!</p>
                </div>
            </div>
        `,
        cta: 'Daftar Sekarang',
        ctaLink: 'https://wa.me/6285183231800?text=Halo, saya tertarik dengan program Almai Ultimate'
    }
};

function openLayananModal(type) {
    const data = layananData[type];
    if (!data) return;
    
    const modal = document.getElementById('layananModal');
    const content = document.getElementById('layananModalContent');
    
    document.getElementById('layananModalIcon').className = 'w-12 h-12 rounded-xl flex items-center justify-center ' + data.iconBg;
    document.getElementById('layananModalIcon').innerHTML = data.icon;
    document.getElementById('layananModalTitle').textContent = data.title;
    document.getElementById('layananModalBody').innerHTML = data.content;
    document.getElementById('layananModalCta').textContent = data.cta;
    document.getElementById('layananModalCta').href = data.ctaLink;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeLayananModal() {
    const modal = document.getElementById('layananModal');
    const content = document.getElementById('layananModalContent');
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

document.getElementById('layananModal').addEventListener('click', function(e) {
    if (e.target === this) closeLayananModal();
});
</script>

<!-- Search -->
<section class="py-6">
    <div class="container mx-auto px-6">
        <div class="max-w-xl mx-auto">
            <form action="<?= base_url('kelas') ?>" method="get" class="relative">
                <input type="text" name="search" value="<?= esc($searchQuery ?? '') ?>" 
                    placeholder="Cari kelas berdasarkan judul atau kategori..." 
                    class="w-full bg-[#111] border border-white/20 rounded-full px-6 py-4 pl-14 focus:border-accent focus:outline-none">
                <i class="fas fa-search absolute left-6 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <?php if ($currentCategory): ?>
                    <input type="hidden" name="category" value="<?= esc($currentCategory) ?>">
                <?php endif; ?>
                <?php if ($currentMode): ?>
                    <input type="hidden" name="mode" value="<?= esc($currentMode) ?>">
                <?php endif; ?>
            </form>
        </div>
    </div>
</section>

<!-- Filter -->
<section class="py-4">
    <div class="container mx-auto px-6">
        <!-- Mode Filter -->
        <div class="flex flex-wrap gap-3 justify-center mb-4">
            <?php 
            $baseParams = [];
            if ($currentCategory) $baseParams['category'] = $currentCategory;
            if ($searchQuery) $baseParams['search'] = $searchQuery;
            ?>
            <a href="<?= base_url('kelas') . ($baseParams ? '?' . http_build_query($baseParams) : '') ?>" 
               class="px-4 py-2 rounded-full border text-xs font-medium transition <?= !$currentMode ? 'bg-accent text-black border-accent' : 'border-white/20 hover:border-accent hover:text-accent' ?>">
                Semua Mode
            </a>
            <?php foreach ($modes as $mode): ?>
                <?php $params = array_merge($baseParams, ['mode' => $mode]); ?>
                <a href="<?= base_url('kelas?' . http_build_query($params)) ?>" 
                   class="px-4 py-2 rounded-full border text-xs font-medium transition <?= $currentMode === $mode ? 'bg-accent text-black border-accent' : 'border-white/20 hover:border-accent hover:text-accent' ?>">
                    <i class="fas <?= $mode === 'Online' ? 'fa-video' : ($mode === 'Offline' ? 'fa-building' : 'fa-arrows-rotate') ?> mr-1"></i><?= esc($mode) ?>
                </a>
            <?php endforeach; ?>
        </div>
        
        <!-- Category Filter -->
        <div class="flex flex-wrap gap-3 justify-center">
            <?php 
            $baseParams = [];
            if ($currentMode) $baseParams['mode'] = $currentMode;
            if ($searchQuery) $baseParams['search'] = $searchQuery;
            ?>
            <a href="<?= base_url('kelas') . ($baseParams ? '?' . http_build_query($baseParams) : '') ?>" 
               class="px-6 py-2 rounded-full border text-sm font-medium transition <?= !$currentCategory ? 'bg-accent text-black border-accent' : 'border-white/20 hover:border-accent hover:text-accent' ?>">
                Semua
            </a>
            <?php foreach ($categories as $category): ?>
                <?php $params = array_merge($baseParams, ['category' => $category]); ?>
                <a href="<?= base_url('kelas?' . http_build_query($params)) ?>" 
                   class="px-6 py-2 rounded-full border text-sm font-medium transition <?= $currentCategory === $category ? 'bg-accent text-black border-accent' : 'border-white/20 hover:border-accent hover:text-accent' ?>">
                    <?= esc(str_replace(' Trading', '', $category)) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Kelas Grid -->
<section class="py-12">
    <div class="container mx-auto px-6">
        <?php if (empty($kelasList)): ?>
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-search text-4xl mb-4"></i>
                <p>Tidak ada kelas yang ditemukan.</p>
            </div>
        <?php else: ?>
            <div class="grid md:grid-cols-3 gap-6">
                <?php foreach ($kelasList as $kelas): ?>
                    <?= view('partials/cards/kelas_card', ['kelas' => $kelas]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
