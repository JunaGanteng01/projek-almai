<script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
<style>
    :root {
        --ck-color-base-background: #0a0a0a;
        --ck-color-base-border: #333;
        --ck-color-toolbar-background: #111;
        --ck-color-text: #e5e7eb;
        --ck-color-base-foreground: #111;
        --ck-color-button-default-background: transparent;
        --ck-color-button-default-hover-background: #333;
        --ck-color-button-on-background: #333;
        --ck-color-button-on-hover-background: #444;
        --ck-color-list-background: #111;
        --ck-color-list-button-hover-background: #333;
        --ck-color-dropdown-panel-background: #111;
        --ck-color-dropdown-panel-border: #333;
        --ck-color-input-background: #111;
        --ck-color-input-border: #333;
        --ck-color-panel-background: #111;
        --ck-color-panel-border: #333;
    }
    .ck-editor__editable_inline { min-height: 200px; }
    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) { border-color: #333; }
    input[type="time"]::-webkit-calendar-picker-indicator,
    input[type="date"]::-webkit-calendar-picker-indicator,
    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
        cursor: pointer;
    }
</style>

<script>
    // CKEditor Initialization
    const commonConfig = {
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo', 'imageUpload'],
    };

    function UploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return {
                upload: () => loader.file.then(file => new Promise((resolve, reject) => {
                    const data = new FormData();
                    data.append('upload', file);
                    data.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
                    fetch('<?= base_url('superadmin/layanan/upload-image') ?>', { method: 'POST', body: data })
                        .then(res => res.json())
                        .then(res => res.error ? reject(res.error.message) : resolve({ default: res.url }))
                        .catch(reject);
                }))
            };
        };
    }

    if (typeof ClassicEditor !== 'undefined') {
        if (document.querySelector('#editor_description')) {
            ClassicEditor.create(document.querySelector('#editor_description'), { ...commonConfig, extraPlugins: [UploadAdapterPlugin] }).catch(console.error);
        }
        if (document.querySelector('#editor_content')) {
            ClassicEditor.create(document.querySelector('#editor_content'), { ...commonConfig, extraPlugins: [UploadAdapterPlugin] }).catch(console.error);
        }
    } else {
        console.warn('ClassicEditor is not loaded. Skipping initialization.');
    }

    // Toggle Recurring
    if (document.getElementById('isRecurring')) {
        document.getElementById('isRecurring').addEventListener('change', function() {
            document.getElementById('recurringOptions').classList.toggle('hidden', !this.checked);
        });
    }

    // Toggle License
    if (document.getElementById('isLicenseProduct')) {
        document.getElementById('isLicenseProduct').addEventListener('change', function() {
            document.getElementById('licenseFields').classList.toggle('hidden', !this.checked);
        });
    }

    // Packages Logic
    let packageCounter = 0;
    
    window.addPackageRow = function() {
        const container = document.getElementById('packagesContainer');
        const div = document.createElement('div');
        div.className = 'grid grid-cols-12 gap-3 items-end bg-black/30 p-3 rounded-lg border border-white/5 relative';
        div.innerHTML = `
            <div class="col-span-5">
                <label class="block text-xs text-gray-500 mb-1">Nama Paket</label>
                <input type="text" name="packages[${packageCounter}][name]" required placeholder="Misal: Paket Basic" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-3 py-2 text-sm focus:border-accent outline-none">
            </div>
            <div class="col-span-6">
                <label class="block text-xs text-gray-500 mb-1">Harga (IDR)</label>
                <input type="number" name="packages[${packageCounter}][price]" required placeholder="500000" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-3 py-2 text-sm focus:border-accent outline-none">
            </div>
            <div class="col-span-1 text-right">
                <button type="button" onclick="this.parentElement.parentElement.remove(); if(typeof updateReferralPreview === 'function') updateReferralPreview();" class="w-full h-[38px] flex items-center justify-center bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500/20 transition">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
        packageCounter++;
        
        const priceInput = div.querySelector('input[type="number"]');
        priceInput.addEventListener('input', function() {
            if (typeof updateReferralPreview === 'function') updateReferralPreview();
        });
    };

    function togglePackages() {
        const isChecked = document.getElementById('hasPackages')?.checked;
        const singleField = document.getElementById('singlePriceField');
        const multiField = document.getElementById('multiPackageField');
        const priceInput = document.getElementById('priceInput');

        if (singleField) singleField.classList.toggle('hidden', isChecked);
        if (multiField) multiField.classList.toggle('hidden', !isChecked);

        if (isChecked && document.getElementById('packagesContainer') && document.getElementById('packagesContainer').children.length === 0) {
            if (typeof window.addPackageRow === 'function') addPackageRow();
        }
        if (typeof updateReferralPreview === 'function') updateReferralPreview();
    };

    if (document.getElementById('hasPackages')) {
        document.getElementById('hasPackages').addEventListener('change', togglePackages);
        togglePackages();
    }

    // Auto calculate Poin Price (IDR / 100)
    const priceInput = document.querySelector('input[name="price"]');
    const poinInput = document.getElementById('poinPrice');
    if (priceInput && poinInput) {
        priceInput.addEventListener('input', function() {
            poinInput.value = Math.floor(this.value / 100);
        });
    }

    // Resources
    window.addResourceRow = function() {
        const container = document.getElementById('newResourcesContainer');
        const div = document.createElement('div');
        div.className = 'p-3 bg-white/5 border border-white/10 rounded-lg relative';
        div.innerHTML = `
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-gray-500 hover:text-red-400"><i class="fas fa-times"></i></button>
            <input type="text" name="resource_titles[]" placeholder="Judul Materi" class="w-full bg-black/50 border border-white/10 rounded px-3 py-2 text-sm mb-2">
            <input type="file" name="resources[]" required class="text-xs">
        `;
        container.appendChild(div);
    };

    // YouTube rows
    window.addYtRow = function() {
        const container = document.getElementById('ytContainer');
        const div = document.createElement('div');
        div.className = 'p-3 bg-white/5 border border-white/10 rounded-lg space-y-2 relative';
        div.innerHTML = `
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-gray-500 hover:text-red-400"><i class="fas fa-times"></i></button>
            <input type="text" placeholder="Judul Video" class="yt-title w-full bg-black/50 border border-white/10 rounded px-3 py-2 text-sm">
            <input type="url" placeholder="URL YouTube" class="yt-url w-full bg-black/50 border border-white/10 rounded px-3 py-2 text-sm">
        `;
        container.appendChild(div);
    };

    // Collect YT data before submit
    const wizardForm = document.getElementById('layananForm');
    if (wizardForm) {
        wizardForm.addEventListener('submit', function() {
            const data = [];
            document.querySelectorAll('#ytContainer > div').forEach(div => {
                const title = div.querySelector('.yt-title')?.value;
                const url = div.querySelector('.yt-url')?.value;
                if (title || url) data.push({ title, url });
            });
            const ytHidden = document.getElementById('ytHidden');
            if (ytHidden) ytHidden.value = JSON.stringify(data);
        });
    }

    // Toggle Referral Section
    window.toggleReferralSection = function(enabled) {
        const section  = document.getElementById('referralSection');
        const inputOn  = document.getElementById('referral_dist_pct');
        const inputOff = document.getElementById('referral_dist_pct_disabled');

        if (enabled) {
            section.classList.remove('hidden');
            if (inputOn)  inputOn.disabled  = false;
            if (inputOff) inputOff.disabled = true;
            updateReferralPreview();
        } else {
            section.classList.add('hidden');
            if (inputOn)  inputOn.disabled  = true;
            if (inputOff) inputOff.disabled = false;
        }
    };

    // ── Referral Preview ──────────────────────────────────────────────
    const DEPTH = 8;
    const POIN_RATE = 1000; // 1 poin = Rp 1.000

    function getLevelLabel(i) {
        if (i === 1) return 'Referrer (Level 1)';
        if (i === 2) return 'Upline Level 2';
        return `Upline Level ${i}`;
    }

    function getAllPrices() {
        const hasPackages = document.getElementById('hasPackages')?.checked;
        const globalPct   = parseFloat(document.getElementById('referral_dist_pct')?.value || 25);
        if (hasPackages) {
            const inputs = document.querySelectorAll('#packagesContainer input[name*="[price]"]');
            const names  = document.querySelectorAll('#packagesContainer input[name*="[name]"]');
            const prices = [];
            inputs.forEach((inp, idx) => {
                const val = parseFloat(inp.value || 0);
                if (val > 0) {
                    prices.push({ label: names[idx]?.value || `Paket ${idx + 1}`, price: val, pct: globalPct });
                }
            });
            return prices.length ? prices : null;
        }
        const single = parseFloat(document.querySelector('input[name="price"]')?.value || 0);
        return single > 0 ? [{ label: 'Harga Tunggal', price: single, pct: globalPct }] : null;
    }

    function buildLevels(pool, pct) {
        const DECAY = 50; // tiap level dapat 50% dari sisa pool (fixed)
        const rows = [];
        let remaining = pool;
        for (let i = 1; i <= DEPTH; i++) {
            if (remaining < 1) break;
            const share = Math.floor(remaining * DECAY / 100);
            if (share < 1) break;
            const poin = Math.floor(share / POIN_RATE);
            const barW = pool > 0 ? Math.round((share / pool) * 100) : 0;
            const pct_of_price = pool > 0 ? (share / pool * pct).toFixed(2) : '0.00';
            rows.push({ label: getLevelLabel(i), share, poin, barW, pct_of_price });
            remaining -= share;
        }
        return { rows, remaining };
    }

    function renderPreviewBlock(pkgLabel, pool, pct, colors) {
        const { rows, remaining } = buildLevels(pool, pct);
        const poin_pool = Math.floor(pool / POIN_RATE);

        const header = pkgLabel
            ? `<div class="flex items-center justify-between mb-2 pb-2 border-b border-white/5">
                <span class="font-bold text-white text-[11px] uppercase tracking-widest">${pkgLabel}</span>
                <span class="text-[10px] text-gray-500">Pool: <span class="text-accent font-bold">Rp ${pool.toLocaleString('id-ID')}</span> · <span class="text-yellow-400 font-bold">${poin_pool.toLocaleString('id-ID')} poin</span></span>
               </div>`
            : `<div class="flex justify-between mb-2 pb-2 border-b border-white/5 text-[10px] text-gray-500">
                <span>Pool komisi</span>
                <span>Rp ${pool.toLocaleString('id-ID')} · <span class="text-yellow-400">${poin_pool.toLocaleString('id-ID')} poin</span></span>
               </div>`;

        if (rows.length === 0) {
            return `${header}<p class="text-gray-600 text-center py-2 text-[11px]">Masukkan harga untuk melihat preview</p>`;
        }

        const levelRows = rows.map((r, i) => `
            <div class="flex items-center gap-2 py-1">
                <div class="w-32 shrink-0 text-gray-400 text-[11px] truncate" title="${r.label}">${r.label}</div>
                <div class="w-14 shrink-0 text-gray-600 text-[10px] text-right">${r.pct_of_price}%</div>
                <div class="flex-1 bg-white/5 rounded-full h-1.5 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-300" style="width:${r.barW}%;background:${colors[i] || '#052e16'}"></div>
                </div>
                <div class="text-right shrink-0 leading-tight min-w-[90px]">
                    <div class="font-bold text-[11px]" style="color:${colors[i] || '#052e16'}">Rp ${r.share.toLocaleString('id-ID')}</div>
                    <div class="text-yellow-400 text-[10px]">🪙 ${r.poin.toLocaleString('id-ID')} poin</div>
                </div>
            </div>
        `).join('');

        const footer = remaining > 0
            ? `<div class="mt-2 pt-2 border-t border-white/5 flex justify-between text-[10px] text-gray-500">
                <span>Sisa tidak terdistribusi</span>
                <span class="text-yellow-500 font-bold">Rp ${remaining.toLocaleString('id-ID')}</span>
               </div>`
            : `<div class="mt-2 pt-2 border-t border-white/5 text-[10px] text-accent text-right">Pool habis terdistribusi ✓</div>`;

        return `${header}${levelRows}${footer}`;
    }

    window.updateReferralPreview = function() {
        const prices = getAllPrices();
        const colors = ['#33E818','#22c55e','#16a34a','#15803d','#166534','#14532d','#052e16','#022c22'];

        const container = document.getElementById('referralPreviewTable');
        if (!container) return;

        if (!prices) {
            container.innerHTML = '<p class="text-gray-600 text-center py-2 text-[11px]">Masukkan harga layanan untuk melihat preview</p>';
            document.getElementById('calc_referral_user_cash').value = 0;
            document.getElementById('calc_referral_user_poin').value = 0;
            return;
        }

        const isMulti   = prices.length > 1;
        const firstPool = Math.floor(prices[0].price * prices[0].pct / 100);

        // Update hidden fields using first/only price
        document.getElementById('calc_referral_user_cash').value = firstPool;
        document.getElementById('calc_referral_user_poin').value = Math.floor(firstPool / POIN_RATE);

        if (isMulti) {
            container.innerHTML = prices.map((pkg, idx) => {
                const pool  = Math.floor(pkg.price * pkg.pct / 100);
                const block = renderPreviewBlock(pkg.label, pool, pkg.pct, colors);
                return `<div class="${idx > 0 ? 'mt-4 pt-4 border-t border-white/5' : ''}">${block}</div>`;
            }).join('');
        } else {
            container.innerHTML = renderPreviewBlock(null, firstPool, prices[0].pct, colors);
        }
    };

    // Trigger preview on price / package name / referral_distribution_percentage change
    document.addEventListener('input', function(e) {
        if (e.target.name === 'price' || e.target.name?.includes('[price]') || e.target.name?.includes('[name]') || e.target.name?.includes('[referral_distribution_percentage]')) {
            updateReferralPreview();
        }
    });

    // Init preview on load
    updateReferralPreview();
</script>
