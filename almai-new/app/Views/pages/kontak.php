<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero -->
<section class="relative pt-32 pb-16 overflow-hidden">
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="container mx-auto px-6 relative z-10 text-center">
        <p class="text-accent font-bold tracking-widest text-sm mb-4 uppercase">Hubungi Kami</p>
        <h1 class="text-5xl md:text-6xl font-bold mb-4 tracking-tighter">Kontak <span class="text-accent">Kami</span></h1>
        <p class="text-gray-400 max-w-2xl mx-auto">Ada pertanyaan? Tim kami siap membantu Anda.</p>
    </div>
</section>

<!-- LOKASI -->
<div class="mt-8 max-w-6xl mx-auto" data-aos="fade-up">

    <!-- Title -->
    <div class="text-center mb-10 px-6">
        <h2 class="text-3xl md:text-4xl font-bold mb-3">
            Lokasi <span class="text-accent">Kantor Kami</span>
        </h2>
        <p class="text-gray-400 max-w-2xl mx-auto">
            Kunjungi kantor kami di Denpasar, Bali untuk konsultasi langsung
        </p>
    </div>

    <!-- Image -->
    <div class="max-w-4xl mx-auto px-6">
        <div class="rounded-2xl overflow-hidden border border-white/10 shadow-2xl shadow-accent/5">
            <img src="<?= base_url('images/office.jpeg') ?>" 
                 class="w-full h-[350px] md:h-[450px] object-cover">
        </div>
    </div>

    <!-- Info -->
    <div class="max-w-3xl mx-auto text-center mt-8 px-6">
        <h3 class="font-bold text-xl mb-4 flex items-center justify-center gap-2">
            <i class="fas fa-building text-accent"></i>
            PT. Alma Indonesia Raya
        </h3>


<!-- Button Maps -->
<a href="https://maps.app.goo.gl/x9EbQSvTiDKXt1UYA" 
   target="_blank"
   class="inline-flex items-center gap-2 px-6 py-3 
          bg-accent text-black font-semibold rounded-lg 
          hover:bg-white transition duration-300
          hover:shadow-[0_0_15px_rgba(0,255,150,0.6)]">
    
    <i class="fas fa-map-marker-alt"></i>
    Buka di Google Maps
</a>

</section>
        
        <!-- Office Hours -->
        <div class="mt-8 max-w-6xl mx-auto" data-aos="fade-up">
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                <div class="grid md:grid-cols-3 gap-6 text-center">
                    <div>
                        <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-clock text-accent text-xl"></i>
                        </div>
                        <h4 class="font-bold mb-2">Jam Operasional</h4>
                        <p class="text-gray-400 text-sm">Senin - Jumat</p>
                        <p class="text-accent font-bold">09:00 - 17:00 WITA</p>
                    </div>
                    <div>
                        <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-calendar-check text-accent text-xl"></i>
                        </div>
                        <h4 class="font-bold mb-2">Sabtu</h4>
                        <p class="text-gray-400 text-sm">Buka dengan Perjanjian</p>
                        <p class="text-accent font-bold">09:00 - 14:00 WITA</p>
                    </div>
                    <div>
                        <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-calendar-times text-accent text-xl"></i>
                        </div>
                        <h4 class="font-bold mb-2">Minggu & Libur</h4>
                        <p class="text-gray-400 text-sm">Tutup</p>
                        <p class="text-gray-500 font-bold">-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<br>
</section>

