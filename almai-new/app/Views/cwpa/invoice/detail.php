<?= $this->extend('cwpa/layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto">
    <!-- Header Actions -->
    <div class="mb-6 flex items-center justify-between no-print">
        <a href="<?= $backUrl ?? base_url('cwpa/dashboard/transaksi') ?>" class="flex items-center gap-2 text-gray-400 hover:text-white transition">
            <i class="fas fa-arrow-left"></i>
            <span class="text-sm">Kembali ke Transaksi</span>
        </a>
        <div class="flex gap-2">
            <button onclick="downloadPDF()" class="px-4 py-2 bg-accent text-black font-bold rounded-lg text-sm hover:bg-white transition">
                <i class="fas fa-download mr-2"></i> Download PDF
            </button>
        </div>
    </div>

    <!-- Invoice Card -->
    <div id="invoiceContent" class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
        <!-- Invoice Header -->
        <div class="p-6 md:p-8 border-b border-white/10">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <img src="<?= base_url('images/alma.gif') ?>" alt="ALMAI" class="h-10">
                        <span class="text-2xl font-bold">ALMAI</span>
                    </div>
                    <p class="text-gray-400 text-sm">PT. Alma Indonesia Raya</p>
                    <p class="text-gray-400 text-sm">Jl. Badak Agung No. 22 Kav. 3, Renon, Denpasar - Bali. 80226</p>
                    <p class="text-gray-400 text-sm">Telp: 0361-3610019</p>
                </div>
                <div class="text-left md:text-right">
                    <h1 class="text-3xl font-bold mb-2">INVOICE</h1>
                    <p class="text-xl font-mono text-accent">#<?= esc($transaksi['invoice_number']) ?></p>
                    <div class="mt-4">
                        <?php
                        $statusColors = [
                            'pending' => 'bg-yellow-500/20 text-yellow-500 border-yellow-500/20',
                            'paid' => 'bg-blue-500/20 text-blue-500 border-blue-500/20',
                            'confirmed' => 'bg-accent/20 text-accent border-accent/20',
                            'cancelled' => 'bg-red-500/20 text-red-500 border-red-500/20',
                            'refunded' => 'bg-purple-500/20 text-purple-500 border-purple-500/20'
                        ];
                        $statusText = [
                            'pending' => 'MENUNGGU PEMBAYARAN',
                            'paid' => 'MENUNGGU KONFIRMASI',
                            'confirmed' => 'LUNAS',
                            'cancelled' => 'DIBATALKAN',
                            'refunded' => 'REFUND'
                        ];
                        ?>
                        <span class="px-4 py-2 rounded-full text-sm font-bold border <?= $statusColors[$transaksi['status']] ?? 'bg-gray-500/20 text-gray-500 border-gray-500/20' ?>">
                            <?= $statusText[$transaksi['status']] ?? strtoupper($transaksi['status']) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Info -->
        <div class="p-6 md:p-8 border-b border-white/10">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm text-gray-400 mb-2">Ditagihkan Kepada:</h3>
                    <p class="font-bold text-lg"><?= esc($transaksi['user_name'] ?? 'Customer') ?></p>
                    <p class="text-gray-400"><?= esc($transaksi['user_email'] ?? '-') ?></p>
                </div>
                <div class="md:text-right">
                    <div class="mb-4">
                        <p class="text-sm text-gray-400">Tanggal Invoice</p>
                        <p class="font-bold"><?= date('d F Y', strtotime($transaksi['created_at'])) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Metode Pembayaran</p>
                        <p class="font-bold">
                            <?php
                            $methodLabels = [
                                'xendit' => 'Payment Gateway (Xendit)',
                                'transfer' => 'Transfer Bank',
                                'poin' => 'Almai Poin'
                            ];
                            echo $methodLabels[$transaksi['payment_method']] ?? ucfirst($transaksi['payment_method']);
                            ?>
                        </p>
                    </div>
                    <?php if ($transaksi['paid_at']): ?>
                        <div class="mt-4">
                            <p class="text-sm text-gray-400">Tanggal Bayar</p>
                            <p class="font-bold"><?= date('d F Y, H:i', strtotime($transaksi['paid_at'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="p-6 md:p-8 border-b border-white/10">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-left py-3 text-sm text-gray-400">Item</th>
                        <th class="text-center py-3 text-sm text-gray-400">Qty</th>
                        <th class="text-right py-3 text-sm text-gray-400">Harga</th>
                        <th class="text-right py-3 text-sm text-gray-400">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-white/5">
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <span class="px-2 py-1 bg-accent/20 text-accent rounded text-xs uppercase"><?= esc($transaksi['product_type']) ?></span>
                                <span><?= esc($transaksi['product_name']) ?></span>
                            </div>
                        </td>
                        <td class="py-4 text-center">1</td>
                        <td class="py-4 text-right">
                            <?php if ($transaksi['payment_method'] === 'poin'): ?>
                                <?= number_format($transaksi['amount'], 0, ',', '.') ?> Poin
                            <?php else: ?>
                                Rp <?= number_format($transaksi['amount'], 0, ',', '.') ?>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 text-right">
                            <?php if ($transaksi['payment_method'] === 'poin'): ?>
                                <?= number_format($transaksi['amount'], 0, ',', '.') ?> Poin
                            <?php else: ?>
                                Rp <?= number_format($transaksi['amount'], 0, ',', '.') ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="p-6 md:p-8 border-b border-white/10">
            <div class="max-w-xs ml-auto space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Subtotal</span>
                    <span>
                        <?php if ($transaksi['payment_method'] === 'poin'): ?>
                            <?= number_format($transaksi['amount'], 0, ',', '.') ?> Poin
                        <?php else: ?>
                            Rp <?= number_format($transaksi['amount'], 0, ',', '.') ?>
                        <?php endif; ?>
                    </span>
                </div>
                <?php if ($transaksi['discount'] > 0): ?>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Diskon</span>
                        <span class="text-red-400">- Rp <?= number_format($transaksi['discount'], 0, ',', '.') ?></span>
                    </div>
                <?php endif; ?>
                <div class="flex justify-between text-lg font-bold pt-3 border-t border-white/10">
                    <span>Total Bayar</span>
                    <span class="text-accent">
                        <?php if ($transaksi['payment_method'] === 'poin'): ?>
                            <?= number_format($transaksi['total'], 0, ',', '.') ?> Poin
                        <?php else: ?>
                            Rp <?= number_format($transaksi['total'], 0, ',', '.') ?>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Payment Info based on status and method -->
        <?php if ($transaksi['status'] === 'pending'): ?>
            <?php if ($transaksi['payment_method'] === 'xendit' && !empty($xenditUrl)): ?>
                <!-- Xendit Payment -->
                <div class="p-6 md:p-8 bg-blue-500/10 border-t border-blue-500/30 no-print">
                    <h3 class="font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-credit-card text-blue-500"></i> Pembayaran via Payment Gateway
                    </h3>
                    <p class="text-gray-400 text-sm mb-4">Klik tombol di bawah untuk melanjutkan pembayaran melalui Xendit:</p>
                    <a href="<?= esc($xenditUrl) ?>" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-500 text-white font-bold rounded-xl hover:bg-blue-600 transition">
                        <i class="fas fa-external-link-alt"></i> Bayar Sekarang
                    </a>
                </div>
            <?php elseif ($transaksi['payment_method'] === 'transfer'): ?>
                <!-- Transfer Bank -->
                <div class="p-6 md:p-8 bg-yellow-500/10 border-t border-yellow-500/30">
                    <h3 class="font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-university text-yellow-500"></i> Transfer Bank Manual
                    </h3>
                    <p class="text-gray-400 text-sm mb-4">Silakan transfer ke rekening berikut:</p>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-black/30 rounded-xl p-4">
                            <p class="text-sm text-gray-400 mb-1">Bank</p>
                            <p class="font-bold text-lg"><?= esc($bankInfo['bank_name']) ?></p>
                        </div>
                        <div class="bg-black/30 rounded-xl p-4">
                            <p class="text-sm text-gray-400 mb-1">Nomor Rekening</p>
                            <p class="font-mono text-accent text-lg"><?= esc($bankInfo['account_number']) ?></p>
                            <p class="text-sm text-gray-400">a.n. <?= esc($bankInfo['account_name']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php elseif ($transaksi['status'] === 'confirmed'): ?>
            <!-- Confirmed/Paid -->
            <div class="p-6 md:p-8 bg-accent/10 border-t border-accent/30">
                <div class="flex items-start gap-3">
                    <i class="fas fa-check-circle text-accent text-xl mt-1"></i>
                    <div>
                        <h3 class="font-bold text-accent">Pembayaran Berhasil</h3>
                        <p class="text-sm text-gray-400 mt-1">
                            Dikonfirmasi pada: <?= $transaksi['confirmed_at'] ? date('d F Y, H:i', strtotime($transaksi['confirmed_at'])) : date('d F Y, H:i', strtotime($transaksi['paid_at'] ?? $transaksi['created_at'])) ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php elseif ($transaksi['status'] === 'cancelled'): ?>
            <!-- Cancelled -->
            <div class="p-6 md:p-8 bg-red-500/10 border-t border-red-500/30">
                <div class="flex items-start gap-3">
                    <i class="fas fa-times-circle text-red-500 text-xl mt-1"></i>
                    <div>
                        <h3 class="font-bold text-red-400">Transaksi Dibatalkan</h3>
                        <p class="text-sm text-gray-400 mt-1">Transaksi ini telah dibatalkan atau kadaluarsa.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Referral Info -->
        <?php if (!empty($transaksi['referral_code'])): ?>
            <div class="p-6 md:p-8 border-t border-white/10">
                <p class="text-sm text-gray-400">
                    <i class="fas fa-gift mr-2 text-accent"></i>
                    Menggunakan kode referral: <span class="text-accent font-mono"><?= esc($transaksi['referral_code']) ?></span>
                </p>
            </div>
        <?php endif; ?>

        <!-- Legal Documents Download Section -->
        <?php if ($transaksi['status'] === 'confirmed'): ?>
            <div class="p-6 md:p-8 border-t border-white/10 bg-blue-500/5">
                <h3 class="font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-file-contract text-blue-500"></i>
                    Dokumen Legal
                </h3>
                <p class="text-sm text-gray-400 mb-4">Download dokumen legal terkait transaksi ini:</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <!-- Perjanjian Pemberian Jasa -->
                    <a href="<?= base_url('cwpa/dashboard/transaksi/download-perjanjian/' . $transaksi['invoice_number']) ?>" 
                       class="flex items-center gap-3 p-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition group">
                        <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-sm text-white">Perjanjian Pemberian Jasa</p>
                            <p class="text-xs text-gray-500">PDF Document</p>
                        </div>
                        <i class="fas fa-download text-gray-500 group-hover:text-accent transition"></i>
                    </a>

                    <!-- Pemberitahuan Risiko -->
                    <a href="<?= base_url('cwpa/dashboard/transaksi/download-risiko/' . $transaksi['invoice_number']) ?>" 
                       class="flex items-center gap-3 p-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition group">
                        <div class="w-10 h-10 bg-red-500/20 rounded-lg flex items-center justify-center text-red-500 group-hover:bg-red-500 group-hover:text-white transition">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-sm text-white">Pemberitahuan Risiko</p>
                            <p class="text-xs text-gray-500">PDF Document</p>
                        </div>
                        <i class="fas fa-download text-gray-500 group-hover:text-accent transition"></i>
                    </a>

                    <!-- Profil Perusahaan -->
                    <a href="<?= base_url('cwpa/dashboard/transaksi/download-profil/' . $transaksi['invoice_number']) ?>" 
                       class="flex items-center gap-3 p-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition group">
                        <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center text-purple-500 group-hover:bg-purple-500 group-hover:text-white transition">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-sm text-white">Profil Perusahaan</p>
                            <p class="text-xs text-gray-500">PDF Document</p>
                        </div>
                        <i class="fas fa-download text-gray-500 group-hover:text-accent transition"></i>
                    </a>
                </div>
                <p class="text-xs text-gray-500 mt-4">
                    <i class="fas fa-info-circle mr-1"></i>
                    Dokumen legal ini di-generate secara otomatis berdasarkan data transaksi Anda.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer Notes -->
    <div class="mt-8 text-center text-sm text-gray-500">
        <p>Terima kasih telah berbelanja di ALMAI</p>
        <p class="mt-1">Jika ada pertanyaan, hubungi CS kami di 085183231800</p>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    async function downloadPDF() {
        const element = document.getElementById('invoiceContent');
        const invoiceNumber = '<?= esc($transaksi['invoice_number']) ?>';

        // Clone element to avoid modifying the original
        const clone = element.cloneNode(true);
        
        // Apply dark theme styles for PDF
        clone.style.backgroundColor = '#111111';
        clone.style.color = '#ffffff';
        clone.style.padding = '20px';
        
        // Keep dark theme colors for PDF
        const allElements = clone.querySelectorAll('*');
        allElements.forEach(el => {
            // Keep text white by default
            const computedStyle = window.getComputedStyle(el);
            
            // Preserve gray text colors
            if (el.classList.contains('text-gray-400')) {
                el.style.color = '#9ca3af';
            }
            if (el.classList.contains('text-gray-500')) {
                el.style.color = '#6b7280';
            }
            
            // Preserve accent color (green)
            if (el.classList.contains('text-accent')) {
                el.style.color = '#33e818';
            }
            
            // Preserve status colors
            if (el.classList.contains('text-yellow-500')) {
                el.style.color = '#eab308';
            }
            if (el.classList.contains('text-blue-500')) {
                el.style.color = '#3b82f6';
            }
            if (el.classList.contains('text-red-400') || el.classList.contains('text-red-500')) {
                el.style.color = '#f87171';
            }
            if (el.classList.contains('text-purple-500')) {
                el.style.color = '#a855f7';
            }
            
            // Preserve background colors
            if (el.classList.contains('bg-white/5')) {
                el.style.backgroundColor = 'rgba(255, 255, 255, 0.05)';
            }
            if (el.classList.contains('bg-white/10')) {
                el.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
            }
            if (el.classList.contains('bg-black/30')) {
                el.style.backgroundColor = 'rgba(0, 0, 0, 0.3)';
            }
        });

        const opt = {
            margin: 10,
            filename: 'Invoice-' + invoiceNumber + '.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { 
                scale: 2,
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#111111',
                logging: false,
                imageTimeout: 15000,
                removeContainer: true
            },
            jsPDF: { 
                unit: 'mm', 
                format: 'a4', 
                orientation: 'portrait' 
            }
        };

        try {
            await html2pdf().set(opt).from(clone).save();
        } catch (error) {
            console.error('PDF generation error:', error);
            alert('Terjadi kesalahan saat membuat PDF. Silakan coba lagi.');
        }
    }
</script>
<?= $this->endSection() ?>
