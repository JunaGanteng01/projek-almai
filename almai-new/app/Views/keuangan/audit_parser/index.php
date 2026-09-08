<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Audit Parser (AI)';
$activeMenu = 'audit-parser'; ?>

<!-- Breadcrumb Header -->
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">Audit Parser (AI)</h1>
            <p class="text-gray-400">Proses file hasil OCR Laporan Audit KAP dengan bantuan Google Gemini AI untuk pemetaan chart of account.</p>
        </div>
    </div>

    <div class="bg-[#1C1C1C] rounded-xl border border-white/10 p-6">
        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
            <i class="fas fa-upload text-blue-400"></i> Upload File Laporan Audit (PDF)
        </h2>
        <form id="uploadForm" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-400 mb-2">PILIH FILE PDF (.PDF)</label>
                <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" class="w-full bg-black/50 border border-white/10 rounded-lg text-white text-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-white/10 file:text-white hover:file:bg-white/20 transition-all cursor-pointer">
            </div>
            <button type="submit" id="btnProses" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                <i class="fas fa-cogs"></i> Proses dengan Gemini AI
            </button>
            <span id="loadingIndicator" style="display: none;" class="text-blue-400 text-sm flex items-center gap-2 mt-2">
                <i class="fas fa-spinner fa-spin"></i> Membaca dan menganalisis PDF...
            </span>
        </form>
    </div>

    <!-- Result Card -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 relative overflow-hidden" id="resultCard" style="display: none;">
        <h3 class="font-bold text-lg text-white mb-4 flex items-center gap-2">
            <i class="fas fa-table text-accent"></i> Hasil Ekstraksi AI & Mapping Akun
        </h3>
        <div class="bg-blue-500/10 border border-blue-500/20 p-4 rounded-lg mb-6 flex items-start gap-3 text-sm text-blue-300">
            <i class="fas fa-info-circle mt-0.5"></i>
            <p>Silakan periksa kembali hasil bacaan AI dan mapping kode akun di bawah ini sebelum menyimpan.</p>
        </div>
        
        <form id="saveForm">
            <?= csrf_field() ?>
            <div class="overflow-x-auto mb-6">
                <table class="w-full text-left text-sm text-gray-400" id="resultTable">
                    <thead class="text-xs text-gray-500 uppercase bg-black/50 border-b border-white/10">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-medium">Tanggal</th>
                            <th scope="col" class="px-4 py-3 font-medium">Nama Item (dari PDF)</th>
                            <th scope="col" class="px-4 py-3 font-medium">Nominal</th>
                            <th scope="col" class="px-4 py-3 font-medium">Posisi (D/K)</th>
                            <th scope="col" class="px-4 py-3 font-medium">Mapping Akun (Saran AI)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <!-- Data akan dimuat melalui AJAX -->
                    </tbody>
                </table>
            </div>
            
            <div class="border-t border-white/10 pt-6">
                <button type="submit" id="btnSave" class="bg-accent text-black font-bold px-6 py-2.5 rounded-lg hover:bg-green-500 transition flex items-center gap-2 text-sm shadow-[0_0_15px_rgba(51,232,24,0.3)] hover:shadow-[0_0_25px_rgba(51,232,24,0.5)]">
                    <i class="fas fa-save"></i> Simpan ke KAP-AI
                </button>
            </div>
        </form>
    </div>

    <!-- Saved KAP Data List -->
    <div class="bg-[#1C1C1C] rounded-xl border border-white/10 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                <i class="fas fa-database text-green-400"></i> Data KAP
            </h2>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-400">Filter Tahun:</label>
                <select id="filterTahun" class="bg-black border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white focus:border-accent focus:outline-none appearance-none">
                    <option value="">Semua</option>
                    <?php
                        $years = [];
                        if (!empty($savedData)) {
                            foreach ($savedData as $r) {
                                $y = substr($r['tanggal'] ?? '', 0, 4);
                                if ($y && !in_array($y, $years)) $years[] = $y;
                            }
                            rsort($years);
                        }
                        foreach ($years as $y): ?>
                            <option value="<?= $y ?>"><?= $y ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" id="searchKap" placeholder="Cari..." class="bg-black border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white focus:border-accent focus:outline-none w-40">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-400" id="kapTable">
                <thead class="text-xs text-gray-500 uppercase bg-black/50 border-b border-white/10">
                    <tr>
                        <th class="px-4 py-3 font-medium">Tanggal</th>
                        <th class="px-4 py-3 font-medium">Nama Item</th>
                        <th class="px-4 py-3 font-medium">Kode Akun</th>
                        <th class="px-4 py-3 font-medium">Nama Akun</th>
                        <th class="px-4 py-3 font-medium">Posisi</th>
                        <th class="px-4 py-3 font-medium">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if (empty($savedData)): ?>
                        <tr class="kap-empty-row">
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada data KAP.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($savedData as $row): ?>
                            <tr class="hover:bg-white/5 transition kap-row" data-tahun="<?= substr($row['tanggal'] ?? '', 0, 4) ?>">
                                <td class="px-4 py-3 text-white"><?= esc($row['tanggal']) ?></td>
                                <td class="px-4 py-3 text-white"><?= esc($row['nama_item']) ?></td>
                                <td class="px-4 py-3 font-mono text-blue-400"><?= esc($row['kode_akun'] ?? '-') ?></td>
                                <td class="px-4 py-3"><?= esc($row['nama_akun'] ?? '-') ?></td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-xs font-bold <?= $row['posisi'] === 'debit' ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' ?>">
                                        <?= strtoupper(esc($row['posisi'])) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-white font-mono text-right">
                                    <?= number_format((float)$row['nominal'], 2, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    let akunListGlobal = [];

    $('#uploadForm').submit(function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        $('#btnProses').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        $('#loadingIndicator').show();
        $('#resultCard').slideUp();
        
        $.ajax({
            url: '<?= base_url("keuangan/audit-parser/parse") ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#btnProses').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                $('#loadingIndicator').hide();
                
                if (response.csrf_hash) {
                    $('input[name="<?= csrf_token() ?>"]').val(response.csrf_hash);
                }
                
                if (response.status === 'success') {
                    akunListGlobal = response.akun_list;
                    renderTable(response.data);
                    $('#resultCard').slideDown();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                $('#btnProses').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                $('#loadingIndicator').hide();
                alert('Terjadi kesalahan pada server saat memproses data.');
            }
        });
    });

    function renderTable(data) {
        let tbody = $('#resultTable tbody');
        tbody.empty();
        
        if (!Array.isArray(data)) {
            tbody.append('<tr><td colspan="4" class="px-4 py-8 text-center text-red-400">Format balasan AI tidak sesuai (Bukan Array JSON)</td></tr>');
            return;
        }

        data.forEach(function(item, index) {
            let options = '<option value="">-- Pilih Akun --</option>';
            akunListGlobal.forEach(function(akun) {
                let selected = (akun.kode_akun == item.kode_akun_saran) ? 'selected' : '';
                options += `<option value="${akun.id}" ${selected}>${akun.kode_akun} - ${akun.nama_akun}</option>`;
            });

            let tr = `
                <tr class="hover:bg-white/5 transition">
                    <td class="px-4 py-3">
                        <input type="date" class="w-full bg-black border border-white/10 rounded px-3 py-1.5 text-sm text-white focus:border-accent focus:outline-none" name="items[${index}][tanggal]" value="<?= date('Y-01-01') ?>">
                    </td>
                    <td class="px-4 py-3">
                        <input type="hidden" name="items[${index}][nama_item]" value="${item.nama_item}">
                        <span class="text-white font-medium">${item.nama_item}</span>
                    </td>
                    <td class="px-4 py-3">
                        <input type="number" class="w-full bg-black border border-white/10 rounded px-3 py-1.5 text-sm text-white focus:border-accent focus:outline-none" name="items[${index}][nominal]" value="${item.nominal}">
                    </td>
                    <td class="px-4 py-3">
                        <select class="w-full bg-black border border-white/10 rounded px-3 py-1.5 text-sm text-white focus:border-accent focus:outline-none appearance-none" name="items[${index}][posisi]">
                            <option value="debit" ${item.nominal > 0 ? 'selected' : ''}>Debit</option>
                            <option value="kredit" ${item.nominal < 0 ? 'selected' : ''}>Kredit</option>
                        </select>
                    </td>
                    <td class="px-4 py-3">
                        <select class="w-full bg-black border border-white/10 rounded px-3 py-1.5 text-sm text-white focus:border-accent focus:outline-none appearance-none" name="items[${index}][akun_id]">
                            ${options}
                        </select>
                    </td>
                </tr>
            `;
            tbody.append(tr);
        });
    }

    $('#saveForm').submit(function(e) {
        e.preventDefault();
        
        let formData = $(this).serialize();
        let btn = $('#btnSave');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        
        $.ajax({
            url: '<?= base_url("keuangan/audit-parser/save") ?>',
            type: 'POST',
            data: formData,
            success: function(response) {
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Data');
                
                if (response.csrf_hash) {
                    $('input[name="<?= csrf_token() ?>"]').val(response.csrf_hash);
                }

                if (response.status === 'success') {
                    alert(response.message);
                    window.location.href = '<?= base_url("keuangan/audit-parser") ?>';
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Data');
                alert('Terjadi kesalahan saat menyimpan data.');
            }
        });
    });

    // Filter & Search Logic for KAP Table
    function filterKapTable() {
        let tahun = $('#filterTahun').val();
        let search = $('#searchKap').val().toLowerCase();

        $('.kap-row').each(function() {
            let row = $(this);
            let rowTahun = row.data('tahun').toString();
            let rowText = row.text().toLowerCase();

            let matchTahun = (tahun === '' || rowTahun === tahun);
            let matchSearch = (search === '' || rowText.includes(search));

            if (matchTahun && matchSearch) {
                row.show();
            } else {
                row.hide();
            }
        });
    }

    $('#filterTahun, #searchKap').on('input change', function() {
        filterKapTable();
    });
});
</script>
<?= $this->endSection() ?>
