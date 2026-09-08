<footer class="bg-[#0a0a0a] py-16">
    
<!-- Wrapper Boxed (Tengah, Tidak Full) --> 
<div class="max-w-6xl mx-auto px-6 lg:px-10 bg-[#111] rounded-2xl shadow-lg">

<!-- Glosarium -->
<div class="pt-12 pb-8 text-center sm:text-left">
    <div class="flex flex-wrap items-center justify-center gap-x-3 gap-y-2">
        <!-- Judul -->
        <h2 class="text-white font-semibold text-sm md:text-base tracking-tight w-full md:w-auto text-center md:text-left">Glosarium:</h2>
        <!-- Huruf -->
        <?php foreach (range('A','Z') as $letter): ?>
            <a href="<?= base_url('glosarium?letter=' . $letter) ?>" 
               class="text-accent hover:text-white font-medium transition text-xs w-5 text-center">
                <?= $letter ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>



        <!-- Main Footer Content -->
        <div class="w-full py-12 lg:py-16 border-t border-white/5">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-12 md:gap-8 items-start">
                
                <!-- Branding -->
                <div class="col-span-2 md:col-span-1 flex flex-col items-center md:items-start text-center md:text-left mb-8 md:mb-0">
                    <div class="text-gray-400 text-sm leading-relaxed max-w-sm">
                        <p class="font-bold text-white text-base mb-4 tracking-tight uppercase">
                            PT. ALMA INDONESIA RAYA
                        </p>
                        <div class="space-y-1">
                            <p><span class="font-bold text-white uppercase text-xs">ALMAI</span> | Jl. Badak Agung No. 22 Kav. 3, Renon, Denpasar - Bali. 80226</p>
                            <p>Telf/Fax : 03613610019</p>
                            <p>Chat Support : 085183231800, 085183390019</p>
                        </div>
                    </div>
                </div>

                <!-- Perusahaan -->
<div class="flex flex-col items-start text-left">
    <div class="w-full">
        <h2 class="mb-8 text-sm font-bold text-white uppercase tracking-wider">Perusahaan</h2>
        <ul class="text-gray-400 text-sm space-y-4">
            <li><a href="https://almai.id/about"target="_blank" class="hover:underline">Tentang Kami</a></li>
            <li><a href="<?= base_url('legalitas') ?>" class="hover:underline">Izin usaha</a></li>
            <li><a href="<?= base_url('rekomendasi') ?>" class="hover:underline">Bursa</a></li>
            <li><a href="<?= base_url('asosiasi') ?>" class="hover:underline">Asosiasi</a></li>
        </ul>
    </div>
</div>

<!-- Info -->
<div class="flex flex-col items-start text-left">
    <div class="w-full">
        <h2 class="mb-8 text-sm font-bold text-white uppercase tracking-wider">Info</h2>
        <ul class="text-gray-400 text-sm space-y-4">
            <li><a href="<?= base_url('event') ?>" class="hover:underline">Event</a></li>
            <li><a href="<?= base_url('wpa') ?>" class="hover:underline">WPA & CWPA</a></li>
            <li><a href="<?= base_url('almai-poin') ?>" class="hover:underline">Almai Poin</a></li>
            <li><a href="<?= base_url('roadmaps') ?>" class="hover:underline">Roadmaps</a></li>
        </ul>
    </div>
 </div>
</div>

<!-- Disclaimer -->
<div class="mt-16 mb-20 pt-12 border-t border-white/5">
    <h4 class="font-bold text-white text-sm mb-6 uppercase tracking-widest text-center md:text-left">
        Disclaimer
    </h4>
    <div class="text-gray-500 text-sm leading-relaxed text-left md:text-justify opacity-70">PT. Alma Indonesia Raya adalah perusahaan Penasihat Berjangka yang menyediakan edukasi dan rekomendasi perdagangan berjangka serta aset keuangan digital untuk membantu trader memahami pasar dengan lebih baik. Seluruh keputusan transaksi disesuaikan dengan profil risiko masing-masing pengguna. Untuk keamanan, pastikan menggunakan layanan resmi melalui platform www.almai.id dan melakukan pembayaran hanya melalui rekening atas nama PT. Alma Indonesia Raya.

    </div>
</div>

<!-- LOGO (SUDAH PINDAH KE SINI) -->
<div class="flex justify-center mb-4">
    <a href="<?= base_url() ?>" class="block">
        <img src="<?= base_url('images/almai-full.png') ?>" 
             class="h-12 md:h-14 object-contain opacity-80 hover:opacity-100 transition"
             alt="Almai Logo" />
    </a>
</div>

<!-- Bottom Bar -->
<div class="flex flex-col md:flex-row justify-between items-center gap-8 pt-8">

    <!-- Links -->
    <div class="flex gap-6 text-sm text-gray-400">
        <a href="<?= base_url('kebijakan-privasi') ?>" class="hover:text-accent">Privasi</a>
        <a href="<?= base_url('syarat-ketentuan') ?>" class="hover:text-accent">S&K</a>
        <a href="<?= base_url('faq') ?>" class="hover:text-accent">FAQ</a>
    </div>

    <!-- Copyright -->
    <p class="text-gray-500 text-sm text-center">
        © Almai Made With 💚 In Bali <?= date('Y') ?>
    </p>

<!-- Social -->
<div class="flex items-center gap-5">
    <a href="https://www.instagram.com/almai_id/" 
       target="_blank" 
       rel="noopener noreferrer"
       class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-accent bg-white/5 hover:bg-white/10 rounded-full">
        <i class="fab fa-instagram"></i>
    </a>

    <a href="https://www.tiktok.com/@almai.indonesia?lang=en" 
       target="_blank" 
       rel="noopener noreferrer"
       class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-accent bg-white/5 hover:bg-white/10 rounded-full">
        <i class="fab fa-tiktok"></i>
    </a>

    <a href="https://www.youtube.com/@Almai_id" 
       target="_blank" 
       rel="noopener noreferrer"
       class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-accent bg-white/5 hover:bg-white/10 rounded-full">
        <i class="fab fa-youtube"></i>
    </a>
</div>

</div>
</footer>