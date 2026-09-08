<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="pt-32 pb-16 min-h-screen">
    <div class="container mx-auto px-6 max-w-4xl">
        <h1 class="text-3xl font-bold mb-8">Riwayat Transaksi</h1>
        
        <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl mb-6">
            <?= session()->getFlashdata('error') ?>
        </div>
        <?php endif; ?>

        <?php if (empty($transaksiList)): ?>
        <div class="bg-[#111] rounded-2xl border border-white/10 p-12 text-center">
            <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-receipt text-3xl text-gray-500"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Belum Ada Transaksi</h3>
            <p class="text-gray-400 mb-6">Anda belum memiliki riwayat transaksi.</p>
            <a href="<?= base_url('kelas') ?>" class="inline-block px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                Jelajahi Kelas
            </a>
        </div>
        <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($transaksiList as $transaksi): ?>
            <?php
            $statusColors = [
                'pending' => 'bg-yellow-500/20 text-yellow-500',
                'paid' => 'bg-blue-500/20 text-blue-500',
                'confirmed' => 'bg-accent/20 text-accent',
                'cancelled' => 'bg-red-500/20 text-red-500',
            ];
            $statusText = [
                'pending' => 'Menunggu',
                'paid' => 'Dibayar',
                'confirmed' => 'Lunas',
                'cancelled' => 'Batal',
            ];
            ?>
            <a href="<?= base_url('user/invoice/' . $transaksi['invoice_number']) ?>" class="block bg-[#111] rounded-2xl border border-white/10 p-6 hover:border-accent/50 transition">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <p class="font-mono text-accent text-sm mb-1">#<?= esc($transaksi['invoice_number']) ?></p>
                        <h3 class="font-bold text-lg"><?= esc($transaksi['product_name']) ?></h3>
                        <p class="text-gray-400 text-sm"><?= date('d M Y, H:i', strtotime($transaksi['created_at'])) ?></p>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-xl font-bold text-accent mb-2">Rp <?= number_format($transaksi['total'], 0, ',', '.') ?></p>
                        <span class="px-3 py-1 rounded-full text-xs font-bold <?= $statusColors[$transaksi['status']] ?? 'bg-gray-500/20 text-gray-500' ?>">
                            <?= $statusText[$transaksi['status']] ?? ucfirst($transaksi['status']) ?>
                        </span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