<!-- Contact Content -->
<section class="py-16 bg-black/50">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12">

                <!-- Contact Info -->
                <div data-aos="fade-up">
                    <h2 class="text-3xl font-bold mb-8">Informasi Kontak</h2>
                    
                    <div class="space-y-6">

                        <!-- Alamat -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-accent"></i>
                            </div>
                            <div>
                                <h3 class="font-bold mb-1">Alamat</h3>
                                <p class="text-gray-400">
                                    Jl. Badak Agung No. 22 Kav. 3, Kel. Renon. Denpasar - Bali 80226
                                </p>
                            </div>
                        </div>

                        <!-- TELEPON -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-accent"></i>
                            </div>
                            <div class="space-y-1">
                                <h3 class="font-semibold text-white">Telepon & Fax</h3>
                                <a href="tel:+623613610019" class="text-accent hover:underline">
                                    0361-3610019
                                </a>
                            </div>
                        </div>

                        <!-- WHATSAPP -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fab fa-whatsapp text-accent"></i>
                            </div>
                            <div class="space-y-1">
                                <h3 class="font-semibold text-white">WhatsApp</h3>
                                <a id="waLink" target="_blank" class="text-accent hover:underline">
                                    +62 851-8339-0019
                                </a>
                            </div>
                        </div>

                        <!-- EMAIL -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-accent"></i>
                            </div>
                            <div>
                                <h3 class="font-bold mb-1">Email</h3>
                                <a href="mailto:info@almai.id" class="text-accent hover:underline">
                                    info@almai.id
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Contact Form -->
                <div class="w-full max-w-sm mx-auto bg-[#111] p-6 rounded-xl border border-white/10"
                     data-aos="fade-up" data-aos-delay="100">
                    
                    <h2 class="text-xl font-bold mb-5 text-center">Kirim Pesan</h2>

                    <form id="contactForm" class="space-y-3">

                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Nama Lengkap</label>
                            <input type="text" id="nama"
                                   class="w-full bg-black border border-white/20 rounded-lg px-3 py-2 text-sm focus:border-accent focus:outline-none"
                                   placeholder="Nama Anda" required>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Email</label>
                            <input type="email" id="email"
                                   class="w-full bg-black border border-white/20 rounded-lg px-3 py-2 text-sm focus:border-accent focus:outline-none"
                                   placeholder="email@example.com" required>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 mb-1">No. WhatsApp</label>
                            <input type="tel" id="whatsapp"
                                   class="w-full bg-black border border-white/20 rounded-lg px-3 py-2 text-sm focus:border-accent focus:outline-none"
                                   placeholder="08xxxxxxxxxx" required>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Subjek</label>
                            <select id="subjek"
                                    class="w-full bg-black border border-white/20 rounded-lg px-3 py-2 text-sm focus:border-accent focus:outline-none"
                                    required>
                                <option value="">Pilih Subjek</option>
                                <option value="Pertanyaan Umum">Pertanyaan Umum</option>
                                <option value="Tentang Kelas">Tentang Kelas</option>
                                <option value="Tentang WPA">Tentang WPA</option>
                                <option value="Pembayaran">Pembayaran</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Pesan</label>
                            <textarea id="pesan" rows="3"
                                      class="w-full bg-black border border-white/20 rounded-lg px-3 py-2 text-sm focus:border-accent focus:outline-none"
                                      placeholder="Tulis pesan Anda..." required></textarea>
                        </div>

                        <button type="submit"
                                class="w-full py-2.5 bg-accent text-black text-sm font-semibold rounded-lg hover:bg-white transition">
                            <i class="fab fa-whatsapp mr-2"></i>Kirim via WhatsApp
                        </button>

                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Script WA -->
<script>
const phone = "6285183390019";
const message = "Hi Almai, saya mau tanya dong..";

document.getElementById("waLink").href =
  `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
</script>

<!-- FAQ -->
<section class="py-16 bg-card-bg">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-12">Pertanyaan Umum</h2>
        <div class="max-w-3xl mx-auto space-y-4">
            <div class="bg-[#111] rounded-xl border border-white/10 overflow-hidden">
                <button class="w-full p-6 text-left flex items-center justify-between" onclick="this.parentElement.classList.toggle('active')">
                    <span class="font-bold">Apa itu WPA?</span>
                    <i class="fas fa-chevron-down transition"></i>
                </button>
                <div class="px-6 pb-6 hidden">
                    <p class="text-gray-400">WPA (Wakil Penasihat Berjangka) adalah profesional yang telah mendapatkan sertifikasi dari BAPPEBTI dan LSP PBK untuk memberikan nasihat terkait perdagangan berjangka.</p>
                </div>
            </div>
            <div class="bg-[#111] rounded-xl border border-white/10 overflow-hidden">
                <button class="w-full p-6 text-left flex items-center justify-between" onclick="this.parentElement.classList.toggle('active')">
                    <span class="font-bold">Bagaimana cara membeli kelas?</span>
                    <i class="fas fa-chevron-down transition"></i>
                </button>
                <div class="px-6 pb-6 hidden">
                    <p class="text-gray-400">Anda dapat membeli kelas dengan mendaftar akun, memilih kelas yang diinginkan, dan melakukan pembayaran melalui metode yang tersedia.</p>
                </div>
            </div>
         
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// FAQ accordion
document.querySelectorAll('.bg-\\[\\#111\\].rounded-xl button').forEach(btn => {
    btn.addEventListener('click', function() {
        const content = this.nextElementSibling;
        content.classList.toggle('hidden');
        this.querySelector('i').classList.toggle('rotate-180');
    });
});

// Contact form to WhatsApp
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form values
    const nama = document.getElementById('nama').value;
    const email = document.getElementById('email').value;
    const whatsapp = document.getElementById('whatsapp').value;
    const subjek = document.getElementById('subjek').value;
    const pesan = document.getElementById('pesan').value;
    
    // Format WhatsApp message
    const message = `*Pesan dari Website Almai*\n\n` +
                   `*Nama:* ${nama}\n` +
                   `*Email:* ${email}\n` +
                   `*No. WhatsApp:* ${whatsapp}\n` +
                   `*Subjek:* ${subjek}\n\n` +
                   `*Pesan:*\n${pesan}`;
    
    // WhatsApp number (remove leading 0 and add 62)
    const waNumber = '6285183390019';
    
    // Create WhatsApp URL
    const waUrl = `https://wa.me/${waNumber}?text=${encodeURIComponent(message)}`;
    
    // Open WhatsApp in new tab
    window.open(waUrl, '_blank');
    
    // Optional: Reset form after sending
    this.reset();
});
</script>
<?= $this->endSection() ?>
