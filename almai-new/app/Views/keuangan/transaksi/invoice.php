<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        accent: '#33E818',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #1a1a1a;
            color: #ffffff;
        }

        .invoice-card {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <div class="invoice-card w-full max-w-md bg-[#09090b] rounded-3xl p-6 md:p-8 relative overflow-hidden border border-white/5">

        <!-- Header -->
        <div class="flex items-start justify-between mb-8">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-accent flex items-center justify-center">
                    <span class="text-black font-bold text-lg">A</span>
                </div>
                <span class="text-xl font-bold tracking-tight">Almai</span>
            </div>
            <div class="text-right">
                <div class="inline-block bg-[#0f391b] px-2 py-0.5 rounded text-[10px] font-bold text-[#33E818] mb-1">
                    TERBAYAR
                </div>
                <p class="text-gray-400 text-xs font-mono">#<?= $trx['invoice_number'] ?></p>
            </div>
        </div>

        <!-- Greeting -->
        <div class="mb-8">
            <h1 class="text-sm font-medium text-gray-200 mb-2">Halo <?= explode(' ', $trx['user_name'])[0] ?>,</h1>
            <p class="text-xs text-gray-400 leading-relaxed">
                Terima kasih telah mempercayakan pembelian Anda kepada ALMAI. Berikut kami sertakan invoice sebagai detail transaksi Anda.
            </p>
        </div>

        <!-- From / To Grid -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <!-- From -->
            <div>
                <p class="text-[10px] uppercase font-bold text-gray-500 mb-2 tracking-wider">Invoice From</p>
                <div class="text-xs text-gray-300 space-y-1">
                    <p class="font-bold text-white">PT. Alma Indonesia Raya</p>
                    <p>Jl. Badak Agung No. 22 Kav. 3, Renon, Denpasar - Bali. 80226</p>
                    <p>Telp: 0361-3610019</p>
                </div>
            </div>

            <!-- To & Payment Method -->
            <div class="space-y-6">
                <!-- To -->
                <!-- Use grid inside this col to match layout if needed, but the image shows From on left, To on Left (Wait).
                     Actually image shows:
                     Invoice From (Left)
                     (Space)
                     Invoice To (Left, below Invoice From in a new row?) NO.
                     
                     Looking at image:
                     Row 1: Invoice From (Left)
                     Row 2: Invoice To (Left)      Metode Pembayaran (Right)
                     Row 3: Date Create (Left)     Due Date (Right)
                -->
            </div>
        </div>

        <!-- Layout Correction based on Image Reading -->
        <!-- Section 1: Invoice From -->
        <div class="mb-6">
            <p class="text-[10px] uppercase font-bold text-gray-500 mb-2 tracking-wider">Invoice From</p>
            <div class="text-xs text-gray-300 space-y-0.5">
                <p class="font-bold text-white">PT. Alma Indonesia Raya</p>
                <p>Jl. Badak Agung No. 22 Kav. 3, Renon, Denpasar - Bali. 80226</p>
                <p>Telp: 0361-3610019</p>
            </div>
        </div>

        <!-- Section 2: Invoice To & Payment Method -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-[10px] uppercase font-bold text-gray-500 mb-2 tracking-wider">Invoice To</p>
                <div class="text-xs text-gray-300 space-y-0.5">
                    <p class="font-bold text-white truncate"><?= esc($trx['user_name']) ?></p>
                    <p class="truncate text-gray-500"><?= esc($trx['user_email']) ?></p>
                </div>
            </div>
            <div>
                <p class="text-[10px] uppercase font-bold text-gray-500 mb-2 tracking-wider">Metode Pembayaran</p>
                <div class="text-xs text-gray-300">
                    <p class="font-bold text-white uppercase"><?= esc($trx['payment_method'] ?? 'QRIS') ?></p>
                </div>
            </div>
        </div>

        <!-- Section 3: Dates -->
        <div class="grid grid-cols-2 gap-4 mb-8">
            <div>
                <p class="text-[10px] uppercase font-bold text-gray-500 mb-2 tracking-wider">Date Create</p>
                <p class="text-xs text-white font-medium"><?= date('d F Y', strtotime($trx['created_at'])) ?></p>
            </div>
            <div>
                <p class="text-[10px] uppercase font-bold text-gray-500 mb-2 tracking-wider">Due Date</p>
                <p class="text-xs text-white font-medium"><?= date('d M Y', strtotime($trx['created_at'] . ' + 3 days')) ?></p>
            </div>
        </div>

        <!-- Table -->
        <div class="mb-6">
            <div class="grid grid-cols-12 gap-2 mb-3 px-2">
                <div class="col-span-1 text-[10px] font-bold text-gray-500 uppercase">#</div>
                <div class="col-span-8 text-[10px] font-bold text-gray-500 uppercase">Qty</div>
                <div class="col-span-3 text-[10px] font-bold text-gray-500 uppercase text-right">Harga</div>
            </div>

            <div class="border-t border-dashed border-white/10 py-3 px-2">
                <div class="grid grid-cols-12 gap-2 items-center">
                    <div class="col-span-1 text-xs font-bold text-gray-400">1</div>
                    <div class="col-span-8">
                        <div class="flex items-center gap-2">
                            <span class="bg-[#0f391b] text-[#33E818] text-[8px] font-bold px-1.5 py-0.5 rounded uppercase tracking-wider">
                                <?= esc($trx['layanan_subcategory'] ?? $trx['product_type']) ?>
                            </span>
                            <span class="text-xs font-medium text-white truncate">
                                <?= esc($trx['layanan_name'] ?? $trx['product_name']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-span-3 text-right">
                        <span class="text-xs font-bold text-gray-300">Rp <?= number_format($trx['amount'] ?? $trx['total'], 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
            <div class="border-b border-dashed border-white/10 mb-2 w-full"></div>
        </div>

        <!-- Totals -->
        <div class="space-y-2 mb-8 pl-12 md:pl-32">
            <div class="flex justify-between items-center text-xs">
                <span class="text-gray-400">SubTotal</span>
                <span class="font-bold text-white">Rp <?= number_format($trx['amount'] ?? $trx['total'], 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between items-center text-xs">
                <span class="text-gray-400">Diskon</span>
                <span class="font-bold text-red-500">-Rp 0</span>
            </div>
            <div class="flex justify-between items-center text-xs">
                <span class="text-gray-400">PPN</span>
                <span class="font-bold text-gray-500">-</span>
            </div>
            <div class="flex justify-between items-center text-sm pt-2">
                <span class="text-gray-200 font-bold">Total</span>
                <span class="font-bold text-white">Rp <?= number_format($trx['total'], 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Success Details Line (looks like a progress bar or separator) -->
        <div class="w-16 h-1 bg-gray-700/50 rounded-full mb-6"></div>

        <!-- Payment Success Box -->
        <div class="bg-[#0f391b]/30 border border-[#33E818]/20 rounded-xl p-3 flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-full bg-[#33E818] flex items-center justify-center shrink-0">
                <i class="fas fa-check text-black text-sm"></i>
            </div>
            <div>
                <p class="text-[#33E818] text-xs font-bold">Pembayaran Berhasil</p>
                <p class="text-[10px] text-gray-400">Dikonfirmasi pada: <?= date('d F Y, H:i', strtotime($trx['updated_at'] ?? $trx['created_at'])) ?></p>
            </div>
        </div>

        <!-- CTA Button -->
        <a href="#" class="block w-full bg-[#33E818] hover:bg-[#2dc915] text-black font-bold text-sm text-center py-3 rounded-xl transition shadow-[0_0_20px_rgba(51,232,24,0.3)] mb-8">
            Belajar Sekarang
        </a>

        <!-- Footer Notes -->
        <div class="border-t border-dashed border-white/10 pt-4 flex justify-between items-start">
            <div>
                <p class="text-[10px] font-bold text-gray-300 uppercase mb-1">NOTES</p>
                <p class="text-[10px] text-gray-500">Menggunakan kode referral : <span class="text-[#33E818]"><?= esc($trx['referral_code'] ?? '-') ?></span></p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-gray-300 uppercase mb-1">Butuh bantuan?</p>
                <p class="text-[10px] text-gray-500">support@almai.id</p>
            </div>
        </div>

    </div>

</body>

</html>