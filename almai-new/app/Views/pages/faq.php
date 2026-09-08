<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* Prevent white background on autofill and focus */
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 30px #111 inset !important;
        -webkit-text-fill-color: white !important;
        transition: background-color 5000s ease-in-out 0s;
    }
    
    #faqSearch {
        background-color: transparent !important;
        background-image: none !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- FAQ Hero -->
<section class="relative pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-accent/10 blur-[120px] rounded-full pointer-events-none"></div>
    
    <div class="container mx-auto px-6 relative z-10 text-center">
        <p class="text-accent font-bold tracking-widest text-sm mb-4 uppercase" data-aos="fade-up">Pusat Bantuan</p>
        <h1 class="text-4xl md:text-6xl font-bold mb-6 tracking-tighter" data-aos="fade-up">Frequently Asked <span class="text-accent">Questions</span></h1>
        <p class="text-gray-400 max-w-2xl mx-auto mb-12" data-aos="fade-up" data-aos-delay="100">
            Temukan jawaban untuk pertanyaan yang paling sering diajukan mengenai layanan Almai, trading, dan platform kami.
        </p>
        
        <!-- FAQ Search -->
        <div class="max-w-xl mx-auto relative group" data-aos="fade-up" data-aos-delay="200">
            <div class="absolute inset-0 bg-accent/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative flex items-center bg-[#111] border border-white/10 rounded-2xl overflow-hidden focus-within:border-accent/50 transition-all">
                <div class="pl-6 pr-4 text-gray-500 bg-transparent">
                    <i class="fas fa-search"></i>
                </div>
                <input type="text" id="faqSearch" placeholder="Cari pertanyaan..." class="w-full py-5 bg-transparent border-none focus:ring-0 focus:outline-none text-white placeholder-gray-600 outline-none appearance-none" style="background-color: transparent !important;">
            </div>
        </div>
    </div>
</section>

<!-- FAQ Content -->
<section class="py-20 bg-black">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <?php if (empty($faqs)): ?>
                <div class="text-center py-20 bg-[#111] rounded-3xl border border-dashed border-white/10">
                    <i class="fas fa-question-circle text-5xl text-gray-700 mb-6"></i>
                    <p class="text-gray-500">Belum ada pertanyaan yang tersedia saat ini.</p>
                </div>
            <?php else: ?>
                <!-- FAQ Accordion -->
                <div class="space-y-4" id="faqContainer">
                    <?php foreach ($faqs as $index => $faq): ?>
                        <div class="faq-item group" data-aos="fade-up" data-aos-delay="<?= $index * 50 ?>" data-question="<?= strtolower(esc($faq['question'])) ?>">
                            <button class="faq-trigger w-full flex items-center justify-between p-6 md:p-8 bg-[#0a0a0a] border border-white/5 rounded-2xl hover:border-accent/30 transition-all text-left group-hover:bg-[#111]">
                                <span class="text-lg md:text-xl font-bold text-white pr-8"><?= esc($faq['question']) ?></span>
                                <div class="faq-icon w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-gray-400 group-hover:bg-accent group-hover:text-black transition-all">
                                    <i class="fas fa-chevron-down transition-transform duration-300"></i>
                                </div>
                            </button>
                            <div class="faq-answer hidden">
                                <div class="p-6 md:p-8 pt-0 text-gray-400 leading-loose border-x border-b border-white/5 rounded-b-2xl bg-[#0a0a0a]/50">
                                    <?= $faq['answer'] ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- No Results -->
                <div id="noResults" class="hidden text-center py-20">
                    <p class="text-gray-500">Tidak menemukan pertanyaan yang cocok dengan pencarian Anda.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Support CTA -->
<section class="py-20 bg-gradient-to-t from-accent/5 to-transparent">
    <div class="container mx-auto px-6 text-center">
        <div class="max-w-3xl mx-auto p-12 bg-[#0a0a0a] border border-white/10 rounded-[3rem] shadow-2xl relative overflow-hidden" data-aos="zoom-in">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-accent/10 blur-[80px] rounded-full"></div>
            
            <h2 class="text-3xl font-bold mb-6">Masih punya <span class="text-accent">pertanyaan?</span></h2>
            <p class="text-gray-400 mb-10">
                Tim dukungan kami siap membantu Anda 24/7. Hubungi kami melalui kanal bantuan di bawah ini.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="https://wa.me/6285183231800?text=hai%20almai%20%2C%20saya%20mau%20tau%20tentang" class="w-full sm:w-auto px-8 py-4 bg-accent text-black font-bold rounded-2xl hover:scale-105 transition-all flex items-center justify-center gap-3">
                    <i class="fab fa-whatsapp text-xl"></i> Chat WhatsApp
                </a>
                <a href="mailto:support@almai.id" class="w-full sm:w-auto px-8 py-4 bg-white/5 border border-white/10 text-white font-bold rounded-2xl hover:bg-white/10 transition-all flex items-center justify-center gap-3">
                    <i class="fas fa-envelope text-xl"></i> Kirim Email
                </a>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const triggers = document.querySelectorAll('.faq-trigger');
        
        triggers.forEach(trigger => {
            trigger.addEventListener('click', function() {
                const parent = this.parentElement;
                const answer = parent.querySelector('.faq-answer');
                const icon = this.querySelector('.faq-icon i');
                
                // Toggle current FAQ
                const isHidden = answer.classList.contains('hidden');
                
                // Close others
                document.querySelectorAll('.faq-answer').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.faq-trigger').forEach(el => {
                    el.classList.remove('border-accent/30', 'bg-[#111]', 'rounded-t-2xl');
                    el.classList.add('rounded-2xl');
                });
                document.querySelectorAll('.faq-icon i').forEach(el => el.classList.remove('rotate-180'));
                
                if (isHidden) {
                    answer.classList.remove('hidden');
                    this.classList.add('border-accent/30', 'bg-[#111]');
                    this.classList.remove('rounded-2xl');
                    this.classList.add('rounded-t-2xl');
                    icon.classList.add('rotate-180');
                }
            });
        });
        
        // Search functionality
        const searchInput = document.getElementById('faqSearch');
        const faqItems = document.querySelectorAll('.faq-item');
        const noResults = document.getElementById('noResults');
        
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let hasVisibleItems = false;
            
            faqItems.forEach(item => {
                const question = item.getAttribute('data-question');
                if (question.includes(query)) {
                    item.classList.remove('hidden');
                    hasVisibleItems = true;
                } else {
                    item.classList.add('hidden');
                }
            });
            
            if (hasVisibleItems) {
                noResults.classList.add('hidden');
            } else {
                noResults.classList.remove('hidden');
            }
        });
    });
</script>
<?= $this->endSection() ?>
