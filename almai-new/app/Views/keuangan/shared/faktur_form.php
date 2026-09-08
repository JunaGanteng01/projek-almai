<?php
/**
 * Form faktur penjualan/pembelian (layout mirip ERP).
 * @var string $mode penjualan|pembelian
 * @var array|null $record
 * @var array $kontakList
 * @var string $saveUrl
 * @var string $backUrl
 * @var string $nomorLabel
 * @var string $nomorName
 * @var string $kontakLabel
 */
use App\Services\Keuangan\FakturFormService;

$r = $record ?? [];
$items = FakturFormService::itemsFromRecord($r);
$isEdit = !empty($r['id']);
$nomorVal = $r[$nomorName] ?? '';
$ppnGlobal = (float)($r['ppn'] ?? 11);
?>
<style>
.faktur-input {
    width: 100%;
    background: #000;
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 0.75rem;
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    color: #fff;
    transition: all 0.2s ease;
}
.faktur-input:focus {
    border-color: #33e818;
    outline: none;
    box-shadow: 0 0 0 3px rgba(51, 232, 24, 0.1);
}
.faktur-input:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.faktur-th {
    padding: 0.75rem;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: rgba(0,0,0,0.3);
}
.faktur-td {
    padding: 0.5rem 0.75rem;
    vertical-align: middle;
}
.line-item-row input,
.line-item-row select {
    width: 100%;
    background: #000;
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 0.5rem;
    padding: 0.5rem 0.625rem;
    font-size: 0.8125rem;
    color: #fff;
    transition: all 0.2s ease;
}
.line-item-row input:focus,
.line-item-row select:focus {
    border-color: #33e818;
    box-shadow: 0 0 0 2px rgba(51, 232, 24, 0.1);
}
.section-card {
    background: #111;
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 1.25rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.section-title {
    font-size: 0.875rem;
    font-weight: 700;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.section-title i {
    color: #33e818;
}
@media (max-width: 768px) {
    .section-card {
        padding: 1rem;
    }
    .faktur-th {
        font-size: 9px;
        padding: 0.5rem;
    }
    .faktur-td {
        padding: 0.375rem 0.5rem;
    }
}
</style>

<form id="fakturForm" action="<?= esc($saveUrl) ?>" method="post" class="space-y-6">
    <?= csrf_field() ?>
    <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><?php endif; ?>

    <!-- Section 1: Informasi Dasar -->
    <div class="section-card">
        <div class="section-title">
            <i class="fas fa-file-alt"></i> Informasi Dasar
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Kontak -->
            <div class="lg:col-span-2">
                <label class="text-xs text-gray-400 font-bold"><?= esc($kontakLabel) ?> <span class="text-red-400">*</span></label>
                <select name="kontak_id" id="kontakSelect" required class="faktur-input mt-2">
                    <option value="">— Pilih <?= strtolower($kontakLabel) ?> —</option>
                    <?php foreach ($kontakList as $k): ?>
                        <option value="<?= $k['id'] ?>"
                            <?= (int)($r['kontak_id'] ?? 0) === (int)$k['id'] ? 'selected' : '' ?>
                            data-email="<?= esc($k['email'] ?? '') ?>"
                            data-perusahaan="<?= esc($k['perusahaan'] ?? '') ?>"
                            data-alamat="<?= esc($k['alamat'] ?? '') ?>"
                            data-nama="<?= esc($k['nama']) ?>">
                            <?= esc($k['nama']) ?><?= $k['perusahaan'] ? ' — ' . esc($k['perusahaan']) : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="<?= $mode === 'penjualan' ? 'nama_kontak' : 'nama_supplier' ?>" id="namaKontakHidden" value="<?= esc($mode === 'penjualan' ? ($r['nama_kontak'] ?? '') : ($r['nama_supplier'] ?? '')) ?>">
            </div>

            <!-- Nomor Faktur -->
            <div>
                <label class="text-xs text-gray-400 font-bold"><?= esc($nomorLabel) ?> <span class="text-red-400">*</span></label>
                <div class="flex gap-2 mt-2">
                    <input type="text" name="<?= esc($nomorName) ?>" id="nomorFaktur" required value="<?= esc($nomorVal) ?>" class="faktur-input flex-1" placeholder="INV/...">
                    <?php if (!$isEdit): ?>
                    <button type="button" onclick="generateNomor()" class="px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-xs text-accent hover:bg-white/10 transition" title="Generate nomor otomatis">
                        <i class="fas fa-magic"></i>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Tanggal & Termin -->
    <div class="section-card">
        <div class="section-title">
            <i class="fas fa-calendar"></i> Tanggal & Termin
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="text-xs text-gray-400 font-bold">Tgl. Transaksi <span class="text-red-400">*</span></label>
                <input type="date" name="tanggal_transaksi" id="tglTransaksi" required value="<?= esc($r['tanggal_transaksi'] ?? date('Y-m-d')) ?>" class="faktur-input mt-2">
            </div>
            <div>
                <label class="text-xs text-gray-400 font-bold">Tgl. Jatuh Tempo</label>
                <input type="date" name="tanggal_jatuh_tempo" id="tglJatuhTempo" value="<?= esc($r['tanggal_jatuh_tempo'] ?? date('Y-m-d', strtotime('+30 days'))) ?>" class="faktur-input mt-2">
            </div>
            <div>
                <label class="text-xs text-gray-400 font-bold">Termin</label>
                <select name="termin_hari" id="terminHari" class="faktur-input mt-2" onchange="applyTermin()">
                    <?php foreach ([0, 7, 14, 30, 45, 60] as $t): ?>
                        <option value="<?= $t ?>" <?= (int)($r['termin_hari'] ?? 30) === $t ? 'selected' : '' ?>><?= $t === 0 ? 'Cash' : 'Net ' . $t ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-xs text-gray-400 font-bold">Tgl. Pembayaran</label>
                <input type="date" name="tanggal_pembayaran" value="<?= esc($r['tanggal_pembayaran'] ?? '') ?>" class="faktur-input mt-2">
            </div>
        </div>
    </div>

    <!-- Section 3: Referensi & Kontak -->
    <div class="section-card">
        <div class="section-title">
            <i class="fas fa-link"></i> Referensi & Kontak
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="text-xs text-gray-400 font-bold">Referensi</label>
                <input type="text" name="referensi" value="<?= esc($r['referensi'] ?? '') ?>" class="faktur-input mt-2" placeholder="No. PO / referensi">
            </div>
            <div>
                <label class="text-xs text-gray-400 font-bold">Tag</label>
                <input type="text" name="tag" value="<?= esc($r['tag'] ?? '') ?>" class="faktur-input mt-2" placeholder="Tag">
            </div>
            <div class="lg:col-span-2">
                <label class="text-xs text-gray-400 font-bold">Email</label>
                <input type="email" name="email" id="emailKontak" value="<?= esc($r['email'] ?? '') ?>" class="faktur-input mt-2">
            </div>
        </div>
    </div>

    <!-- Section 4: Item Produk -->
    <div class="section-card">
        <div class="section-title">
            <i class="fas fa-box"></i> Item Produk
        </div>
        <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
            <label class="flex items-center gap-2 text-xs text-gray-400 cursor-pointer hover:text-white transition">
                <input type="checkbox" name="harga_termasuk_pajak" value="1" id="termasukPajak" <?= !empty($r['harga_termasuk_pajak']) ? 'checked' : '' ?> class="rounded border-white/20" onchange="recalcAll()">
                Harga termasuk pajak
            </label>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full" id="lineItemsTable">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="faktur-th w-8">#</th>
                        <th class="faktur-th flex-1">Produk</th>
                        <th class="faktur-th flex-1">Deskripsi</th>
                        <th class="faktur-th w-16">Qty</th>
                        <th class="faktur-th w-20">Satuan</th>
                        <th class="faktur-th w-20">Diskon</th>
                        <th class="faktur-th w-24">Harga</th>
                        <th class="faktur-th w-16">Pajak %</th>
                        <th class="faktur-th w-24 text-right">Jumlah</th>
                        <th class="faktur-th w-8"></th>
                    </tr>
                </thead>
                <tbody id="lineItemsBody">
                    <?php foreach ($items as $idx => $item): ?>
                    <tr class="line-item-row border-b border-white/5" data-index="<?= $idx ?>">
                        <td class="faktur-td text-gray-500 text-xs row-num"><?= $idx + 1 ?></td>
                        <td class="faktur-td"><input type="text" name="item_produk[]" value="<?= esc($item['produk'] ?? '') ?>" placeholder="Produk" oninput="recalcRow(this)"></td>
                        <td class="faktur-td"><input type="text" name="item_deskripsi[]" value="<?= esc($item['deskripsi'] ?? '') ?>" placeholder="Deskripsi"></td>
                        <td class="faktur-td"><input type="number" name="item_qty[]" value="<?= esc($item['qty'] ?? 1) ?>" min="0" step="0.01" oninput="recalcRow(this)"></td>
                        <td class="faktur-td"><input type="text" name="item_satuan[]" value="<?= esc($item['satuan'] ?? 'pcs') ?>"></td>
                        <td class="faktur-td"><input type="number" name="item_diskon[]" value="<?= esc($item['diskon'] ?? 0) ?>" min="0" step="0.01" oninput="recalcRow(this)"></td>
                        <td class="faktur-td"><input type="number" name="item_harga[]" value="<?= esc($item['harga'] ?? 0) ?>" min="0" step="0.01" oninput="recalcRow(this)"></td>
                        <td class="faktur-td"><input type="number" name="item_pajak[]" value="<?= esc($item['pajak'] ?? 0) ?>" min="0" step="0.01" oninput="recalcRow(this)"></td>
                        <td class="faktur-td text-right"><span class="row-jumlah text-white font-medium text-xs"><?= number_format($item['jumlah'] ?? 0, 0, ',', '.') ?></span></td>
                        <td class="faktur-td"><button type="button" onclick="removeRow(this)" class="text-red-400 hover:text-red-300 p-1 transition"><i class="fas fa-trash text-xs"></i></button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="button" onclick="addRow()" class="mt-4 w-full py-3 border border-dashed border-white/20 rounded-xl text-sm text-gray-400 hover:text-accent hover:border-accent/40 transition">
            <i class="fas fa-plus mr-2"></i> Tambah Baris
        </button>
    </div>

    <!-- Section 5: Catatan & Akun -->
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 section-card">
            <div class="section-title">
                <i class="fas fa-sticky-note"></i> Catatan & Akun
            </div>
            <div class="space-y-4">
                <div>
                    <label class="text-xs text-gray-400 font-bold">Pesan / Catatan</label>
                    <textarea name="catatan" rows="3" class="faktur-input mt-2" placeholder="Catatan untuk faktur..."><?= esc($r['catatan'] ?? '') ?></textarea>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-400 font-bold">Status Pembayaran</label>
                        <select name="status_pembayaran" class="faktur-input mt-2">
                            <option value="belum_dibayar" <?= ($r['status_pembayaran'] ?? 'belum_dibayar') === 'belum_dibayar' ? 'selected' : '' ?>>Belum Dibayar</option>
                            <option value="sebagian_dibayar" <?= ($r['status_pembayaran'] ?? '') === 'sebagian_dibayar' ? 'selected' : '' ?>>Sebagian Dibayar</option>
                            <option value="lunas" <?= ($r['status_pembayaran'] ?? '') === 'lunas' ? 'selected' : '' ?>>Lunas</option>
                        </select>
                    </div>
                    <?php if ($mode === 'penjualan'): ?>
                    <div>
                        <label class="text-xs text-gray-400 font-bold">Kode Akun Pendapatan</label>
                        <input type="text" name="kode_akun_revenue" value="<?= esc($r['kode_akun_revenue'] ?? '') ?>" class="faktur-input mt-2" placeholder="Opsional">
                    </div>
                    <?php else: ?>
                    <div>
                        <label class="text-xs text-gray-400 font-bold">Kode Akun Beban</label>
                        <input type="text" name="kode_akun_beban" value="<?= esc($r['kode_akun_beban'] ?? '') ?>" class="faktur-input mt-2">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-bold">Kode Akun Hutang</label>
                        <input type="text" name="kode_akun_hutang" value="<?= esc($r['kode_akun_hutang'] ?? '') ?>" class="faktur-input mt-2">
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Section 6: Ringkasan Biaya -->
        <div class="section-card">
            <div class="section-title">
                <i class="fas fa-calculator"></i> Ringkasan
            </div>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Sub Total</span>
                    <span id="displaySubtotal" class="text-white font-medium">0</span>
                </div>
                <input type="hidden" name="ppn" id="ppnGlobal" value="<?= $ppnGlobal ?>">
                <div class="flex justify-between items-center gap-2">
                    <span class="text-gray-400">PPN (%)</span>
                    <input type="number" id="ppnPercentInput" value="<?= $ppnGlobal ?>" min="0" max="100" step="0.01" class="faktur-input w-20 text-right text-xs" oninput="document.getElementById('ppnGlobal').value=this.value; recalcAll()">
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Pengiriman</span>
                    <input type="number" name="biaya_pengiriman" id="biayaPengiriman" value="<?= (float)($r['biaya_pengiriman'] ?? 0) ?>" min="0" class="faktur-input w-24 text-right text-xs" oninput="recalcAll()">
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Diskon Tambahan</span>
                    <input type="number" name="diskon_tambahan" id="diskonTambahan" value="<?= (float)($r['diskon_tambahan'] ?? 0) ?>" min="0" class="faktur-input w-24 text-right text-xs" oninput="recalcAll()">
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Biaya Transaksi</span>
                    <input type="number" name="biaya_transaksi" id="biayaTransaksi" value="<?= (float)($r['biaya_transaksi'] ?? 0) ?>" min="0" class="faktur-input w-24 text-right text-xs" oninput="recalcAll()">
                </div>
                <?php if ($mode === 'pembelian'): ?>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Biaya Lainnya</span>
                    <input type="number" name="biaya_lainnya" id="biayaLainnya" value="<?= (float)($r['biaya_lainnya'] ?? 0) ?>" min="0" class="faktur-input w-24 text-right text-xs" oninput="recalcAll()">
                </div>
                <?php endif; ?>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Uang Muka</span>
                    <input type="number" name="uang_muka" id="uangMuka" value="<?= (float)($r['uang_muka'] ?? 0) ?>" min="0" class="faktur-input w-24 text-right text-xs" oninput="recalcAll()">
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Pemotongan</span>
                    <input type="number" name="pemotongan" id="pemotongan" value="<?= (float)($r['pemotongan'] ?? 0) ?>" min="0" class="faktur-input w-24 text-right text-xs" oninput="recalcAll()">
                </div>
                <div class="border-t border-white/10 pt-3 flex justify-between">
                    <span class="text-white font-bold">Total</span>
                    <span id="displayTotal" class="text-accent font-bold text-base">0</span>
                </div>
                <div class="flex justify-between text-xs bg-white/5 p-2 rounded">
                    <span class="text-gray-500">Sisa Tagihan</span>
                    <span id="displaySisa" class="text-white font-bold">0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-3 justify-end pt-4">
        <a href="<?= esc($backUrl) ?>" class="px-6 py-2.5 border border-white/10 rounded-xl text-gray-400 hover:bg-white/5 transition">
            <i class="fas fa-times mr-2"></i> Batal
        </a>
        <button type="submit" class="px-6 py-2.5 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
            <i class="fas fa-save"></i> Simpan
        </button>
    </div>
</form>

<template id="rowTemplate">
    <tr class="line-item-row border-b border-white/5">
        <td class="faktur-td text-gray-500 text-xs row-num">1</td>
        <td class="faktur-td"><input type="text" name="item_produk[]" placeholder="Produk" oninput="recalcRow(this)"></td>
        <td class="faktur-td"><input type="text" name="item_deskripsi[]"></td>
        <td class="faktur-td"><input type="number" name="item_qty[]" value="1" min="0" step="0.01" oninput="recalcRow(this)"></td>
        <td class="faktur-td"><input type="text" name="item_satuan[]" value="pcs"></td>
        <td class="faktur-td"><input type="number" name="item_diskon[]" value="0" min="0" step="0.01" oninput="recalcRow(this)"></td>
        <td class="faktur-td"><input type="number" name="item_harga[]" value="0" min="0" step="0.01" oninput="recalcRow(this)"></td>
        <td class="faktur-td"><input type="number" name="item_pajak[]" value="0" min="0" step="0.01" oninput="recalcRow(this)"></td>
        <td class="faktur-td text-right"><span class="row-jumlah text-white font-medium text-xs">0</span></td>
        <td class="faktur-td"><button type="button" onclick="removeRow(this)" class="text-red-400 p-1"><i class="fas fa-trash text-xs"></i></button></td>
    </tr>
</template>

<script>
const FAKTUR_MODE = <?= json_encode($mode) ?>;
const GENERATE_URL = <?= json_encode(base_url('keuangan/' . $mode . '/generate-nomor')) ?>;

function fmt(n) { return new Intl.NumberFormat('id-ID').format(Math.round(n)); }

function recalcRow(el) {
    const row = el.closest('tr');
    const qty = parseFloat(row.querySelector('[name="item_qty[]"]').value) || 0;
    const harga = parseFloat(row.querySelector('[name="item_harga[]"]').value) || 0;
    const diskon = parseFloat(row.querySelector('[name="item_diskon[]"]').value) || 0;
    const pajak = parseFloat(row.querySelector('[name="item_pajak[]"]').value) || 0;
    let sub = Math.max(0, qty * harga - diskon);
    if (!document.getElementById('termasukPajak').checked && pajak > 0) {
        sub += sub * (pajak / 100);
    }
    row.querySelector('.row-jumlah').textContent = fmt(sub);
    recalcAll();
}

function recalcAll() {
    let sub = 0;
    document.querySelectorAll('.line-item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('[name="item_qty[]"]').value) || 0;
        const harga = parseFloat(row.querySelector('[name="item_harga[]"]').value) || 0;
        const diskon = parseFloat(row.querySelector('[name="item_diskon[]"]').value) || 0;
        const pajak = parseFloat(row.querySelector('[name="item_pajak[]"]').value) || 0;
        let line = Math.max(0, qty * harga - diskon);
        if (!document.getElementById('termasukPajak').checked && pajak > 0) line += line * (pajak / 100);
        sub += line;
    });
    const ppnPct = parseFloat(document.getElementById('ppnPercentInput').value) || 0;
    const pengiriman = parseFloat(document.getElementById('biayaPengiriman')?.value) || 0;
    const diskonT = parseFloat(document.getElementById('diskonTambahan')?.value) || 0;
    const biayaT = parseFloat(document.getElementById('biayaTransaksi')?.value) || 0;
    const lain = parseFloat(document.getElementById('biayaLainnya')?.value) || 0;
    const uangMuka = parseFloat(document.getElementById('uangMuka')?.value) || 0;
    const potong = parseFloat(document.getElementById('pemotongan')?.value) || 0;
    let ppnNom = document.getElementById('termasukPajak').checked ? 0 : sub * (ppnPct / 100);
    let total = sub + ppnNom + pengiriman + biayaT + lain - diskonT - potong - uangMuka;
    if (total < 0) total = 0;
    document.getElementById('displaySubtotal').textContent = fmt(sub);
    document.getElementById('displayTotal').textContent = fmt(total);
    document.getElementById('displaySisa').textContent = fmt(total);
    renumberRows();
}

function renumberRows() {
    document.querySelectorAll('#lineItemsBody .line-item-row').forEach((row, i) => {
        row.querySelector('.row-num').textContent = i + 1;
    });
}

function addRow() {
    const tpl = document.getElementById('rowTemplate').content.cloneNode(true);
    document.getElementById('lineItemsBody').appendChild(tpl);
    renumberRows();
}

function removeRow(btn) {
    const rows = document.querySelectorAll('#lineItemsBody .line-item-row');
    if (rows.length <= 1) return;
    btn.closest('tr').remove();
    recalcAll();
}

function applyTermin() {
    const days = parseInt(document.getElementById('terminHari').value, 10);
    const tgl = document.getElementById('tglTransaksi').value;
    if (!tgl || days <= 0) return;
    const d = new Date(tgl);
    d.setDate(d.getDate() + days);
    document.getElementById('tglJatuhTempo').value = d.toISOString().slice(0, 10);
}

function generateNomor() {
    fetch(GENERATE_URL + '?date=' + (document.getElementById('tglTransaksi').value || ''))
        .then(r => r.json())
        .then(d => { if (d.success && d.nomor) document.getElementById('nomorFaktur').value = d.nomor; });
}

document.getElementById('kontakSelect')?.addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    document.getElementById('namaKontakHidden').value = opt.dataset.nama || '';
    document.getElementById('emailKontak').value = opt.dataset.email || '';
});

document.getElementById('tglTransaksi')?.addEventListener('change', applyTermin);
recalcAll();
</script>
