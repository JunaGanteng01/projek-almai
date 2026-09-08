<?php $this->setVar('pageTitle', $title ?? 'Tambah Invoice Customer'); ?>
<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white"><?= esc($title ?? 'Tambah Invoice Customer') ?></h1>
        <p class="text-sm text-gray-400">Silakan lengkapi formulir di bawah ini untuk menyimpan data invoice customer.</p>
    </div>
    <div>
        <a href="<?= base_url('keuangan/invoices') ?>" class="w-full md:w-auto px-6 py-2.5 bg-gray-700 hover:bg-gray-600 text-white font-bold rounded-xl transition flex items-center justify-center gap-2 shadow-lg">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="bg-[#111] border border-white/10 rounded-2xl shadow-2xl p-6">
    <form id="invoiceForm" method="post" action="<?= base_url('keuangan/invoices/save') ?>" class="space-y-6">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="invoice_id" value="<?= $invoice ? $invoice['id'] : '' ?>">

        <!-- Row 1: Invoice Numbers & Dates & Currency -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Mata Uang *</label>
                <select name="currency" id="currency" required onchange="calculateTotal()" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2.5 text-white focus:border-accent focus:outline-none text-sm">
                    <option value="IDR" <?= ($invoice && $invoice['currency'] === 'IDR') ? 'selected' : '' ?>>Rupiah (IDR)</option>
                    <option value="USD" <?= ($invoice && $invoice['currency'] === 'USD') ? 'selected' : '' ?>>Dollar (USD)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">No Faktur *</label>
                <input type="text" name="invoice_number" id="invoice_number" required value="<?= $invoice ? esc($invoice['invoice_number']) : '' ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2.5 text-white focus:border-accent focus:outline-none font-mono text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Tanggal Faktur *</label>
                <input type="date" name="tanggal" id="tanggal" required value="<?= $invoice ? $invoice['tanggal'] : '' ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2.5 text-white focus:border-accent focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Tanggal Jatuh Tempo <span class="text-[10px] text-gray-500 font-bold">(Opsional)</span></label>
                <input type="date" name="jatuh_tempo" id="jatuh_tempo" value="<?= $invoice ? $invoice['jatuh_tempo'] : '' ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2.5 text-white focus:border-accent focus:outline-none text-sm">
            </div>
        </div>

        <!-- Row 2: Customer Identity -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Nama Customer *</label>
                <input type="text" name="customer_name" id="customer_name" required value="<?= $invoice ? esc($invoice['customer_name']) : '' ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2.5 text-white focus:border-accent focus:outline-none text-sm" placeholder="Nama lengkap customer...">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Email Customer <span class="text-[10px] text-gray-500 font-bold">(Opsional)</span></label>
                <input type="email" name="customer_email" id="customer_email" value="<?= $invoice ? esc($invoice['customer_email']) : '' ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2.5 text-white focus:border-accent focus:outline-none text-sm" placeholder="customer@email.com">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Nomor WhatsApp *</label>
                <input type="text" name="customer_phone" id="customer_phone" required value="<?= $invoice ? esc($invoice['customer_phone']) : '' ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2.5 text-white focus:border-accent focus:outline-none text-sm font-mono" placeholder="Contoh: 08123456789">
                <div class="flex items-center gap-2 mt-2">
                    <input type="checkbox" name="send_wa" id="send_wa" value="1" <?= !$invoice ? 'checked' : '' ?> class="w-4 h-4 rounded bg-black border-white/25 text-accent focus:ring-accent focus:ring-offset-black">
                    <label for="send_wa" class="text-xs text-gray-400 cursor-pointer select-none font-medium">Kirim notifikasi via WhatsApp</label>
                </div>
            </div>
        </div>

        <!-- Alamat Customer -->
        <div>
            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Alamat Customer <span class="text-[10px] text-gray-500 font-bold">(Opsional)</span></label>
            <textarea name="customer_address" id="customer_address" rows="2" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none text-sm" placeholder="Alamat lengkap customer..."><?= $invoice ? esc($invoice['customer_address'] ?? '') : '' ?></textarea>
        </div>

        <!-- Itemized Products/Services List -->
        <div class="border border-white/10 rounded-2xl p-5 bg-black/40">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-300">Rincian Item Jasa / Layanan</h3>
                <button type="button" onclick="addItemRow()" class="px-3.5 py-1.5 bg-accent hover:bg-white text-black font-extrabold rounded-xl text-xs transition flex items-center gap-1 shadow-md shadow-accent/15">
                    <i class="fas fa-plus"></i> Tambah Baris Jasa
                </button>
            </div>
            
            <div class="overflow-x-auto scrollbar-hide">
                <table class="w-full text-left" id="itemsTable">
                    <thead>
                        <tr class="border-b border-white/10 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="pb-3 w-[45%]">Nama Jasa / Produk *</th>
                            <th class="pb-3 w-20 text-center">Qty</th>
                            <th class="pb-3 w-36 text-right">Harga Satuan *</th>
                            <th class="pb-3 w-36 text-right">Total Harga</th>
                            <th class="pb-3 w-10 text-center"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsContainer" class="divide-y divide-white/5">
                        <!-- Rows injected dynamically via JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Row 3: Totals and PPN with strict Background Overrides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div class="col-span-2">
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Subtotal Tagihan</label>
                <input type="text" id="subtotal_display" readonly style="background-color: #1a1a1a !important; color: #ffffff !important;" class="w-full border border-white/10 rounded-xl px-4 py-2.5 focus:outline-none font-bold font-mono text-sm shadow-inner" value="Rp 0">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">PPN (%)</label>
                <input type="number" name="ppn_percent" id="ppn_percent" min="0" max="100" value="<?= $invoice ? $invoice['ppn'] : '0' ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2.5 text-white focus:border-accent focus:outline-none font-mono text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Nominal Pajak</label>
                <input type="text" id="ppn_nominal" readonly style="background-color: #1a1a1a !important; color: #ffffff !important;" class="w-full border border-white/10 rounded-xl px-4 py-2.5 focus:outline-none font-mono text-sm" value="Rp 0">
            </div>
        </div>

        <!-- Row 4: Total Display & Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Total Akhir (Setelah PPN)</label>
                <input type="text" id="total_display" readonly style="background-color: #1a1a1a !important; color: #c0ff00 !important;" class="w-full border border-white/10 rounded-xl px-4 py-3 font-black text-xl focus:outline-none font-mono" value="Rp 0">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Status Invoice *</label>
                <select name="status" id="status" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3.5 text-white focus:border-accent focus:outline-none text-sm font-semibold">
                    <option value="pending" <?= ($invoice && $invoice['status'] === 'pending') ? 'selected' : '' ?>>PENDING</option>
                    <option value="paid" <?= ($invoice && $invoice['status'] === 'paid') ? 'selected' : '' ?>>PAID</option>
                    <option value="cancelled" <?= ($invoice && $invoice['status'] === 'cancelled') ? 'selected' : '' ?>>CANCELLED</option>
                </select>
            </div>
        </div>

        <!-- Notes -->
        <div>
            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Keterangan Tambahan / Syarat Pembayaran</label>
            <textarea name="notes" id="notes" rows="3" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none text-sm" placeholder="Tuliskan petunjuk pembayaran atau catatan memo jika ada..."><?= $invoice ? esc($invoice['notes'] ?? '') : '' ?></textarea>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 pt-5 border-t border-white/10">
            <a href="<?= base_url('keuangan/invoices') ?>" class="px-6 py-3 bg-white/5 text-white font-semibold rounded-xl hover:bg-white/15 transition text-sm">
                Batal
            </a>
            <button type="submit" class="px-7 py-3 bg-accent text-black font-extrabold rounded-xl hover:bg-white transition text-sm shadow-lg shadow-accent/15 flex items-center gap-2">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const isEdit = <?= $invoice ? 'true' : 'false' ?>;
        
        if (!isEdit) {
            document.getElementById('tanggal').valueAsDate = new Date();
            generateNoFaktur();
            addItemRow();
        } else {
            // Load existing items
            const itemsData = <?= $invoice && !empty($invoice['items']) ? $invoice['items'] : 'null' ?>;
            if (itemsData && itemsData.length > 0) {
                itemsData.forEach(item => {
                    addItemRow(item.name, item.qty, item.price);
                });
            } else {
                // Fallback for older data
                addItemRow('<?= $invoice ? addslashes($invoice['product_name']) : '' ?>', 1, <?= $invoice ? $invoice['subtotal'] : 0 ?>);
            }
        }
        
        calculateTotal();
    });

    // Dynamic itemized row rendering
    function addItemRow(name = '', qty = 1, price = 0) {
        const container = document.getElementById('itemsContainer');
        const tr = document.createElement('tr');
        tr.className = 'item-row group border-b border-white/5 last:border-b-0';
        tr.innerHTML = `
            <td class="py-3 pr-3">
                <input type="text" name="item_name[]" value="${escapeHtml(name)}" required class="w-full bg-black border border-white/15 rounded-xl px-4 py-2.5 text-xs text-white focus:border-accent focus:outline-none" placeholder="Deskripsi jasa / produk...">
            </td>
            <td class="py-3 px-1.5 w-20">
                <input type="number" name="item_qty[]" value="${qty}" required min="1" oninput="calculateRowTotal(this)" class="item-qty w-full bg-black border border-white/15 rounded-xl px-2 py-2.5 text-xs text-white text-center focus:border-accent focus:outline-none">
            </td>
            <td class="py-3 px-1.5 w-36">
                <input type="number" name="item_price[]" value="${price}" required min="0" oninput="calculateRowTotal(this)" class="item-price w-full bg-black border border-white/15 rounded-xl px-3 py-2.5 text-xs text-white text-right focus:border-accent focus:outline-none font-mono">
            </td>
            <td class="py-3 pl-3 text-right w-36">
                <div class="text-right py-2 px-3 font-mono text-xs text-gray-200 font-bold item-total-display">Rp 0</div>
            </td>
            <td class="py-3 text-center w-10">
                <button type="button" onclick="removeItemRow(this)" class="text-red-400 hover:text-red-500 transition p-2">
                    <i class="fas fa-trash-alt text-xs"></i>
                </button>
            </td>
        `;
        container.appendChild(tr);
        
        // Trigger calc
        const priceInput = tr.querySelector('.item-price');
        calculateRowTotal(priceInput);
    }

    function removeItemRow(btn) {
        const container = document.getElementById('itemsContainer');
        const rows = container.querySelectorAll('.item-row');
        if (rows.length <= 1) {
            alert('Invoice minimal harus memiliki 1 rincian item.');
            return;
        }
        btn.closest('tr').remove();
        calculateTotal();
    }

    function calculateRowTotal(input) {
        const tr = input.closest('tr');
        const qty = parseFloat(tr.querySelector('.item-qty').value) || 0;
        const price = parseFloat(tr.querySelector('.item-price').value) || 0;
        const total = qty * price;
        
        tr.querySelector('.item-total-display').innerText = formatCurrency(total);
        calculateTotal();
    }

    function calculateTotal() {
        const container = document.getElementById('itemsContainer');
        const rows = container.querySelectorAll('.item-row');
        let subtotal = 0;

        rows.forEach(tr => {
            const qty = parseFloat(tr.querySelector('.item-qty').value) || 0;
            const price = parseFloat(tr.querySelector('.item-price').value) || 0;
            subtotal += (qty * price);
        });

        const ppnPercent = parseFloat(document.getElementById('ppn_percent').value) || 0;
        const ppnNominal = subtotal * (ppnPercent / 100);
        const total = subtotal + ppnNominal;

        // Update display inputs
        document.getElementById('subtotal_display').value = formatCurrency(subtotal);
        document.getElementById('ppn_nominal').value = formatCurrency(ppnNominal);
        document.getElementById('total_display').value = formatCurrency(total);
    }

    // Auto generate dynamic invoice number on date change
    document.getElementById('tanggal').addEventListener('change', generateNoFaktur);

    function generateNoFaktur() {
        const id = document.getElementById('invoice_id').value;
        if (id) {
            return; // Don't overwrite when editing
        }
        
        const tanggalVal = document.getElementById('tanggal').value;
        if (!tanggalVal) return;
        
        fetch('<?= base_url('keuangan/invoices/generate-faktur') ?>?date=' + tanggalVal)
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    document.getElementById('invoice_number').value = res.invoice_number;
                }
            })
            .catch(error => console.error('Error generating invoice number:', error));
    }

    function formatCurrency(amount) {
        const curr = document.getElementById('currency').value || 'IDR';
        if (curr === 'USD') {
            return '$ ' + amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        return 'Rp ' + amount.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Real-time calculation on PPN percent input
    document.getElementById('ppn_percent').addEventListener('input', calculateTotal);
</script>

<?= $this->endSection() ?>
