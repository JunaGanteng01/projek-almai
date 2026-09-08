<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="<?= base_url('css/tailwind.min.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        html,
        body {
            background-color: #050505;
            color: #ffffff;
        }

        @media print {
            body {
                background: white !important;
                color: black !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            .print-white {
                background: white !important;
                border-color: #e5e7eb !important;
            }

            .print-black {
                color: black !important;
            }

            .print-gray {
                color: #6b7280 !important;
            }
        }
    </style>
</head>

<body class="antialiased min-h-screen">
    <!-- Header -->
    <header class="bg-[#0a0a0a] border-b border-white/10 px-4 md:px-8 py-4 sticky top-0 z-50 no-print">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <a href="<?= $backUrl ?? base_url('user/transaksi') ?>" class="flex items-center gap-2 text-gray-400 hover:text-white">
                <i class="fas fa-arrow-left"></i>
                <span class="text-sm">Kembali</span>
            </a>
            <div class="flex gap-2">
                <button onclick="downloadPDF()" class="px-4 py-2 bg-accent text-black font-bold rounded-lg text-sm hover:bg-white transition">
                    <i class="fas fa-download mr-2"></i> Download PDF
                </button>
                <button onclick="window.print()" class="px-4 py-2 bg-white/10 text-white font-bold rounded-lg text-sm hover:bg-white/20 transition">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8">
        <?php if (session()->getFlashdata('new_account_password')): ?>
            <div class="bg-blue-500 rounded-2xl p-6 mb-8 shadow-[0_0_30px_rgba(59,130,246,0.3)] border border-blue-400/30 overflow-hidden relative">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class="fas fa-user-plus text-8xl -rotate-12"></i>
                </div>
                <div class="relative z-10 text-white">
                    <h2 class="text-2xl font-bold mb-2 flex items-center gap-3">
                        <span class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-magic text-sm"></i>
                        </span>
                        Selamat Bergabung!
                    </h2>
                    <p class="text-white/80 mb-6 leading-relaxed">Akun Anda telah dibuat otomatis. Simpan data login berikut untuk mengakses layanan Anda selanjutnya:</p>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-black/20 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                            <p class="text-xs text-white/50 mb-1 uppercase tracking-wider">Email Username</p>
                            <p class="font-bold text-lg"><?= esc(session()->get('userEmail')) ?></p>
                        </div>
                        <div class="bg-black/20 backdrop-blur-sm rounded-xl p-4 border border-white/10 group">
                            <p class="text-xs text-white/50 mb-1 uppercase tracking-wider flex justify-between">
                                Password Anda
                                <span class="text-[10px] lowercase italic opacity-50">Private</span>
                            </p>
                            <div class="flex items-center justify-between">
                                <p class="font-bold text-lg font-mono tracking-wider"><?= esc(session()->getFlashdata('new_account_password')) ?></p>
                                <button onclick="navigator.clipboard.writeText('<?= esc(session()->getFlashdata('new_account_password')) ?>'); alert('Password disalin!')"
                                    class="text-xs bg-white/10 hover:bg-white/20 px-2 py-1 rounded transition">Copy</button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-start gap-3 bg-white/10 rounded-xl p-3 text-xs">
                        <i class="fas fa-info-circle mt-0.5"></i>
                        <p>Anda dapat mengubah password kapan saja di menu profil. Detail login ini juga akan dikirimkan ke email/WA Anda setelah konfirmasi.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-accent/20 border border-accent/50 text-accent px-4 py-3 rounded-xl mb-6 no-print">
                <i class="fas fa-check-circle mr-2"></i><?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl mb-6 no-print">
                <i class="fas fa-exclamation-circle mr-2"></i><?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Invoice Card -->
        <div id="invoiceContent" class="bg-[#111] print-white border border-white/10 rounded-2xl overflow-hidden">
            <!-- Invoice Header -->
            <div class="p-6 md:p-8 border-b border-white/10">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-10">
                            <span class="text-2xl font-bold print-black">ALMAI</span>
                        </div>
                        <p class="text-gray-400 print-gray text-sm">PT. Alma Indonesia Raya</p>
                        <p class="text-gray-400 print-gray text-sm">Jl. Badak Agung No. 22 Kav. 3, Renon, Denpasar - Bali. 80226</p>
                        <p class="text-gray-400 print-gray text-sm">Telp: 0361-3610019</p>
                    </div>
                    <div class="text-left md:text-right">
                        <h1 class="text-3xl font-bold mb-2 print-black">INVOICE</h1>
                        <p class="text-xl font-mono text-accent">#<?= esc($transaksi['invoice_number']) ?></p>
                        <div class="mt-4">
                            <?php
                            $statusColors = [
                                'pending' => 'bg-yellow-500/20 text-yellow-500',
                                'paid' => 'bg-blue-500/20 text-blue-500',
                                'confirmed' => 'bg-accent/20 text-accent',
                                'cancelled' => 'bg-red-500/20 text-red-500',
                                'refunded' => 'bg-purple-500/20 text-purple-500'
                            ];
                            $statusText = [
                                'pending' => 'MENUNGGU PEMBAYARAN',
                                'paid' => 'MENUNGGU KONFIRMASI',
                                'confirmed' => 'LUNAS',
                                'cancelled' => 'DIBATALKAN',
                                'refunded' => 'REFUND'
                            ];
                            ?>
                            <span class="px-4 py-2 rounded-full text-sm font-bold <?= $statusColors[$transaksi['status']] ?? 'bg-gray-500/20 text-gray-500' ?>">
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
                        <h3 class="text-sm text-gray-400 print-gray mb-2">Ditagihkan Kepada:</h3>
                        <p class="font-bold text-lg print-black"><?= esc($transaksi['user_name'] ?? session()->get('userName')) ?></p>
                        <p class="text-gray-400 print-gray"><?= esc($transaksi['user_email'] ?? session()->get('userEmail')) ?></p>
                    </div>
                    <div class="md:text-right">
                        <div class="mb-4">
                            <p class="text-sm text-gray-400 print-gray">Tanggal Invoice</p>
                            <p class="font-bold print-black"><?= date('d F Y', strtotime($transaksi['created_at'])) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 print-gray">Metode Pembayaran</p>
                            <p class="font-bold print-black">
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
                                <p class="text-sm text-gray-400 print-gray">Tanggal Bayar</p>
                                <p class="font-bold print-black"><?= date('d F Y, H:i', strtotime($transaksi['paid_at'])) ?></p>
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
                            <th class="text-left py-3 text-sm text-gray-400 print-gray">Item</th>
                            <th class="text-center py-3 text-sm text-gray-400 print-gray">Qty</th>
                            <th class="text-right py-3 text-sm text-gray-400 print-gray">Harga</th>
                            <th class="text-right py-3 text-sm text-gray-400 print-gray">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $subtotal = 0;
                        $totalDiscount = 0;
                        foreach ($items as $item): 
                            $subtotal += $item['amount'];
                            $totalDiscount += $item['discount'];
                        ?>
                        <tr class="border-b border-white/5">
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <span class="px-2 py-1 bg-accent/20 text-accent rounded text-xs uppercase"><?= esc($item['product_type']) ?></span>
                                    <span class="print-black"><?= esc($item['product_name']) ?></span>
                                </div>
                            </td>
                            <td class="py-4 text-center print-black">1</td>
                             <td class="py-4 text-right print-black">
                                <?php if ($item['payment_method'] === 'poin' && $item['amount'] == 0 && preg_match('/Pembayaran poin: ([\d,.]+) Poin/', $item['notes'] ?? '', $m)): ?>
                                    <?= $m[1] ?> Poin
                                <?php else: ?>
                                    Rp <?= number_format($item['amount'], 0, ',', '.') ?>
                                <?php endif; ?>
                             </td>
                             <td class="py-4 text-right print-black">
                                <?php if ($item['payment_method'] === 'poin' && $item['amount'] == 0 && preg_match('/Pembayaran poin: ([\d,.]+) Poin/', $item['notes'] ?? '', $m)): ?>
                                    <?= $m[1] ?> Poin
                                <?php else: ?>
                                    Rp <?= number_format($item['amount'], 0, ',', '.') ?>
                                <?php endif; ?>
                             </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="p-6 md:p-8 border-b border-white/10">
                <div class="max-w-xs ml-auto space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400 print-gray">Subtotal</span>
                        <span class="print-black">
                            Rp <?= number_format($subtotal, 0, ',', '.') ?>
                        </span>
                    </div>
                    <?php if ($totalDiscount > 0): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400 print-gray">Diskon/Poin</span>
                            <span class="text-red-400">- Rp <?= number_format($totalDiscount, 0, ',', '.') ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between text-lg font-bold pt-3 border-t border-white/10">
                        <span class="print-black">Total Bayar</span>
                        <span class="text-accent">
                            Rp <?= number_format($subtotal - $totalDiscount, 0, ',', '.') ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Payment Info based on status and method -->
            <?php if ($transaksi['status'] === 'pending'): ?>
                <?php if ($transaksi['payment_method'] === 'xendit' && !empty($xenditUrl)): ?>
                    <!-- Xendit Payment -->
                    <div class="p-6 md:p-8 bg-blue-500/10 border-t border-blue-500/30 no-print">
                        <h3 class="font-bold mb-4 print-black flex items-center gap-2">
                            <i class="fas fa-credit-card text-blue-500"></i> Pembayaran via Payment Gateway
                        </h3>
                        <p class="text-gray-400 text-sm mb-4">Klik tombol di bawah untuk melanjutkan pembayaran melalui Xendit:</p>
                        <a href="<?= esc($xenditUrl) ?>" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-500 text-white font-bold rounded-xl hover:bg-blue-600 transition">
                            <i class="fas fa-external-link-alt"></i> Bayar Sekarang
                        </a>
                        <p class="text-xs text-gray-500 mt-3">
                            <i class="fas fa-info-circle mr-1"></i> Anda akan diarahkan ke halaman pembayaran Xendit
                        </p>
                    </div>
                <?php elseif ($transaksi['payment_method'] === 'transfer'): ?>
                    <!-- Transfer Bank -->
                    <div class="p-6 md:p-8 bg-yellow-500/10 border-t border-yellow-500/30">
                        <h3 class="font-bold mb-4 print-black flex items-center gap-2">
                            <i class="fas fa-university text-yellow-500"></i> Transfer Bank Manual
                        </h3>
                        <p class="text-gray-400 text-sm mb-4">Silakan transfer ke rekening berikut:</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-black/30 print-white rounded-xl p-4">
                                <p class="text-sm text-gray-400 print-gray mb-1">Bank</p>
                                <p class="font-bold print-black text-lg"><?= esc($bankInfo['bank_name']) ?></p>
                            </div>
                            <div class="bg-black/30 print-white rounded-xl p-4">
                                <p class="text-sm text-gray-400 print-gray mb-1">Nomor Rekening</p>
                                <p class="font-mono text-accent text-lg"><?= esc($bankInfo['account_number']) ?></p>
                                <p class="text-sm text-gray-400 print-gray">a.n. <?= esc($bankInfo['account_name']) ?></p>
                            </div>
                        </div>
                        <div class="mt-4 p-4 bg-accent/10 border border-accent/30 rounded-xl">
                            <p class="text-sm text-accent">
                                <i class="fas fa-info-circle mr-2"></i>
                                Transfer tepat sesuai nominal: <strong>Rp <?= number_format($transaksi['total'], 0, ',', '.') ?></strong>
                            </p>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">
                            Setelah transfer, konfirmasi pembayaran akan diproses dalam 1x24 jam.
                        </p>
                    </div>
                <?php else: ?>
                    <!-- Generic Pending -->
                    <div class="p-6 md:p-8 bg-yellow-500/10 border-t border-yellow-500/30">
                        <h3 class="font-bold mb-2 print-black flex items-center gap-2">
                            <i class="fas fa-clock text-yellow-500"></i> Menunggu Pembayaran
                        </h3>
                        <p class="text-gray-400 text-sm">Silakan selesaikan pembayaran Anda.</p>
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
                <?php if ($transaksi['product_type'] === 'event'): ?>
                    <div class="p-6 md:p-8 border-t border-accent/30 bg-accent/5">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                            <div>
                                <h3 class="font-bold text-accent mb-1"><i class="fas fa-ticket-alt mr-2"></i>E-Ticket Tersedia</h3>
                                <p class="text-sm text-gray-400">Silakan unduh E-Ticket Anda untuk masuk ke acara.</p>
                            </div>
                            <a href="<?= base_url('user/ticket/' . $transaksi['invoice_number']) ?>" target="_blank" class="w-full md:w-auto px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center justify-center gap-2 shadow-[0_0_15px_rgba(51,232,24,0.3)]">
                                <i class="fas fa-download"></i> Download E-Ticket
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
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
        </div>

        <!-- Footer Notes -->
        <div class="mt-8 text-center text-sm text-gray-500 print-gray">
            <p>Terima kasih telah berbelanja di ALMAI</p>
            <p class="mt-1">Jika ada pertanyaan, hubungi CS kami di 085183231800</p>
        </div>
    </main>

    <script>
        function downloadPDF() {
            const element = document.getElementById('invoiceContent');
            const invoiceNumber = '<?= esc($transaksi['invoice_number']) ?>';

            const opt = {
                margin: 10,
                filename: 'Invoice-' + invoiceNumber + '.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    useCORS: true
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };

            // Add temporary styles for PDF
            element.style.backgroundColor = '#ffffff';
            element.style.color = '#000000';

            html2pdf().set(opt).from(element).save().then(() => {
                // Restore styles
                element.style.backgroundColor = '';
                element.style.color = '';
            });
        }
    </script>
</body>

</html>