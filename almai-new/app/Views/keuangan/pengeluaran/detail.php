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

        @media print {
            body {
                background-color: #ffffff !important;
                color: #000000 !important;
            }

            .invoice-card {
                border: none !important;
                box-shadow: none !important;
                background: #ffffff !important;
                color: #000000 !important;
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .text-gray-400, .text-gray-500, .text-gray-300 {
                color: #4b5563 !important;
            }

            .text-white {
                color: #000000 !important;
            }

            .bg-[#09090b] {
                background: #ffffff !important;
            }

            .border-white\/5, .border-white\/10 {
                border-color: #e5e7eb !important;
            }

            .bg-[#0f391b] {
                background: #f3f4f6 !important;
                border: 1px solid #d1d5db !important;
            }

            .text-[#33E818] {
                color: #16a34a !important;
            }
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-4">

    <!-- Back / Print Controls -->
    <div class="w-full max-w-md flex justify-between items-center mb-4 no-print">
        <a href="<?= base_url('keuangan/pengeluaran') ?>" class="flex items-center gap-2 text-gray-400 hover:text-white transition text-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Pengeluaran
        </a>
        <button onclick="window.print()" class="flex items-center gap-2 bg-[#222] border border-white/10 hover:bg-white hover:text-black text-white px-3 py-1.5 rounded-xl transition text-sm font-semibold">
            <i class="fas fa-print"></i> Cetak / Simpan PDF
        </button>
    </div>

    <div class="invoice-card w-full max-w-md bg-[#09090b] rounded-3xl p-6 md:p-8 relative overflow-hidden border border-white/5">

        <!-- Header -->
        <div class="flex items-start justify-between mb-8">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-accent flex items-center justify-center">
                    <span class="text-black font-bold text-lg">A</span>
                </div>
                <span class="text-xl font-bold tracking-tight text-white">Almai</span>
            </div>
            <div class="text-right">
                <?php if ($trx['status'] === 'lunas'): ?>
                    <div class="inline-block bg-[#0f391b] px-2 py-0.5 rounded text-[10px] font-bold text-[#33E818] mb-1">
                        LUNAS
                    </div>
                <?php elseif ($trx['status'] === 'pending'): ?>
                    <div class="inline-block bg-yellow-500/10 px-2 py-0.5 rounded text-[10px] font-bold text-yellow-500 mb-1">
                        PENDING
                    </div>
                <?php else: ?>
                    <div class="inline-block bg-red-500/10 px-2 py-0.5 rounded text-[10px] font-bold text-red-500 mb-1">
                        DIBATALKAN
                    </div>
                <?php endif; ?>
                <p class="text-gray-400 text-xs font-mono"><?= esc($trx['no_faktur']) ?></p>
            </div>
        </div>

        <!-- Greeting/Detail Type -->
        <div class="mb-8">
            <h1 class="text-sm font-medium text-gray-200 mb-2">Invoice Pengeluaran & Stok</h1>
            <p class="text-xs text-gray-400 leading-relaxed">
                Bukti pencatatan pengeluaran atau transaksi pembelian operasional PT. Alma Indonesia Raya.
            </p>
        </div>

        <!-- Section 1: Invoice From (Supplier) -->
        <div class="mb-6">
            <p class="text-[10px] uppercase font-bold text-gray-500 mb-2 tracking-wider">Invoice From (Supplier)</p>
            <div class="text-xs text-gray-300 space-y-0.5">
                <p class="font-bold text-white"><?= esc($trx['supplier_name']) ?></p>
                <?php if (!empty($trx['supplier_notes'])): ?>
                    <p class="text-gray-400 italic"><?= esc($trx['supplier_notes']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section 2: Invoice To & Payment Type -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-[10px] uppercase font-bold text-gray-500 mb-2 tracking-wider">Invoice To</p>
                <div class="text-xs text-gray-300 space-y-0.5">
                    <p class="font-bold text-white">PT. Alma Indonesia Raya</p>
                    <p class="text-gray-400">Denpasar, Bali</p>
                </div>
            </div>
            <div>
                <p class="text-[10px] uppercase font-bold text-gray-500 mb-2 tracking-wider">Status Bayar</p>
                <div class="text-xs text-gray-300">
                    <p class="font-bold text-white uppercase"><?= esc($trx['status']) ?></p>
                </div>
            </div>
        </div>

        <!-- Section 3: Dates -->
        <div class="grid grid-cols-2 gap-4 mb-8">
            <div>
                <p class="text-[10px] uppercase font-bold text-gray-500 mb-2 tracking-wider">Tanggal Transaksi</p>
                <p class="text-xs text-white font-medium"><?= date('d F Y', strtotime($trx['tanggal'])) ?></p>
            </div>
            <div>
                <p class="text-[10px] uppercase font-bold text-gray-500 mb-2 tracking-wider">Jatuh Tempo</p>
                <p class="text-xs text-white font-medium">
                    <?= !empty($trx['jatuh_tempo']) ? date('d F Y', strtotime($trx['jatuh_tempo'])) : '-' ?>
                </p>
            </div>
        </div>

        <!-- Table -->
        <div class="mb-6">
            <div class="grid grid-cols-12 gap-2 mb-3 px-2">
                <div class="col-span-1 text-[10px] font-bold text-gray-500 uppercase">#</div>
                <div class="col-span-8 text-[10px] font-bold text-gray-500 uppercase">Deskripsi</div>
                <div class="col-span-3 text-[10px] font-bold text-gray-500 uppercase text-right">Total</div>
            </div>

            <div class="border-t border-dashed border-white/10 py-3 px-2">
                <div class="grid grid-cols-12 gap-2 items-center">
                    <div class="col-span-1 text-xs font-bold text-gray-400">1</div>
                    <div class="col-span-8">
                        <span class="text-xs font-medium text-white truncate">
                            <?= !empty($trx['keterangan']) ? esc($trx['keterangan']) : 'Pembelian / Pengeluaran Operasional' ?>
                        </span>
                    </div>
                    <div class="col-span-3 text-right">
                        <span class="text-xs font-bold text-gray-300">Rp <?= number_format($trx['total'], 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
            <div class="border-b border-dashed border-white/10 mb-2 w-full"></div>
        </div>

        <!-- Totals -->
        <div class="space-y-2 mb-8 pl-12 md:pl-32">
            <div class="flex justify-between items-center text-xs">
                <span class="text-gray-400">SubTotal</span>
                <span class="font-bold text-white">Rp <?= number_format($trx['subtotal'], 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between items-center text-xs">
                <span class="text-gray-400">PPN (<?= intval($trx['ppn']) ?>%)</span>
                <span class="font-bold text-white">
                    Rp <?= number_format(($trx['subtotal'] * $trx['ppn']) / 100, 0, ',', '.') ?>
                </span>
            </div>
            <div class="flex justify-between items-center text-sm pt-2 border-t border-white/5">
                <span class="text-gray-200 font-bold">Total</span>
                <span class="font-bold text-white">Rp <?= number_format($trx['total'], 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Status Confirmation Box -->
        <?php if ($trx['status'] === 'lunas'): ?>
            <div class="bg-[#0f391b]/30 border border-[#33E818]/20 rounded-xl p-3 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-[#33E818] flex items-center justify-center shrink-0">
                    <i class="fas fa-check text-black text-sm"></i>
                </div>
                <div>
                    <p class="text-[#33E818] text-xs font-bold">Pengeluaran Lunas</p>
                    <p class="text-[10px] text-gray-400">Tercatat pada: <?= date('d F Y, H:i', strtotime($trx['created_at'] ?? $trx['tanggal'])) ?></p>
                </div>
            </div>
        <?php elseif ($trx['status'] === 'pending'): ?>
            <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-xl p-3 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-clock text-black text-sm"></i>
                </div>
                <div>
                    <p class="text-yellow-500 text-xs font-bold">Menunggu Pembayaran</p>
                    <p class="text-[10px] text-gray-400">Tanggal Jatuh Tempo: <?= !empty($trx['jatuh_tempo']) ? date('d F Y', strtotime($trx['jatuh_tempo'])) : '-' ?></p>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-3 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-times text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-red-500 text-xs font-bold">Transaksi Dibatalkan</p>
                    <p class="text-[10px] text-gray-400">Batal tercatat</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Footer Notes -->
        <div class="border-t border-dashed border-white/10 pt-4 mt-8 flex justify-between items-start">
            <div>
                <p class="text-[10px] font-bold text-gray-300 uppercase mb-1">NOTES</p>
                <p class="text-[10px] text-gray-500">Dicatat secara sistematis oleh Divisi Keuangan.</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-gray-300 uppercase mb-1">Butuh bantuan?</p>
                <p class="text-[10px] text-gray-500">finance@almai.id</p>
            </div>
        </div>

    </div>

</body>

</html>
