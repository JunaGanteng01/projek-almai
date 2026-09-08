<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>

<div style="margin-bottom: 20px;">
    <a href="<?= base_url('user/dashboard/rwa') ?>" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #fff; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Kembali ke Dashboard
    </a>
</div>

<style>
    .hero-card { background: linear-gradient(135deg, rgba(124,255,0,0.10), rgba(124,255,0,0.02)); border: 1px solid rgba(124,255,0,0.18); border-radius: 18px; padding: 22px; margin-bottom: 18px; }
    .hero-top { display: flex; justify-content: space-between; gap: 16px; align-items: flex-start; margin-bottom: 16px; }
    .hero-title { font-size: 22px; font-weight: 800; margin-bottom: 6px; color: #fff;}
    .hero-sub { color: #9ca3af; font-size: 13px; line-height: 1.6; max-width: 760px; }
    .status-pill { padding: 7px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; background: rgba(124,255,0,0.12); color: #33e818; border: 1px solid rgba(124,255,0,0.18); white-space: nowrap; }
    .metric-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 18px; }
    .metric-card, .panel-card { background: #111; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; }
    .metric-card { padding: 16px; }
    .metric-label { font-size: 11px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .metric-value { font-size: 20px; font-weight: 800; color: #fff; }
    .metric-note { font-size: 12px; color: #9ca3af; margin-top: 4px; }
    .panel-card { padding: 18px; margin-bottom: 18px; }
    .panel-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 14px; }
    .panel-title { font-size: 15px; font-weight: 700; color: #fff; }
    .panel-sub { font-size: 12px; color: #9ca3af; }
    .btn-row { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn { padding: 8px 16px; font-size: 13px; font-weight: 600; border-radius: 8px; cursor: pointer; border: none; }
    .btn-outline { background: transparent; border: 1px solid rgba(255,255,255,0.2); color: #fff; }
    .btn-primary { background: #33e818; color: #000; }
    .exchange-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
    .exchange-card { background: #171a1d; border: 1px solid rgba(255,255,255,0.1); border-radius: 14px; padding: 16px; color: #fff; }
    .exchange-header { display: flex; justify-content: space-between; gap: 12px; align-items: center; margin-bottom: 14px; }
    .exchange-title { display: flex; align-items: center; gap: 10px; font-size: 15px; font-weight: 700; }
    .exchange-title img { width: 24px; height: 24px; border-radius: 4px; object-fit: cover; background: #fff; }
    .exchange-status { font-size: 11px; padding: 4px 8px; border-radius: 6px; font-weight: 600; background: rgba(124,255,0,0.1); color: #33e818; border: 1px solid rgba(124,255,0,0.2); }
    .exchange-details { display: grid; gap: 8px; }
    .detail { padding: 10px 12px; border-radius: 10px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); }
    .detail .label { font-size: 11px; color: #9ca3af; margin-bottom: 4px; }
    .detail .value { font-size: 13px; font-weight: 600; word-break: break-all; }
    .exchange-actions { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 12px; }
    .activity-list { display: grid; gap: 10px; }
    .activity-item { padding: 12px 14px; border-radius: 12px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; gap: 12px; align-items: center; color: #fff;}
    .activity-item .left { min-width: 0; }
    .activity-item .title { font-size: 13px; font-weight: 600; }
    .activity-item .desc { font-size: 11px; color: #9ca3af; margin-top: 3px; }
    .badge-ok { background: rgba(92,255,0,0.12); color: #33e818; }
    .badge-warn { background: rgba(255,193,7,0.12); color: #ffc107; }
    .modal-backdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 5000; padding: 16px; overflow-y: auto; }
    .modal-backdrop.show { display: flex; align-items: center; justify-content: center; }
    .modal-card { width: 100%; max-width: 760px; background: #111; border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; box-shadow: 0 24px 80px rgba(0,0,0,0.55); overflow: hidden; color: #fff; }
    .modal-head { display: flex; justify-content: space-between; gap: 16px; align-items: center; padding: 18px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .modal-title { font-size: 16px; font-weight: 800; }
    .modal-body { padding: 20px; }
    .modal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .modal-field { background: #171a1d; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px 14px; }
    .modal-field .label { font-size: 11px; color: #9ca3af; margin-bottom: 6px; }
    .modal-field input, .modal-field select { width: 100%; background: transparent; border: none; outline: none; color: #fff; font-size: 13px; font-weight: 600; padding: 0; }
    .modal-field select option { background: #171a1d; color: #fff; }
    .modal-preview { display: flex; align-items: center; gap: 12px; padding: 12px 14px; margin-bottom: 14px; border-radius: 12px; border: 1px solid rgba(124,255,0,0.15); background: rgba(124,255,0,0.04); }
    .modal-preview img { width: 28px; height: 28px; border-radius: 6px; object-fit: cover; background: #fff; }
    .modal-preview .name { font-size: 14px; font-weight: 700; }
    .modal-preview .desc { font-size: 11px; color: #9ca3af; }
    .modal-foot { padding: 0 20px 20px; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
    .password-wrapper { position: relative; display: flex; align-items: center; }
    .password-wrapper input { width: 100%; padding-right: 40px; }
    .toggle-password { position: absolute; right: 10px; cursor: pointer; color: #9ca3af; padding: 4px; border-radius: 4px; display: flex; align-items: center; justify-content: center; }
    .toggle-password:hover { background: rgba(255,255,255,0.1); color: #fff; }
    .animate-spin { animation: spin 1s linear infinite; display: inline-block; vertical-align: text-bottom; margin-left: 6px; }
    @keyframes spin { 100% { transform: rotate(360deg); } }
    @media (max-width: 900px) {
        .metric-grid, .exchange-grid, .modal-grid { grid-template-columns: 1fr; }
        .hero-top { flex-direction: column; }
    }
</style>

<div class="panel-card mt-4">
    <div class="panel-head">
        <div>
            <div class="panel-title">Kunci Exchange yang Terhubung</div>
            <div class="panel-sub">Ikhtisar akun exchange yang tersimpan di dalam database.</div>
        </div>
        <div class="btn-row">
            <button type="button" class="btn btn-outline" onclick="openExchangeModal('')">Tambah Exchange</button>
        </div>
    </div>

    <div class="exchange-grid">
        <?php if (!empty($exchangeKeys)): ?>
            <?php foreach ($exchangeKeys as $saved): ?>
                <?php 
                    $activeExchangeInfo = null;
                    foreach ($supportedExchanges as $se) {
                        if ($se['slug'] === $saved->exchange) {
                            $activeExchangeInfo = $se;
                            break;
                        }
                    }
                ?>
                <?php if ($activeExchangeInfo): ?>
                <div class="exchange-card">
                    <div class="exchange-header">
                        <div class="exchange-title">
                            <img src="<?= base_url(esc($activeExchangeInfo['logo'])) ?>" alt="<?= esc($activeExchangeInfo['name']) ?>">
                            <?= esc($activeExchangeInfo['name']) ?>
                        </div>
                        <div class="exchange-status"><?= $saved->is_active ? 'Aktif' : 'Tidak Aktif' ?></div>
                    </div>

                    <div class="exchange-details">
                        <div class="detail"><div class="label">Nama Akun</div><div class="value"><?= esc($saved->account_name ?? 'Belum diatur') ?></div></div>
                        <div class="detail"><div class="label">Kunci API</div><div class="value">••••••••••••••••</div></div>
                        <div class="detail"><div class="label">Rahasia API</div><div class="value">••••••••••••••••</div></div>
                        <div class="detail"><div class="label">Kata Sandi</div><div class="value"><?= $saved->passphrase ? '••••••••••••••••' : 'Opsional' ?></div></div>
                    </div>

                    <div class="exchange-actions">
                        <button type="button" class="btn btn-outline" style="border-color: #33e818; color: #33e818;" onclick='openEditExchangeModal(<?= json_encode([
                            "id" => $saved->id,
                            "exchange" => $saved->exchange,
                            "account_name" => $saved->account_name,
                            "passphrase" => $saved->passphrase,
                            "is_active" => $saved->is_active
                        ]) ?>)'>Edit</button>
                        <form method="POST" action="<?= base_url('user/dashboard/rwa/exchange-api/delete') ?>" onsubmit="return confirm('Anda yakin ingin menghapus Kunci API ini?');" style="margin: 0;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $saved->id ?>">
                            <button type="submit" class="btn btn-outline" style="border-color: #ef4444; color: #ef4444;">Hapus</button>
                        </form>
                    </div>

                    <div style="margin-top:12px;font-size:12px;color:#9ca3af;line-height:1.6;">
                        Kredensial disimpan dengan aman. Kunci asli tidak dapat dipulihkan dari penyimpanan.
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="exchange-card" style="text-align: center; padding: 40px 20px; grid-column: 1 / -1; max-width: 500px; margin: 0 auto;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="2" style="margin-bottom:16px;">
                    <rect x="3" y="11" width="18" height="10" rx="2"></rect>
                    <circle cx="12" cy="5" r="2"></circle>
                    <path d="M12 7v4"></path>
                    <line x1="8" y1="16" x2="8.01" y2="16"></line>
                    <line x1="16" y1="16" x2="16.01" y2="16"></line>
                </svg>
                <h4 style="margin-bottom: 8px;">Belum Ada Exchange yang Diatur</h4>
                <p style="font-size:13px; color:#9ca3af; margin-bottom: 20px;">Anda dapat menghubungkan beberapa exchange secara bersamaan. Atur exchange pilihan Anda untuk mulai trading.</p>
                <button type="button" class="btn btn-primary" onclick="openExchangeModal('')">Atur Exchange</button>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="panel-card">
    <div class="panel-head">
        <div>
            <div class="panel-title">Aktivitas Terkini</div>
            <div class="panel-sub">Ringkasan aktivitas gateway terbaru.</div>
        </div>
    </div>
    <div class="activity-list">
        <div class="activity-item"><div class="left"><div class="title">Sistem aktif</div><div class="desc">Jembatan koneksi API siap menerima perintah.</div></div><div class="status-pill badge-ok">OK</div></div>
    </div>
</div>

<div id="exchangeModal" class="modal-backdrop" onclick="handleBackdropClick(event)">
    <div class="modal-card" role="dialog" aria-modal="true">
        <div class="modal-head">
            <div>
                <div class="modal-title">Atur Exchange</div>
                <div class="panel-sub">Pilih exchange terlebih dahulu, kemudian masukkan akun dan kredensial.</div>
            </div>
            <button type="button" class="btn btn-outline" onclick="closeExchangeModal()">Tutup</button>
        </div>

        <form id="exchangeModalForm" method="POST" action="<?= base_url('user/dashboard/rwa/exchange-api/save') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="modal_id">
            <input type="hidden" name="exchange" id="modal_exchange">
            
            <div class="modal-body">
                <div style="background: rgba(0, 180, 255, 0.1); border: 1px solid rgba(0, 180, 255, 0.25); padding: 12px; border-radius: 8px; font-size: 12px; color: #00e5ff; line-height: 1.5; margin-bottom: 14px; display: flex; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0; margin-top: 2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <div>
                        <strong>IP Whitelist:</strong> Kalau mau pakai IP whitelist, copy IP ini: <strong style="user-select: all; background: rgba(255,255,255,0.1); padding: 2px 6px; border-radius: 4px; font-family: monospace; letter-spacing: 1px;">203.194.112.38</strong>
                    </div>
                </div>

                <div id="exchangeModalPreview" class="modal-preview" style="display:none;">
                    <img id="exchangeModalLogo" src="" alt="">
                    <div>
                        <div id="exchangeModalName" class="name"></div>
                        <div id="exchangeModalDesc" class="desc"></div>
                    </div>
                </div>

                <div class="modal-grid">
                    <div class="modal-field">
                        <div class="label">Exchange</div>
                        <select id="modal_exchange_select" onchange="syncExchangeSelection(this.value)">
                            <option value="">Pilih exchange</option>
                            <?php foreach($supportedExchanges as $exchange): ?>
                                <option value="<?= esc($exchange['slug']) ?>"><?= esc($exchange['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="modal-field">
                        <div class="label">Aktif untuk perutean bot</div>
                        <select name="is_active" id="modal_is_active">
                            <option value="1">Ya</option>
                            <option value="0">Tidak</option>
                        </select>
                    </div>
                    <div class="modal-field">
                        <div class="label">Nama Akun</div>
                        <input type="text" name="account_name" id="modal_account_name" placeholder="Nama akun utama">
                    </div>
                    <div class="modal-field">
                        <div class="label">Kunci API</div>
                        <div class="password-wrapper">
                            <input type="password" name="api_key" id="modal_api_key" placeholder="Tempelkan Kunci API">
                            <span class="toggle-password" onclick="toggleVisibility('modal_api_key', this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>
                        </div>
                    </div>
                    <div class="modal-field">
                        <div class="label">Rahasia API</div>
                        <div class="password-wrapper">
                            <input type="password" name="api_secret" id="modal_api_secret" placeholder="Tempelkan Rahasia API">
                            <span class="toggle-password" onclick="toggleVisibility('modal_api_secret', this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>
                        </div>
                    </div>
                    <div class="modal-field">
                        <div class="label">Kata Sandi (Passphrase)</div>
                        <input type="text" name="passphrase" id="modal_passphrase" placeholder="Opsional">
                    </div>
                </div>
            </div>

            <div class="modal-foot">
                <div id="modal_hint" style="font-size:12px;color:#9ca3af;">Pilih exchange untuk melanjutkan.</div>
                <div class="btn-row">
                    <button type="button" class="btn btn-outline" onclick="closeExchangeModal()">Batal Simpan</button>
                    <button type="button" id="btnSimpan" class="btn btn-primary" onclick="testAndSave()">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const exchangeData = <?= json_encode($supportedExchanges) ?>;
const exchangeLookup = exchangeData.reduce((acc, item) => { acc[item.slug] = item; return acc; }, {});

function openExchangeModal(exchangeSlug = '') {
    const modal = document.getElementById('exchangeModal');
    document.getElementById('exchangeModalForm').reset();
    document.getElementById('modal_id').value = '';
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    syncExchangeSelection(exchangeSlug);
}

function openEditExchangeModal(data) {
    const modal = document.getElementById('exchangeModal');
    document.getElementById('exchangeModalForm').reset();
    document.getElementById('modal_id').value = data.id;
    document.getElementById('modal_account_name').value = data.account_name || '';
    document.getElementById('modal_passphrase').value = data.passphrase || '';
    document.getElementById('modal_is_active').value = data.is_active;
    
    // Suggest that password fields only needed if changing
    document.getElementById('modal_api_key').placeholder = "Kosongkan jika tidak ingin diubah";
    document.getElementById('modal_api_secret').placeholder = "Kosongkan jika tidak ingin diubah";
    
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    syncExchangeSelection(data.exchange);
}

function closeExchangeModal() {
    const modal = document.getElementById('exchangeModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
}

function handleBackdropClick(event) {
    if (event.target.id === 'exchangeModal') {
        closeExchangeModal();
    }
}

function syncExchangeSelection(exchangeSlug) {
    const selected = exchangeLookup[exchangeSlug] || null;
    const select = document.getElementById('modal_exchange_select');
    const input = document.getElementById('modal_exchange');
    const preview = document.getElementById('exchangeModalPreview');
    const logo = document.getElementById('exchangeModalLogo');
    const name = document.getElementById('exchangeModalName');
    const desc = document.getElementById('exchangeModalDesc');
    const hint = document.getElementById('modal_hint');

    if (select && select.value !== exchangeSlug) {
        select.value = exchangeSlug;
    }
    input.value = exchangeSlug || '';

    if (!selected) {
        preview.style.display = 'none';
        hint.textContent = 'Pilih exchange untuk melanjutkan.';
        return;
    }

    preview.style.display = 'flex';
    logo.src = '<?= base_url() ?>' + selected.logo;
    name.textContent = selected.name;
    desc.textContent = 'Kredensial dienkripsi dan disimpan dengan aman.';
    hint.textContent = 'Siap untuk mengatur ' + selected.name + '.';
}

function toggleVisibility(inputId, span) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
        span.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
    } else {
        input.type = 'password';
        span.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
    }
}

async function testAndSave() {
    const hint = document.getElementById('modal_hint');
    const exchange = document.getElementById('modal_exchange').value;
    const apiKey = document.getElementById('modal_api_key').value;
    const apiSecret = document.getElementById('modal_api_secret').value;
    const id = document.getElementById('modal_id').value;
    const btnSimpan = document.getElementById('btnSimpan');

    // Jika sedang edit dan tidak mengubah key, langsung simpan tanpa tes ulang
    if (id && (!apiKey || !apiSecret)) {
        hint.style.color = '#fff';
        hint.textContent = 'Menyimpan perubahan...';
        btnSimpan.disabled = true;
        btnSimpan.innerHTML = 'Loading... <svg class="animate-spin" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>';
        document.getElementById('exchangeModalForm').submit();
        return;
    }

    hint.style.color = '#fff';
    hint.textContent = 'Menguji koneksi ke ' + exchange + '...';
    btnSimpan.disabled = true;
    btnSimpan.innerHTML = 'Loading... <svg class="animate-spin" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>';

    // Ambil CSRF token fresh dari hidden input (bukan dari PHP render time)
    // agar token tidak basi jika halaman dibiarkan lama
    const csrfInput = document.querySelector('#exchangeModalForm input[name="<?= csrf_token() ?>"]');
    const csrfToken = csrfInput ? csrfInput.value : '';

    const formData = new FormData();
    formData.append('exchange', exchange);
    formData.append('api_key', apiKey);
    formData.append('api_secret', apiSecret);
    formData.append('<?= csrf_token() ?>', csrfToken);

    try {
        const response = await fetch('<?= base_url('user/dashboard/rwa/exchange-api/test') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        // Refresh CSRF token dari response header jika ada
        const newToken = response.headers.get('X-CSRF-TOKEN');
        if (newToken && csrfInput) {
            csrfInput.value = newToken;
            // Update juga meta tag csrf-token jika ada
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            if (metaTag) metaTag.setAttribute('content', newToken);
        }
        
        const result = await response.json();
        if (result.status === 'success') {
            hint.style.color = '#33e818';
            hint.textContent = 'Koneksi berhasil! Menyimpan data...';
            document.getElementById('exchangeModalForm').submit();
        } else {
            hint.style.color = '#ef4444';
            hint.textContent = result.message || 'Gagal terhubung. Periksa kembali API Key/Secret.';
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = 'Simpan';
        }
    } catch (e) {
        hint.style.color = '#ef4444';
        hint.textContent = 'Terjadi kesalahan jaringan atau server saat menguji koneksi.';
        btnSimpan.disabled = false;
        btnSimpan.innerHTML = 'Simpan';
    }
}
</script>

<?= $this->endSection() ?>
