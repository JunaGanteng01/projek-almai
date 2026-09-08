/**
 * ALMAI REGISTRATION SYSTEM v2.0 - FRONTEND ENGINE
 * 
 * Features:
 * - WPA / Affiliator modal selector
 * - Form submit AJAX token initiation
 * - WA deep-link launching
 * - Auto polling engine (2s interval)
 * - Auto login & redirect on VERIFIED status
 */

document.addEventListener('DOMContentLoaded', function () {
    const btnSelectAffiliate = document.getElementById('btnSelectAffiliate');
    const modalAffiliateSelector = document.getElementById('modalAffiliateSelector');
    const closeModalAffiliate = document.getElementById('closeModalAffiliate');
    const hiddenAffiliateCode = document.getElementById('hiddenAffiliateCode');
    const displayAffiliateName = document.getElementById('displayAffiliateName');
    
    const formRegisterV2 = document.getElementById('formRegisterV2');
    const modalWaWaiting = document.getElementById('modalWaWaiting');
    const btnLaunchWa = document.getElementById('btnLaunchWa');
    const regErrorBox = document.getElementById('reg_error_box');
    const btnSubmitRegV2 = document.getElementById('btnSubmitRegV2');

    let pollingInterval = null;

    // 1. Open WPA Modal
    if (btnSelectAffiliate && modalAffiliateSelector) {
        btnSelectAffiliate.addEventListener('click', function () {
            modalAffiliateSelector.classList.remove('hidden');
        });
    }

    // 2. Close WPA Modal
    if (closeModalAffiliate && modalAffiliateSelector) {
        closeModalAffiliate.addEventListener('click', function () {
            modalAffiliateSelector.classList.add('hidden');
        });
    }

    // 3. Choose WPA item from Modal
    document.querySelectorAll('.btn-choose-wpa').forEach(function (button) {
        button.addEventListener('click', function () {
            const code = this.getAttribute('data-code');
            const name = this.getAttribute('data-name');

            if (hiddenAffiliateCode) hiddenAffiliateCode.value = code;
            if (displayAffiliateName) displayAffiliateName.textContent = name.toUpperCase() + ' (' + code.toUpperCase() + ')';

            if (modalAffiliateSelector) modalAffiliateSelector.classList.add('hidden');
        });
    });

    // 4. Submit Registration Form v2.0
    if (formRegisterV2) {
        formRegisterV2.addEventListener('submit', function (e) {
            e.preventDefault();

            if (regErrorBox) {
                regErrorBox.classList.add('hidden');
                regErrorBox.textContent = '';
            }

            const name = document.getElementById('reg_name').value.trim();
            const email = document.getElementById('reg_email').value.trim();
            const password = document.getElementById('reg_password').value;
            const confirmPassword = document.getElementById('reg_confirm_password').value;
            const affiliatorCode = hiddenAffiliateCode ? hiddenAffiliateCode.value.trim() : '';

            if (password !== confirmPassword) {
                showError('Konfirmasi kata sandi tidak cocok.');
                return;
            }

            if (!affiliatorCode) {
                showError('Wajib memilih WPA / Affiliator pendamping.');
                return;
            }

            btnSubmitRegV2.disabled = true;
            btnSubmitRegV2.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

            fetch('/auth/register-v2/initiate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    password: password,
                    affiliator_code: affiliatorCode
                })
            })
            .then(res => res.json())
            .then(data => {
                btnSubmitRegV2.disabled = false;
                btnSubmitRegV2.innerHTML = 'Daftar & Lanjutkan via WhatsApp ➔';

                if (data.success) {
                    // Set WA launch link
                    if (btnLaunchWa) btnLaunchWa.href = data.wa_url;

                    // Open WA link automatically in new tab
                    window.open(data.wa_url, '_blank');

                    // Show Waiting Modal
                    if (modalWaWaiting) modalWaWaiting.classList.remove('hidden');

                    // Start Polling Engine
                    startPolling(data.token);
                } else {
                    showError(data.message || 'Gagal memulai pendaftaran.');
                }
            })
            .catch(err => {
                btnSubmitRegV2.disabled = false;
                btnSubmitRegV2.innerHTML = 'Daftar & Lanjutkan via WhatsApp ➔';
                showError('Terjadi kesalahan jaringan/server. Silakan coba lagi.');
            });
        });
    }

    // Helper: Show Error Box
    function showError(msg) {
        if (regErrorBox) {
            regErrorBox.textContent = msg;
            regErrorBox.classList.remove('hidden');
        } else {
            alert(msg);
        }
    }

    // 5. Polling Engine (Checks status every 2 seconds)
    function startPolling(token) {
        if (pollingInterval) clearInterval(pollingInterval);

        pollingInterval = setInterval(function () {
            fetch('/auth/register-v2/check-status?token=' + encodeURIComponent(token))
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'VERIFIED') {
                        clearInterval(pollingInterval);
                        window.location.href = data.redirect || '/welcome';
                    } else if (data.status === 'EXPIRED') {
                        clearInterval(pollingInterval);
                        if (modalWaWaiting) modalWaWaiting.classList.add('hidden');
                        showError('Waktu verifikasi WhatsApp telah habis (30 menit). Silakan daftar ulang.');
                    }
                })
                .catch(err => {
                    console.log('Polling check error:', err);
                });
        }, 2000);
    }
});
