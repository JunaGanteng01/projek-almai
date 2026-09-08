<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
.feedback-container {
    min-height: 100vh;
    padding: 120px 0 80px 0;
    position: relative;
    overflow: hidden;
    background-color: #000; /* ALMAI base black */
    color: #fff;
    font-family: 'Public Sans', sans-serif;
}

/* Background Elements */
.bg-shape {
    position: absolute;
    filter: blur(120px);
    z-index: 0;
    border-radius: 50%;
    opacity: 0.4;
}
.shape-1 {
    top: -10%;
    left: -10%;
    width: 40vw;
    height: 40vw;
    background: #33E818;
}
.shape-2 {
    bottom: -10%;
    right: -10%;
    width: 30vw;
    height: 30vw;
    background: #10b981;
}

.feedback-card {
    background: rgba(15, 15, 15, 0.6);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(51, 232, 24, 0.2);
    border-radius: 24px;
    padding: 40px;
    position: relative;
    z-index: 10;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 30px rgba(51, 232, 24, 0.05);
}

@media (max-width: 768px) {
    .feedback-card {
        padding: 30px 20px;
    }
}

.feedback-header {
    text-align: center;
    margin-bottom: 40px;
}

.feedback-header h1 {
    font-size: clamp(2rem, 5vw, 2.5rem);
    font-weight: 800;
    color: #fff;
    margin-bottom: 15px;
}

.feedback-header h1 span {
    color: #33E818;
}

.feedback-header p {
    color: #9ca3af;
    font-size: 1.1rem;
}

.poin-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(51, 232, 24, 0.1);
    border: 1px solid rgba(51, 232, 24, 0.3);
    color: #33E818;
    padding: 8px 20px;
    border-radius: 50px;
    font-weight: 600;
    margin-top: 15px;
    box-shadow: 0 0 15px rgba(51, 232, 24, 0.1);
}

/* Star Rating */
.rating-group {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    gap: 15px;
    margin: 30px 0;
    position: relative;
}

.rating-group input {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

.rating-group label {
    cursor: pointer;
    font-size: clamp(30px, 8vw, 45px);
    color: #374151;
    transition: all 0.3s ease;
}

.rating-group label:hover,
.rating-group label:hover ~ label,
.rating-group input:checked ~ label {
    color: #33E818;
    text-shadow: 0 0 20px rgba(51, 232, 24, 0.5);
    transform: scale(1.1);
}

.form-group label {
    display: block;
    margin-bottom: 10px;
    font-weight: 500;
    color: #e5e7eb;
}

.form-control-custom {
    width: 100%;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 15px;
    color: #fff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control-custom:focus {
    outline: none;
    border-color: #33E818;
    background: rgba(0, 0, 0, 0.4);
    box-shadow: 0 0 15px rgba(51, 232, 24, 0.15);
}

.form-control-custom::placeholder {
    color: #6b7280;
}

.btn-submit {
    background: #33E818;
    color: #000;
    border: none;
    padding: 16px 32px;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 700;
    width: 100%;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}

.btn-submit:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(51, 232, 24, 0.4);
    color: #000;
}

.auth-message {
    text-align: center;
    padding: 30px 10px;
}

.auth-message i {
    font-size: 3.5rem;
    color: #6b7280;
    margin-bottom: 20px;
    display: block;
}

.auth-message h3 {
    font-size: 1.8rem;
    margin-bottom: 15px;
    color: #fff;
    font-weight: 700;
}

.auth-message p {
    color: #9ca3af;
    margin-bottom: 35px;
    font-size: 1.1rem;
}

.btn-login {
    background: #33E818;
    color: #000;
    padding: 14px 35px;
    border-radius: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(51, 232, 24, 0.3);
    color: #000;
}

.btn-register {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    padding: 14px 35px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
}

.btn-register:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
}

.auth-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

/* Success Message */
.success-message {
    text-align: center;
    padding: 40px 20px;
}

.success-icon {
    font-size: 70px;
    color: #33E818;
    margin-bottom: 25px;
    animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    display: block;
    text-shadow: 0 0 20px rgba(51, 232, 24, 0.4);
}

