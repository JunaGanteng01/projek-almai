<?= $this->extend('cwpa/layouts/main') ?>

<?= $this->section('content') ?>
<div class="w-full space-y-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('cwpa/dashboard/portofolio') ?>" class="p-2 hover:bg-white/10 rounded-lg transition text-gray-400 hover:text-white">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Buat Portofolio</h1>
            <p class="text-gray-400 text-sm">Daftarkan akun trading Anda untuk mulai membagikan sinyal</p>
        </div>
    </div>

    <form action="<?= base_url('cwpa/dashboard/portofolio/store') ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>
        
        <div class="bg-[#111] border border-white/10 rounded-xl p-8 md:p-10 shadow-xl">
            <h3 class="font-bold text-white mb-8 flex items-center gap-3 pb-4 border-b border-white/5">
                <i class="fas fa-shield-halved text-accent text-xl"></i>
                Kredensial Akun Trading
            </h3>

            <div class="space-y-8">
                <!-- Account Name -->
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <i class="fas fa-signature text-accent"></i> Nama Portofolio <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="account_name" value="<?= old('account_name') ?>" required
                           placeholder="Contoh: Gold Scalping V1"
                           class="w-full bg-[#0a0a0a] border border-white/10 rounded-xl px-5 py-4 text-white focus:border-accent focus:outline-none transition-all placeholder:text-gray-700">
                </div>

                <!-- Community Name -->
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <i class="fas fa-users text-accent"></i> Nama Komunitas <span class="text-gray-600 text-[10px] lowercase">(Opsional)</span>
                    </label>
                    <input type="text" name="community_name" value="<?= old('community_name') ?>"
                           placeholder="Masukkan nama komunitas Anda"
                           class="w-full bg-[#0a0a0a] border border-white/10 rounded-xl px-5 py-4 text-white focus:border-accent focus:outline-none transition-all placeholder:text-gray-700">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Account Login -->
                    <div>
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fas fa-hashtag text-accent"></i> ID Akun MT5 <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="account_login" value="<?= old('account_login') ?>" required
                               placeholder="12345678"
                               class="w-full bg-[#0a0a0a] border border-white/10 rounded-xl px-5 py-4 text-white focus:border-accent focus:outline-none transition-all font-mono placeholder:text-gray-700">
                    </div>

                    <!-- Broker -->
                    <div>
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fas fa-building text-accent"></i> Nama Broker <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="broker" value="<?= old('broker') ?>" required
                               placeholder="Masukan Nama Broker"
                               class="w-full bg-[#0a0a0a] border border-white/10 rounded-xl px-5 py-4 text-white focus:border-accent focus:outline-none transition-all placeholder:text-gray-700">
                    </div>
                </div>

                <!-- Server -->
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <i class="fas fa-server text-accent"></i> Server Trading <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="server" value="<?= old('server') ?>" required
                           placeholder="Masukan Nama Server"
                           class="w-full bg-[#0a0a0a] border border-white/10 rounded-xl px-5 py-4 text-white focus:border-accent focus:outline-none transition-all placeholder:text-gray-700">
                </div>
            </div>

            <div class="pt-10 flex flex-col md:flex-row gap-4">
                <button type="submit" class="flex-1 bg-accent hover:bg-green-400 text-black font-black py-4 rounded-xl transition-all shadow-lg shadow-accent/10 flex items-center justify-center gap-3 active:scale-[0.98]">
                    <i class="fas fa-plus-circle"></i>
                    BUAT PORTOFOLIO SEKARANG
                </button>
                <a href="<?= base_url('cwpa/dashboard/portofolio') ?>" class="px-8 py-4 bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white rounded-xl transition-all text-center font-bold">
                    BATAL
                </a>
            </div>
        </div>

        <!-- Tip Box -->
        <div class="bg-blue-500/5 border border-blue-500/10 rounded-xl p-6 flex gap-4">
            <div class="w-10 h-10 bg-blue-500/10 rounded-xl flex items-center justify-center flex-shrink-0 text-blue-400">
                <i class="fas fa-info-circle"></i>
            </div>
            <div>
                <h5 class="text-blue-400 font-bold mb-1 text-sm italic italic_not_working_on_label">Penting!</h5>
                <p class="text-xs text-gray-500 leading-relaxed">Pastikan nomor akun yang Anda masukkan benar. Setelah dibuat, Anda perlu memasang **PortofolioEA.mq5** pada terminal MT5 akun tersebut agar data dapat mengalir secara otomatis ke halaman publik Almai.</p>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
