<?php
/**
 * @var string $startDate
 * @var string $endDate
 * @var int $tahun
 * @var array $calkData
 */
?>
<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Catatan Atas Laporan Keuangan (CALK)</h1>
        <p class="text-gray-500 text-xs mt-1">Sesuai Standar SAK ETAP - Hierarchical Structure</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <button onclick="window.print()" class="px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2 shadow-lg shadow-accent/20">
            <i class="fas fa-file-pdf"></i> Cetak CALK
        </button>
        <button onclick="syncCALK()" class="px-4 py-2 bg-blue-500 text-white font-bold rounded-xl hover:bg-blue-600 transition flex items-center gap-2 shadow-lg shadow-blue-500/20">
            <i class="fas fa-sync"></i> Sync Data
        </button>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-xl mb-6 text-sm">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    
    <!-- Form Edit CALK (Left Panel) -->
    <div class="xl:col-span-1 print:hidden">
        <div class="bg-[#111] border border-white/10 rounded-3xl p-6 sticky top-20 max-h-[calc(100vh-120px)] overflow-y-auto">
            <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-edit text-accent"></i> Edit CALK
            </h3>
            
            <form action="<?= base_url('keuangan/calk/save') ?>" method="post" class="space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="start_date" value="<?= esc($startDate) ?>">
                <input type="hidden" name="end_date" value="<?= esc($endDate) ?>">
                
                <!-- Accordion Sections -->
                <div class="space-y-2">
                    <?php if (isset($calkData['sections'])): ?>
                        <?php foreach ($calkData['sections'] as $section): ?>
                            <div class="border border-white/10 rounded-lg overflow-hidden">
                                <button type="button" class="w-full px-4 py-3 bg-white/5 hover:bg-white/10 transition text-left font-bold text-white flex items-center justify-between" onclick="toggleSection(this)">
                                    <span><?= $section['id'] . '. ' . $section['title'] ?></span>
                                    <i class="fas fa-chevron-down transition-transform"></i>
                                </button>
                                
                                <div class="hidden px-4 py-3 bg-black/50 space-y-3 border-t border-white/10">
                                    <?php if ($section['type'] === 'narrative' && isset($section['subsections'])): ?>
                                        <?php foreach ($section['subsections'] as $subsection): ?>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                                                    <?= $subsection['id'] . '. ' . $subsection['title'] ?>
                                                </label>
                                                
                                                <?php if ($subsection['type'] === 'narrative'): ?>
                                                    <textarea name="section_<?= $subsection['id'] ?>" rows="3" class="w-full bg-black border border-white/10 rounded-lg px-3 py-2 focus:border-accent focus:outline-none text-white text-sm"><?= esc($subsection['content'] ?? '') ?></textarea>
                                                <?php elseif ($subsection['type'] === 'table'): ?>
                                                    <div class="text-xs text-gray-400 bg-black/50 p-2 rounded border border-white/10">
                                                        <p class="mb-2">Tabel: <?= $subsection['title'] ?></p>
                                                        <p class="text-gray-500">Tabel ini dapat diedit melalui interface khusus</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php elseif ($section['type'] === 'financial_table'): ?>
                                        <div class="text-xs text-gray-400 bg-black/50 p-2 rounded border border-white/10">
                                            <p class="mb-2">Tabel Keuangan: <?= $section['title'] ?></p>
                                            <p class="text-gray-500">Data otomatis dari laporan keuangan (Read-only)</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="w-full py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <!-- Preview / Print Area (Right Panel) -->
    <div class="xl:col-span-2">
        <div class="bg-white text-black p-8 md:p-12 border border-white/10 rounded-3xl shadow-2xl print:border-none print:shadow-none print:p-0">
            <div class="text-center mb-8 border-b-2 border-black pb-6">
                <h2 class="text-2xl font-black uppercase tracking-widest">PT ALMA INDONESIA RAYA</h2>
                <h3 class="text-lg font-bold mt-2">CATATAN ATAS LAPORAN KEUANGAN</h3>
                <p class="text-sm mt-1">Untuk Periode yang Berakhir pada <?= date('d F Y') ?></p>
            </div>

            <div class="space-y-8 text-sm leading-relaxed text-justify">
                
                <?php if (isset($calkData['sections'])): ?>
                    <?php foreach ($calkData['sections'] as $section): ?>
                        <section>
                            <h4 class="font-black text-base mb-3"><?= $section['id'] . '. ' . $section['title'] ?></h4>
                            
                            <?php if ($section['type'] === 'narrative' && isset($section['subsections'])): ?>
                                <?php foreach ($section['subsections'] as $subsection): ?>
                                    <div class="mb-4">
                                        <h5 class="font-bold text-sm mb-2"><?= $subsection['id'] . '. ' . $subsection['title'] ?></h5>
                                        
                                        <?php if ($subsection['type'] === 'narrative'): ?>
                                            <p><?= nl2br(esc($subsection['content'] ?? '')) ?></p>
                                        <?php elseif ($subsection['type'] === 'table'): ?>
                                            <table class="w-full border-collapse border border-gray-300 text-xs">
                                                <thead>
                                                    <tr class="bg-gray-100">
                                                        <?php foreach ($subsection['columns'] as $col): ?>
                                                            <th class="border border-gray-300 px-2 py-1 text-left font-bold"><?= $col['name'] ?></th>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($subsection['rows'] as $row): ?>
                                                        <tr>
                                                            <?php foreach ($subsection['columns'] as $col): ?>
                                                                <td class="border border-gray-300 px-2 py-1"><?= $row[$col['name']] ?? '' ?></td>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php elseif ($section['type'] === 'financial_table'): ?>
                                <table class="w-full border-collapse border border-gray-300 text-xs">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <?php foreach ($section['columns'] as $col): ?>
                                                <th class="border border-gray-300 px-2 py-1 text-left font-bold"><?= $col['name'] ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($section['rows'] as $row): ?>
                                            <tr <?= isset($row['calculated']) && $row['calculated'] ? 'class="bg-gray-50 font-bold"' : '' ?>>
                                                <?php foreach ($section['columns'] as $col): ?>
                                                    <td class="border border-gray-300 px-2 py-1 text-right">
                                                        <?php if ($col['type'] === 'currency'): ?>
                                                            <?= number_format($row[$col['name']] ?? 0, 0, ',', '.') ?>
                                                        <?php else: ?>
                                                            <?= $row[$col['name']] ?? '' ?>
                                                        <?php endif; ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </section>
                    <?php endforeach; ?>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</div>

<script>
function toggleSection(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('i');
    
    content.classList.toggle('hidden');
    icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
}

function syncCALK() {
    if (confirm('Sinkronisasi CALK dengan data laporan keuangan terbaru?')) {
        fetch('<?= base_url('keuangan/calk/sync') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                tahun: <?= $tahun ?>
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('CALK berhasil disinkronisasi');
                location.reload();
            } else {
                alert('Gagal sinkronisasi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat sinkronisasi');
        });
    }
}

// Open first section by default
document.addEventListener('DOMContentLoaded', function() {
    const firstButton = document.querySelector('[onclick="toggleSection(this)"]');
    if (firstButton) {
        toggleSection(firstButton);
    }
});
</script>

<?= $this->endSection() ?>