@keyframes scaleIn {
    0% { transform: scale(0); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}
</style>

<div class="feedback-container">
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="flex justify-center items-center min-h-[60vh] w-full">
            <div class="w-full max-w-2xl">
                
                <?php if (session()->getFlashdata('message')): ?>
                    <div class="alert alert-dismissible fade show mb-4" role="alert" style="background: rgba(51, 232, 24, 0.1); border: 1px solid rgba(51, 232, 24, 0.3); color: #fff; border-radius: 12px;">
                        <i class="fa-solid fa-check-circle" style="color: #33E818; margin-right: 8px;"></i>
                        <?= session()->getFlashdata('message') ?>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-dismissible fade show mb-4" role="alert" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fff; border-radius: 12px;">
                        <i class="fa-solid fa-exclamation-circle" style="color: #ef4444; margin-right: 8px;"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="feedback-card">
                    
                    <?php if (!$isLoggedIn): ?>
                        <div class="auth-message">
                            <i class="fa-solid fa-shield-halved"></i>
                            <h3>Login Diperlukan</h3>
                            <p>Anda harus login terlebih dahulu untuk dapat mengisi form feedback dan mengklaim <strong>300 ALMAI Poin</strong> secara gratis.</p>
                            <div class="flex flex-wrap gap-4 justify-center mt-6">
                                <a href="<?= base_url('login?redirect=feedback') ?>" class="btn-login">Login Sekarang</a>
                                <a href="<?= base_url('login?tab=register&redirect=feedback') ?>" class="btn-register">Daftar Akun</a>
                            </div>
                        </div>
                    <?php elseif ($hasSubmitted && !session()->getFlashdata('message')): ?>
                        <div class="success-message">
                            <i class="fa-solid fa-check-circle success-icon"></i>
                            <h2 style="font-weight: 700; margin-bottom: 15px;">Feedback Terkirim!</h2>
                            <p style="color: #9ca3af; line-height: 1.6;">Anda sudah pernah mengirimkan feedback. Terima kasih banyak atas partisipasi Anda dalam membangun ekosistem ALMAI menjadi lebih baik.</p>
                            <a href="<?= base_url('/') ?>" class="btn-submit mt-4" style="width: auto; display: inline-flex;">Kembali ke Beranda</a>
                        </div>
                    <?php elseif (session()->getFlashdata('message')): ?>
                        <div class="success-message">
                            <i class="fa-solid fa-star success-icon"></i>
                            <h2 style="font-weight: 700; margin-bottom: 15px;">Reward Berhasil Diklaim!</h2>
                            <p style="color: #9ca3af; line-height: 1.6;">Feedback Anda telah kami terima dan <strong>300 ALMAI Poin</strong> sudah ditambahkan ke saldo Anda.</p>
                            <a href="<?= base_url('user/dashboard') ?>" class="btn-submit mt-4" style="width: auto; display: inline-flex;">Cek Poin Anda</a>
                        </div>
                    <?php else: ?>
                        <div class="feedback-header">
                            <h1>Bagikan Pengalaman <span>Anda</span></h1>
                            <p>Bantu kami menjadi lebih baik dan dapatkan reward!</p>
                            <div class="poin-badge">
                                <i class="fa-solid fa-coins" style="color: #fbbf24;"></i>
                                +300 ALMAI Poin
                            </div>
                        </div>

                        <form action="<?= base_url('feedback/submit') ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="mb-5 text-center">
                                <label class="form-label" style="color: #e5e7eb; font-weight: 500; font-size: 1.1rem;">Seberapa puas Anda dengan ALMAI?</label>
                                <div class="rating-group">
                                    <input type="radio" id="star5" name="rating" value="5" required />
                                    <label for="star5"><i class="fa-solid fa-star"></i></label>
                                    
                                    <input type="radio" id="star4" name="rating" value="4" />
                                    <label for="star4"><i class="fa-solid fa-star"></i></label>
                                    
                                    <input type="radio" id="star3" name="rating" value="3" />
                                    <label for="star3"><i class="fa-solid fa-star"></i></label>
                                    
                                    <input type="radio" id="star2" name="rating" value="2" />
                                    <label for="star2"><i class="fa-solid fa-star"></i></label>
                                    
                                    <input type="radio" id="star1" name="rating" value="1" />
                                    <label for="star1"><i class="fa-solid fa-star"></i></label>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label for="message">Ceritakan pengalaman Anda (Kritik & Saran)</label>
                                <textarea id="message" name="message" class="form-control-custom" rows="6" placeholder="Tuliskan pengalaman, kritik, maupun saran Anda terhadap layanan ALMAI..." required></textarea>
                            </div>

                            <button type="submit" class="btn-submit mt-2">
                                Kirim Feedback  <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
