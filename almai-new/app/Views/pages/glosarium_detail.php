<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    .glossary-content h1, .glossary-content h2, .glossary-content h3 { 
        color: #33e818; 
        font-weight: bold; 
        margin-top: 1.5rem; 
        margin-bottom: 1rem; 
    }
    .glossary-content h1 { font-size: 2rem; }
    .glossary-content h2 { font-size: 1.5rem; }
    .glossary-content p, 
    .glossary-content li, 
    .glossary-content span, 
    .glossary-content div { 
        margin-bottom: 1rem; 
        line-height: 1.8; 
        color: #ffffff !important; 
    }
    .glossary-content ul, .glossary-content ol { 
        margin-left: 1.5rem; 
        margin-bottom: 1rem; 
        color: #ffffff !important;
    }
    .glossary-content ul { list-style-type: disc; }
    .glossary-content ol { list-style-type: decimal; }
    .glossary-content li { margin-bottom: 0.5rem; color: #ffffff !important; }
    .glossary-content strong, .glossary-content b { color: #33e818 !important; font-weight: 600; }
    .glossary-content a { color: #33e818 !important; text-decoration: underline; }
    /* Force all direct text to white */
    .glossary-content { color: #ffffff !important; }
</style>

<div class="pt-32 pb-24 bg-black min-h-screen relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-accent/10 rounded-full blur-[100px] opacity-50 pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">
        <!-- Breadcrumb & Back -->
        <div class="flex items-center gap-4 mb-8 text-sm text-gray-400">
            <a href="<?= base_url('glosarium') ?>" class="hover:text-accent transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali ke Glosarium
            </a>
        </div>

        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-10 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-accent/10 text-accent text-3xl font-black mb-6">
                    <?= substr($glossary->term, 0, 1) ?>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4"><?= esc($glossary->term) ?></h1>
                <?php if (!empty($glossary->short_description)): ?>
                <p class="text-xl text-gray-400 font-light"><?= esc($glossary->short_description) ?></p>
                <?php endif; ?>
            </div>

            <!-- Content Card -->
            <div class="bg-[#111] border border-white/10 rounded-2xl p-8 md:p-12 shadow-2xl shadow-accent/5">
                <div class="glossary-content prose prose-invert max-w-none text-lg">
                    <?= $glossary->definition ?>
                </div>
            </div>

            <!-- Share / Actions -->
            <div class="mt-8 flex justify-center gap-4">
                <a href="https://wa.me/?text=<?= urlencode('Cek definisi ' . $glossary->term . ' di Almai Glosarium: ' . current_url()) ?>" target="_blank" class="px-6 py-3 bg-[#25D366]/10 text-[#25D366] rounded-xl hover:bg-[#25D366]/20 transition flex items-center gap-2 font-medium">
                    <i class="fab fa-whatsapp text-xl"></i> Share via WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
