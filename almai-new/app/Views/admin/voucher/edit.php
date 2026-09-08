<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Edit Voucher'; $pageSubtitle = $voucher['code']; ?>

<div class="mb-6">
    <a href="<?= base_url('admin/voucher') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-accent transition">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<?php if (session()->getFlashdata('errors')): ?>
<div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-6">
    <ul class="list-disc list-inside text-sm">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
        <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form action="<?= base_url('admin/voucher/update/' . $voucher['id']) ?>" method="post">
    <?= csrf_field() ?>
    
    <div class="grid md:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="md:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                <h3 class="font-bold mb-4"><i class="fas fa-info-circle mr-2 text-accent"></i>Informasi Dasar</h3>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Kode Voucher *</label>
                        <input type="text" name="code" value="<?= old('code', $voucher['code']) ?>" required
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none uppercase font-mono">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Nama Voucher *</label>
                        <input type="text" name="name" value="<?= old('name', $voucher['name']) ?>" required
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none">
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="block text-sm text-gray-400 mb-2">Deskripsi</label>
                    <textarea name="description" rows="2"
                        class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none"><?= old('description', $voucher['description']) ?></textarea>
                </div>
            </div>

            <!-- Discount Settings -->
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                <h3 class="font-bold mb-4"><i class="fas fa-percent mr-2 text-accent"></i>Pengaturan Diskon</h3>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Tipe Diskon *</label>
                        <select name="discount_type" id="discountType" onchange="toggleMaxDiscount()" required
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none">
                            <option value="percentage" <?= $voucher['discount_type'] === 'percentage' ? 'selected' : '' ?>>Persentase (%)</option>
                            <option value="fixed" <?= $voucher['discount_type'] === 'fixed' ? 'selected' : '' ?>>Nominal Tetap (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Nilai Diskon *</label>
                        <input type="number" name="discount_value" value="<?= old('discount_value', $voucher['discount_value']) ?>" required min="1" step="0.01"
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Minimum Pembelian</label>
                        <input type="number" name="min_purchase" value="<?= old('min_purchase', $voucher['min_purchase']) ?>" min="0"
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none">
                    </div>
                    <div id="maxDiscountField">
                        <label class="block text-sm text-gray-400 mb-2">Maksimal Diskon</label>
                        <input type="number" name="max_discount" value="<?= old('max_discount', $voucher['max_discount']) ?>" min="0"
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Product Selection -->
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                <h3 class="font-bold mb-4"><i class="fas fa-box mr-2 text-accent"></i>Produk Terkait</h3>
                
                <div class="mb-4">
                    <label class="block text-sm text-gray-400 mb-2">Berlaku Untuk</label>
                    <select name="product_type" id="productType" onchange="toggleProductList()" 
                        class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none">
                        <?php $currentType = $voucher['product_type'] ?: 'all'; ?>
                        <option value="all" <?= $currentType === 'all' ? 'selected' : '' ?>>Semua Produk</option>
                        <option value="layanan" <?= $currentType === 'layanan' ? 'selected' : '' ?>>Layanan Saja</option>
                    </select>
                </div>

                <!-- Hierarchical Layanan Selection -->
                <div id="layananSelection" class="<?= $voucher['product_type'] !== 'layanan' ? 'hidden' : '' ?> space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Kategori</label>
                            <select id="selectCategory" onchange="updateSubcategories()"
                                class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none">
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['slug'] ?>"><?= esc($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Sub Kategori</label>
                            <select id="selectSubcategory" onchange="filterLayananItems()"
                                class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none">
                                <option value="">Pilih Sub Kategori</option>
                            </select>
                        </div>
                    </div>

                    <div id="layananItemsContainer" class="space-y-2">
                        <label class="block text-sm text-gray-400 mb-2">Cari & Pilih Item</label>
                        <div class="max-h-60 overflow-y-auto bg-black/50 rounded-xl p-3 border border-white/5" id="layananListBody">
                            <p class="text-gray-500 text-sm italic text-center py-4">Pilih kategori & subkategori terlebih dahulu</p>
                        </div>
                    </div>

                    <!-- Selected Items "Basket" -->
                    <div class="mt-6 pt-6 border-t border-white/5">
                        <label class="block text-sm font-bold text-accent mb-3">Item Terpilih (Voucher Berlaku Untuk):</label>
                        <div id="selectedBasket" class="space-y-2">
                            <p class="text-gray-500 text-xs italic">Belum ada item dipilih. Jika kosong, voucher berlaku untuk SEMUA item di subkategori/kategori terpilih.</p>
                        </div>
                        <!-- Hidden inputs for actual form submission -->
                        <div id="hiddenInputs"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Usage Stats -->
            <div class="bg-gradient-to-br from-accent/20 to-accent/5 border border-accent/30 rounded-2xl p-6">
                <h3 class="font-bold mb-4"><i class="fas fa-chart-bar mr-2 text-accent"></i>Statistik</h3>
                <div class="text-center">
                    <p class="text-4xl font-bold text-accent"><?= $voucher['used_count'] ?></p>
                    <p class="text-gray-400 text-sm">Kali Digunakan</p>
                    <?php if ($voucher['usage_limit']): ?>
                    <p class="text-xs text-gray-500 mt-1">dari <?= $voucher['usage_limit'] ?> kuota</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Usage Limits -->
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                <h3 class="font-bold mb-4"><i class="fas fa-users mr-2 text-accent"></i>Batas Penggunaan</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Total Penggunaan</label>
                        <input type="number" name="usage_limit" value="<?= old('usage_limit', $voucher['usage_limit']) ?>" min="1"
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none" placeholder="Kosongkan = unlimited">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Per User</label>
                        <input type="number" name="per_user_limit" value="<?= old('per_user_limit', $voucher['per_user_limit']) ?>" min="1"
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Validity Period -->
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                <h3 class="font-bold mb-4"><i class="fas fa-calendar mr-2 text-accent"></i>Masa Berlaku</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Mulai</label>
                        <input type="datetime-local" name="start_date" value="<?= old('start_date', $voucher['start_date'] ? date('Y-m-d\TH:i', strtotime($voucher['start_date'])) : '') ?>"
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Berakhir</label>
                        <input type="datetime-local" name="end_date" value="<?= old('end_date', $voucher['end_date'] ? date('Y-m-d\TH:i', strtotime($voucher['end_date'])) : '') ?>"
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                <h3 class="font-bold mb-4"><i class="fas fa-toggle-on mr-2 text-accent"></i>Status</h3>
                <select name="status" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none">
                    <option value="active" <?= $voucher['status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
                    <option value="inactive" <?= $voucher['status'] === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                <i class="fas fa-save mr-2"></i> Update Voucher
            </button>
        </div>
    </div>
</form>

<script>
const layananData = <?= json_encode($layananList) ?>;
const selectedProducts = <?= json_encode($selectedProducts) ?>;
const subcategoryMapping = {
    'advokasi': [
        {value: 'artikel', label: 'Artikel'},
        {value: 'webinar', label: 'Webinar'},
        {value: 'workshop', label: 'Workshop'},
        {value: 'pendampingan', label: 'Pendampingan CWPA'},
        {value: 'profirm', label: 'Profirm'},
        {value: 'Artikel', label: 'Artikel (Course)'},
        {value: 'Webinar', label: 'Webinar (Course)'},
        {value: 'Workshop', label: 'Workshop (Course)'},
        {value: 'Pendampingan CWPA', label: 'Pendampingan CWPA (Course)'},
        {value: 'Profirm', label: 'Profirm (Course)'},
    ],
    'expert-advisor': [
        {value: 'ea', label: 'Expert Advisor (EA)'},
        {value: 'AIWE', label: 'AIWE (Course)'},
        {value: 'BIDBOX', label: 'BIDBOX (Course)'},
    ],
    'almai-ultimate': [
        {value: 'ultimate', label: 'Ultimate'},
        {value: 'toolkit', label: 'Almai Toolkits'},
        {value: 'private_konsultan', label: 'Private Konsultan'},
        {value: 'vip_member', label: 'VIP Member'},
        {value: 'Almai Toolkits', label: 'Almai Toolkits (Course)'},
        {value: 'Private Konsultan', label: 'Private Konsultan (Course)'},
        {value: 'VIP Member', label: 'VIP Member (Course)'},
    ]
};

// Track selected items: key is "type:id", value is item object
let selectedSet = new Map();

// Pre-populate selectedSet from existing products
// Priority order to handle ID collisions: event > artikel > tool > subscription
const categoryOrder = ['advokasi', 'expert-advisor', 'almai-ultimate'];
const typeOrder = ['webinar', 'workshop', 'artikel', 'ea', 'toolkit', 'pendampingan', 'profirm', 'vip_member', 'private_konsultan'];

selectedProducts.forEach(selectedId => {
    const numericId = parseInt(selectedId);
    let found = false;
    
    // Search in priority order
    for (const category of categoryOrder) {
        if (found) break;
        const catItems = layananData[category] || [];
        
        for (const item of catItems) {
            if (parseInt(item.id) === numericId) {
                const compositeKey = `${item.type}:${item.id}`;
                selectedSet.set(compositeKey, item);
                found = true;
                break;
            }
        }
    }
});

console.log('Pre-populated items:', Array.from(selectedSet.keys()));

function toggleMaxDiscount() {
    const type = document.getElementById('discountType').value;
    const field = document.getElementById('maxDiscountField');
    field.style.display = type === 'percentage' ? 'block' : 'none';
}

function toggleProductList() {
    const type = document.getElementById('productType').value;
    const selection = document.getElementById('layananSelection');
    
    if (type === 'layanan') {
        selection.classList.remove('hidden');
    } else {
        selection.classList.add('hidden');
    }
}

function updateSubcategories() {
    const cat = document.getElementById('selectCategory').value;
    const subSelect = document.getElementById('selectSubcategory');
    const listBody = document.getElementById('layananListBody');
    
    subSelect.innerHTML = '<option value="">Pilih Sub Kategori</option>';
    listBody.innerHTML = '<p class="text-gray-500 text-sm italic text-center py-4">Pilih kategori & subkategori terlebih dahulu</p>';
    
    if (cat && subcategoryMapping[cat]) {
        subcategoryMapping[cat].forEach(sub => {
            const opt = document.createElement('option');
            opt.value = sub.value;
            opt.textContent = sub.label;
            subSelect.appendChild(opt);
        });
    }
}

function filterLayananItems() {
    const cat = document.getElementById('selectCategory').value;
    const sub = document.getElementById('selectSubcategory').value;
    const listBody = document.getElementById('layananListBody');
    
    if (!cat || !sub) return;
    
    const items = (layananData[cat] || layananData[cat === 'almai-ultimate' ? 'ultimate' : '']) ? (layananData[cat] || layananData['ultimate']).filter(i => i.subcategory === sub) : [];
    
    if (items.length === 0) {
        listBody.innerHTML = '<p class="text-gray-500 text-sm italic text-center py-4">Tidak ada item dalam subkategori ini</p>';
        return;
    }
    
    listBody.innerHTML = '';
    items.forEach(item => {
        const compositeKey = `${item.type}:${item.id}`;
        const isChecked = selectedSet.has(compositeKey);
        const label = document.createElement('label');
        label.className = 'flex items-center gap-3 p-2 hover:bg-white/5 rounded-lg cursor-pointer transition';
        const priceFormatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(item.price);
        
        label.innerHTML = `
            <input type="checkbox" onchange='toggleItem(${JSON.stringify(item)})' value="${item.id}" ${isChecked ? 'checked' : ''} class="w-4 h-4 accent-accent">
            <div class="flex flex-col">
                <span class="text-sm font-medium">${item.name}</span>
                <span class="text-[10px] text-gray-400 uppercase tracking-widest">${item.type}</span>
            </div>
            <span class="text-xs text-gray-500 ml-auto">${priceFormatted}</span>
        `;
        listBody.appendChild(label);
    });
}

function toggleItem(item) {
    const compositeKey = `${item.type}:${item.id}`;
    if (selectedSet.has(compositeKey)) {
        selectedSet.delete(compositeKey);
    } else {
        selectedSet.set(compositeKey, item);
    }
    updateBasket();
}

function removeItem(compositeKey) {
    selectedSet.delete(compositeKey);
    updateBasket();
    // Re-filter to sync checkboxes if currently viewing that subcategory
    filterLayananItems();
}

function updateBasket() {
    const basket = document.getElementById('selectedBasket');
    const hidden = document.getElementById('hiddenInputs');
    
    if (selectedSet.size === 0) {
        basket.innerHTML = '<p class="text-gray-500 text-xs italic">Belum ada item dipilih. Jika kosong, voucher berlaku untuk SEMUA item di subkategori/kategori terpilih.</p>';
        hidden.innerHTML = '';
        return;
    }
    
    basket.innerHTML = '';
    hidden.innerHTML = '';
    
    selectedSet.forEach((item, compositeKey) => {
        // UI Item
        const div = document.createElement('div');
        div.className = 'flex items-center justify-between bg-white/5 p-2 px-3 rounded-lg border border-white/10';
        div.innerHTML = `
            <div class="flex flex-col">
                <span class="text-xs font-bold text-gray-300">${item.name}</span>
                <span class="text-[9px] text-gray-500 uppercase tracking-tighter">${item.subcategory}</span>
            </div>
            <button type="button" onclick="removeItem('${compositeKey}')" class="text-red-400 hover:text-red-300 p-1 text-sm">
                <i class="fas fa-times"></i>
            </button>
        `;
        basket.appendChild(div);
        
        // Hidden Input - only submit the numeric ID
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'product_ids[]';
        input.value = item.id;
        hidden.appendChild(input);
    });
}

// Initialize
toggleMaxDiscount();
toggleProductList(); // Show/hide product list based on current selection
updateBasket();
</script>
<?= $this->endSection() ?>
