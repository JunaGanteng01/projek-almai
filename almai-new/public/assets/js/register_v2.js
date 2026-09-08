/**
 * ALMAI REGISTRATION SYSTEM v2.0 - FRONTEND ENGINE (SPRINT 2)
 * Handles: Referral Code Resolution, Initiate Registration AJAX, WA Deep-Link Launch, and Status Polling.
 */

document.addEventListener('DOMContentLoaded', function () {
    const regForm = document.getElementById('formRegisterV2');
    if (!regForm) return;

    const btnSelectAffiliate = document.getElementById('btnSelectAffiliate');
    const modalAffiliate = document.getElementById('modalAffiliateSelector');
    const closeModalAffiliate = document.getElementById('closeModalAffiliate');
    const hiddenAffiliateCode = document.getElementById('hiddenAffiliateCode');
    const displayAffiliateName = document.getElementById('displayAffiliateName');
    const waitingModal = document.getElementById('modalWaWaiting');
    const btnLaunchWa = document.getElementById('btnLaunchWa');

    let pollingInterval = null;
    let currentToken = null;

    // 1. WPA / Affiliator Modal Selector (if applicable)
    if (btnSelectAffiliate && modalAffiliate) {
        btnSelectAffiliate.addEventListener('click', function () {
            modalAffiliate.classList.remove('hidden');
        });
    }

    if (closeModalAffiliate && modalAffiliate) {
        closeModalAffiliate.addEventListener('click', function () {
            modalAffiliate.classList.add('hidden');
        });
    }

    // Handle affiliate item selection
    document.querySelectorAll('.btn-choose-wpa').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const code = this.getAttribute('data-code');
            const name = this.getAttribute('data-name');

            if (hiddenAffiliateCode) hiddenAffiliateCode.value = code;
            if (displayAffiliateName) {
                displayAffiliateName.textContent = name + ' (' + code.toUpperCase() + ')';
                displayAffiliateName.classList.remove('text-slate-400');
                displayAffiliateName.classList.add('text-emerald-400', 'font-semibold');
            }

            if (modalAffiliate) modalAffiliate.classList.add('hidden');
        });
    });

    // 2. Registration Form Submit (Sprint 2 Inisiasi)
    regForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const name = document.getElementById('reg_name').value.trim();
        const email = document.getElementById('reg_email').value.trim();
        const password = document.getElementById('reg_password') ? document.getElementById('reg_password').value : 'Almai123';
        const confirmPass = document.getElementById('reg_confirm_password') ? document.getElementById('reg_confirm_password').value : 'Almai123';
        
        // Support both visible input (regReferralCode) and locked hidden input (hiddenAffiliateCode)
        const regRefInput = document.getElementById('regReferralCode');
        const affiliateCode = (regRefInput && regRefInput.value.trim()) 
            ? regRefInput.value.trim() 
            : (hiddenAffiliateCode ? hiddenAffiliateCode.value.trim() : '');

        const errorBox = document.getElementById('reg_error_box');
        if (errorBox) errorBox.classList.add('hidden');

        // Validations
        if (!name || !email) {
            showError('Mohon isi Nama Lengkap dan Email.');
            return;
        }

        if (name.length < 3) {
            showError('Nama lengkap minimal 3 karakter.');
            return;
        }

        if (!affiliateCode) {
            showError('Wajib mengisi atau memilih Kode Referral.');
            return;
        }

        const submitBtn = document.getElementById('btnSubmitRegV2');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses Inisiasi...';
        }

        // AJAX to Initiate Registration (Sprint 2 Endpoint)
        fetch('/register/initiate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                name: name,
                email: email,
                password: password,
                affiliator_code: affiliateCode
            })
        })
        .then(response => response.json())
        .then(data => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Daftar & Lanjutkan via WhatsApp ➔';
            }

            if (data.success) {
                currentToken = data.token;
                if (btnLaunchWa) btnLaunchWa.href = data.wa_url;

                // Auto launch WhatsApp deep link in new tab
                window.open(data.wa_url, '_blank');

                // Show Waiting Screen Modal
                if (waitingModal) {
                    waitingModal.classList.remove('hidden');
                }

                // Start Polling Engine for WA Verification (Sprint 3 readiness)
                startPolling(currentToken);
            } else {
                showError(data.message || 'Gagal memulai registrasi.');
            }
        })
        .catch(err => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Daftar & Lanjutkan via WhatsApp ➔';
            }
            showError('Terjadi kesalahan koneksi server.');
        });
    });

    function showError(msg) {
        const errorBox = document.getElementById('reg_error_box');
        if (errorBox) {
            errorBox.textContent = msg;
            errorBox.classList.remove('hidden');
        } else {
            alert(msg);
        }
    }

    // 3. Polling Engine
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
                        alert('Waktu verifikasi WhatsApp telah habis (expired). Silakan coba mendaftar lagi.');
                        if (waitingModal) waitingModal.classList.add('hidden');
                    }
                })
                .catch(err => {
                    console.error('Polling error:', err);
                });
        }, 2000);
    }
});
