<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan - <?= esc($penjualan['nomor_tagihan']) ?></title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- html2pdf.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        accent: '#33E818',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        title: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Responsive sheet styling with beautiful dark theme - Map 1-to-1 to A4 Point size */
        #invoice-sheet {
            width: 595pt;
            min-height: 842pt;
            box-sizing: border-box;
            background-color: #111111 !important;
            position: relative;
        }
        .pdf-bg {
            background-color: #111111 !important;
            color: #d1d5db !important;
        }
        .pdf-table-header {
            background-color: rgba(255, 255, 255, 0.04) !important;
        }
        .pdf-border {
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        .pdf-accent-text {
            color: #33E818 !important;
        }

        /* High-Fidelity Light Mode Overrides for Invoice Sheet */
        #invoice-sheet.light-mode {
            background-color: #ffffff !important;
            border-color: rgba(0, 0, 0, 0.12) !important;
            color: #1f2937 !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08) !important;
        }
        #invoice-sheet.light-mode .pdf-bg {
            background-color: #ffffff !important;
            color: #1f2937 !important;
        }
        #invoice-sheet.light-mode .pdf-border,
        #invoice-sheet.light-mode .border-white\/10,
        #invoice-sheet.light-mode .border-white\/5,
        #invoice-sheet.light-mode .border-r {
            border-color: rgba(0, 0, 0, 0.1) !important;
        }
        #invoice-sheet.light-mode .pdf-table-header,
        #invoice-sheet.light-mode .bg-white\/5 {
            background-color: #f8fafc !important;
        }
        #invoice-sheet.light-mode .pdf-accent-text,
        #invoice-sheet.light-mode .text-accent {
            color: #16a34a !important;
        }
        #invoice-sheet.light-mode .text-white {
            color: #0f172a !important;
        }
        #invoice-sheet.light-mode .text-gray-300,
        #invoice-sheet.light-mode .text-gray-400 {
            color: #374151 !important;
        }
        #invoice-sheet.light-mode .text-gray-500,
        #invoice-sheet.light-mode .text-gray-600 {
            color: #6b7280 !important;
        }
        #invoice-sheet.light-mode .print-digital-stamp {
            background-color: #f8fafc !important;
            border-color: rgba(0, 0, 0, 0.08) !important;
            color: #1f2937 !important;
        }
        #invoice-sheet.light-mode img {
            filter: invert(1) hue-rotate(180deg) !important;
        }

        /* Native Browser Print Specific Styles (Enforcing identical dark theme graphics) */
        @media print {
            body, html {
                background-color: #0b0b0b !important;
                color: #d1d5db !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .print-card {
                background-color: #111111 !important;
                color: #d1d5db !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                border-radius: 24px !important;
                padding: 48px !important;
                box-shadow: none !important;
                max-width: 100% !important;
                margin: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .print-border {
                border-color: rgba(255, 255, 255, 0.08) !important;
            }
            .print-table-header {
                background-color: rgba(255, 255, 255, 0.04) !important;
                color: #d1d5db !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .print-text-black {
                color: #ffffff !important;
            }
            .print-text-gray {
                color: #9ca3af !important;
            }
            .print-text-accent {
                color: #33E818 !important;
            }
            .print-digital-stamp {
                background-color: rgba(255, 255, 255, 0.05) !important;
                border-color: rgba(255, 255, 255, 0.05) !important;
                color: #d1d5db !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body class="bg-[#0b0b0b] text-gray-300 font-sans min-h-screen py-10 px-4 flex flex-col items-center">

    <!-- Action Header (No Print) -->
    <div class="max-w-[595pt] w-full mb-6 flex flex-col sm:flex-row items-center justify-between gap-4 no-print bg-[#111] border border-white/10 rounded-2xl p-4 shadow-xl">
        <div class="flex items-center gap-3">
            <a href="<?= base_url('keuangan/penjualan') ?>" class="w-10 h-10 bg-white/5 hover:bg-white/10 rounded-xl flex items-center justify-center text-white transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="font-bold text-white text-sm">Penjualan / Invoice</h4>
                <p class="text-xs text-gray-400 font-mono"><?= esc($penjualan['nomor_tagihan']) ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <!-- Theme Toggle Button -->
            <button id="btn-theme-toggle" onclick="toggleTheme()" class="px-4 py-2.5 bg-white/5 hover:bg-white/10 text-white font-bold rounded-xl transition flex items-center gap-2 text-sm border border-white/10">
                <i id="theme-icon" class="fas fa-moon text-yellow-400"></i> <span id="theme-text">Mode Gelap</span>
            </button>
            <!-- Direct Premium Download -->
            <button id="btn-download" onclick="downloadPDF()" class="px-6 py-2.5 bg-accent hover:bg-white text-black font-extrabold rounded-xl transition flex items-center gap-2 shadow-lg shadow-accent/15 text-sm">
                <i class="fas fa-file-pdf"></i> Unduh PDF
            </button>
        </div>
    </div>

    <!-- Responsive Sheet Wrapper for Mobile (Allows touch horizontal panning for exact A4 layout) -->
    <div class="w-full max-w-[595pt] overflow-x-auto pb-4 no-print scrollbar-thin">
        <!-- Invoice Sheet (Pixel Perfect Capture & Print Area) -->
        <div id="invoice-sheet" class="shadow-2xl pdf-bg border border-white/10 rounded-3xl p-8 md:p-12 overflow-hidden flex flex-col gap-6 print-card print:p-0 print:border-none print:shadow-none">
        
        <!-- Sheet Header -->
        <div class="flex flex-col md:flex-row justify-between gap-6 pb-6 border-b border-white/10 pdf-border print-border">
            <!-- Left Info (PT. Alma Indonesia Raya, Denpasar) -->
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <?php
                    $logoPath = FCPATH . 'images/almai-full.png';
                    $logoSrc = '/images/almai-full.png';
                    if (file_exists($logoPath)) {
                        $logoData = base64_encode(file_get_contents($logoPath));
                        $logoSrc = 'data:image/png;base64,' . $logoData;
                    }
                    ?>
                    <img src="<?= $logoSrc ?>" alt="ALMAI" class="h-10 print:invert">
                </div>
                <div class="text-xs text-gray-400 space-y-0.5 print-text-gray">
                    <p class="font-bold text-white text-base tracking-tight mb-1 print-text-black">PT. Alma Indonesia Raya</p>
                    <p class="text-gray-300 print-text-black">Jl. Badak Agung No. 22 Kav. 3, Renon, Denpasar - Bali. 80226</p>
                    <p class="text-gray-300 print-text-black">Telf/Fax : 03613610019</p>
                    <p class="text-gray-300 print-text-black">Chat Support : 085183231800, 085183390019</p>
                </div>
            </div>

            <!-- Right Info (Invoice Details) -->
            <div class="md:text-right space-y-1.5 print-text-black">
                <h1 class="text-4xl font-extrabold text-white tracking-tight font-title print-text-black">PENJUALAN</h1>
                <div class="space-y-0.5 font-mono text-xs text-gray-400 print-text-black">
                    <p class="font-bold text-sm pdf-accent-text print-text-accent">No: <?= esc($penjualan['nomor_tagihan']) ?></p>
                    <p>Tanggal: <?= date('d M Y', strtotime($penjualan['tanggal_transaksi'])) ?></p>
                    <?php if (!empty($penjualan['tanggal_jatuh_tempo'])): ?>
                        <p class="text-red-400 font-bold print-text-black">Jatuh Tempo: <?= date('d M Y', strtotime($penjualan['tanggal_jatuh_tempo'])) ?></p>
                    <?php endif; ?>
                    <p>Status:
                        <?php if ($penjualan['status_pembayaran'] === 'lunas'): ?>
                            <span class="text-green-400 font-bold uppercase print-text-black">[ LUNAS ]</span>
                        <?php else: ?>
                            <span class="text-yellow-400 font-bold uppercase print-text-black">[ BELUM BAYAR ]</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Billing details -->
        <div class="py-2 print-text-black">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 print-text-gray">Ditagihkan Kepada (Bill To):</p>
            <div class="space-y-0.5">
                <h3 class="text-base font-bold text-white print-text-black"><?= esc($penjualan['nama_kontak']) ?></h3>
                <?php if ($penjualan['email']): ?>
                    <p class="text-xs text-gray-400 print-text-black"><i class="far fa-envelope mr-1.5 opacity-60"></i><?= esc($penjualan['email']) ?></p>
                <?php endif; ?>
                <?php if ($penjualan['alamat']): ?>
                    <p class="text-xs text-gray-400 print-text-black"><i class="fas fa-map-marker-alt mr-1.5 opacity-60"></i><?= esc($penjualan['alamat']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Invoice Table -->
        <div class="border border-white/10 rounded-xl overflow-hidden pdf-border print-border">
            <table class="w-full">
                <thead>
                    <tr class="pdf-table-header border-b border-white/10 pdf-border text-xs print-table-header print-border">
                        <th class="px-5 py-2.5 text-center font-bold text-gray-400 uppercase tracking-wider w-12 print-text-black">No</th>
                        <th class="px-5 py-2.5 text-left font-bold text-gray-400 uppercase tracking-wider print-text-black">Deskripsi Produk / Layanan Jasa</th>
                        <th class="px-5 py-2.5 text-center font-bold text-gray-400 uppercase tracking-wider w-16 print-text-black">Qty</th>
                        <th class="px-5 py-2.5 text-right font-bold text-gray-400 uppercase tracking-wider w-32 print-text-black">Harga</th>
                        <th class="px-5 py-2.5 text-right font-bold text-gray-400 uppercase tracking-wider w-36 print-text-black">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 pdf-border text-xs print-border print-text-black">
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $idx => $item): ?>
                            <tr class="hover:bg-white/5 transition print:hover:bg-transparent">
                                <td class="px-5 py-2 text-sm font-mono text-gray-500 text-center print-text-black"><?= sprintf('%02d', $idx + 1) ?></td>
                                <td class="px-5 py-2 text-sm">
                                    <p class="font-bold text-white print:text-black"><?= esc($item['produk']) ?></p>
                                    <?php if (!empty($item['deskripsi'])): ?>
                                        <p class="text-xs text-gray-500 mt-1 print-text-black"><?= esc($item['deskripsi']) ?></p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-2 text-center font-mono text-gray-400 print-text-black">
                                    <?= number_format($item['qty'], 0) ?> <?= esc($item['satuan'] ?? 'pcs') ?>
                                </td>
                                <td class="px-5 py-2 text-right font-mono text-gray-400 print-text-black">
                                    Rp <?= number_format($item['harga'], 0, ',', '.') ?>
                                </td>
                                <td class="px-5 py-2 text-right font-bold text-white font-mono print:text-black">
                                    Rp <?= number_format($item['jumlah'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-5 py-4 text-center text-gray-500 italic">Tidak ada item</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Summary & Math Section (Aligned perfectly side-by-side) -->
        <div class="space-y-4">
            <!-- Notes & Terms (Full Width) -->
            <?php if ($penjualan['catatan']): ?>
                <div class="text-xs text-gray-400 space-y-1 print-text-black">
                    <p class="font-bold text-white print:text-black">Catatan Tambahan:</p>
                    <p class="italic leading-relaxed print-text-black"><?= nl2br(esc($penjualan['catatan'])) ?></p>
                </div>
            <?php endif; ?>

            <!-- Bottom Grid Area (Payment details & Subtotal perfectly aligned) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">
                <!-- Left Column: Bank Transfer Details -->
                <div class="p-4 bg-white/5 border border-white/10 rounded-xl pdf-border flex flex-col justify-between h-full print-border print:bg-transparent">
                    <div>
                        <p class="text-[10px] font-bold text-accent pdf-accent-text uppercase tracking-wider mb-2 flex items-center gap-1 print-text-accent">
                            <i class="fas fa-university"></i> Rekening Pembayaran (A.N. PT. Alma Indonesia Raya):
                        </p>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-[10px] text-gray-300 print-text-black">
                            <!-- Bank Mandiri -->
                            <div class="border-r border-white/5 pr-2 print-border">
                                <p class="font-bold text-white print:text-black">Bank Mandiri</p>
                                <p class="font-mono font-semibold pdf-accent-text text-xs print-text-accent">1450050070008</p>
                            </div>
                            <!-- Bank BCA -->
                            <div>
                                <p class="font-bold text-white print:text-black">BCA</p>
                                <p class="font-mono font-semibold pdf-accent-text text-xs print-text-accent">0403503859</p>
                            </div>
                            <!-- Bank BRI -->
                            <div class="border-r border-white/5 pr-2 pt-1 print-border">
                                <p class="font-bold text-white print:text-black">BRI</p>
                                <p class="font-mono font-semibold pdf-accent-text text-xs print-text-accent">055601002105306</p>
                            </div>
                            <!-- Bank Danamon -->
                            <div class="pt-1">
                                <p class="font-bold text-white print:text-black">Danamon</p>
                                <p class="font-mono font-semibold pdf-accent-text text-xs print-text-accent">003666257013</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Mathematical Breakdown -->
                <div class="bg-white/5 border border-white/10 rounded-xl p-5 space-y-3.5 pdf-border flex flex-col justify-between h-full print-border print:bg-transparent">
                    <div class="space-y-3.5">
                        <div class="flex justify-between text-xs text-gray-400 font-medium print-text-black">
                            <span>Subtotal:</span>
                            <span class="font-mono">Rp <?= number_format($penjualan['subtotal'] ?? 0, 0, ',', '.') ?></span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-400 font-medium print-text-black">
                            <span>PPN (<?= number_format($penjualan['ppn'], 0) ?>%):</span>
                            <span class="font-mono">Rp <?= number_format(($penjualan['subtotal'] ?? 0) * ($penjualan['ppn'] / 100), 0, ',', '.') ?></span>
                        </div>
                    </div>
                    <div class="border-t border-white/10 pdf-border pt-2.5 flex justify-between items-center print-border">
                        <span class="text-sm font-bold text-white print:text-black">Total Tagihan:</span>
                        <span class="text-lg font-black pdf-accent-text font-mono print-text-accent">Rp <?= number_format($penjualan['total'] ?? 0, 0, ',', '.') ?></span>
                    </div>
                </div>
        </div>

        <!-- Dynamic spacer to push signatures beautifully to the bottom of A4 paper -->
        <div class="flex-grow"></div>

        <!-- Footer Signatures with Secure Digital Timestamps -->
        <div class="grid grid-cols-2 gap-6 pt-6 border-t border-white/10 text-center pdf-border print-border">
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1 print-text-gray">Penerima (Customer)</p>
                <div class="text-[10px] text-gray-400 font-mono leading-tight bg-white/5 p-2 rounded-lg border border-white/5 inline-block text-left min-w-[200px] print-digital-stamp">
                    <span class="text-accent font-bold print-text-accent"><i class="fas fa-check-circle mr-1"></i> Digitally Signed</span><br>
                    <span class="text-gray-500 print-text-gray">Name:</span> <?= esc(substr($penjualan['nama_kontak'], 0, 20)) ?><br>
                    <span class="text-gray-500 print-text-gray">Date:</span> <?= date('Y-m-d H:i') ?> WITA
                </div>
                <p class="text-xs font-semibold text-gray-300 mt-2 print:text-black"><?= esc($penjualan['nama_kontak']) ?></p>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1 print-text-gray">Hormat Kami (PT. Alma Indonesia Raya)</p>
                <div class="text-[10px] text-gray-400 font-mono leading-tight bg-white/5 p-2 rounded-lg border border-white/5 inline-block text-left min-w-[200px] print-digital-stamp">
                    <span class="text-accent font-bold print-text-accent"><i class="fas fa-shield-alt mr-1"></i> Secured by System</span><br>
                    <span class="text-gray-500 print-text-gray">Ref:</span> ALMAI-SEC-<?= strtoupper(substr(md5($penjualan['nomor_tagihan']), 0, 8)) ?><br>
                    <span class="text-gray-500 print-text-gray">Date:</span> <?= date('Y-m-d H:i', strtotime($penjualan['created_at'] ?: 'now')) ?> WITA
                </div>
                <p class="text-xs font-semibold text-white mt-2 print:text-black">Accounting Dept</p>
            </div>
        </div>
        
        </div>
    </div>

    <!-- Small watermark on web screen -->
    <div class="mt-8 text-center text-xs text-gray-600 no-print">
        <p>&copy; <?= date('Y') ?> PT. Alma Indonesia Raya. All Rights Reserved.</p>
    </div>

    <script>
        // Initialize Theme from Local Storage
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('invoice-theme');
            if (savedTheme === 'light') {
                toggleTheme(true);
            }
        });

        function toggleTheme(forceLight = false) {
            const sheet = document.getElementById('invoice-sheet');
            const icon = document.getElementById('theme-icon');
            const text = document.getElementById('theme-text');
            
            const makeLight = forceLight || !sheet.classList.contains('light-mode');
            
            if (makeLight) {
                sheet.classList.add('light-mode');
                if (icon) icon.className = 'fas fa-sun text-orange-400';
                if (text) text.innerText = 'Mode Terang';
                localStorage.setItem('invoice-theme', 'light');
            } else {
                sheet.classList.remove('light-mode');
                if (icon) icon.className = 'fas fa-moon text-yellow-400';
                if (text) text.innerText = 'Mode Gelap';
                localStorage.setItem('invoice-theme', 'dark');
            }
        }

        function downloadPDF() {
            const element = document.getElementById('invoice-sheet');
            const isLight = element.classList.contains('light-mode');
            const bgColor = isLight ? '#ffffff' : '#111111';
            
            // Handle logo image source swap to real black pixels in Light Mode to bypass html2canvas CSS filter limitations
            const logoImg = element.querySelector('img');
            const originalSrc = logoImg ? logoImg.src : '';
            let hasSwapped = false;
            
            if (isLight && logoImg && originalSrc) {
                try {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.width = logoImg.naturalWidth || logoImg.width || 200;
                    canvas.height = logoImg.naturalHeight || logoImg.height || 50;
                    
                    ctx.drawImage(logoImg, 0, 0, canvas.width, canvas.height);
                    const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const data = imgData.data;
                    
                    // Invert near-white pixels (the text "Almai") to beautiful deep charcoal, leaving other colors (green leaf) intact
                    for (let i = 0; i < data.length; i += 4) {
                        let r = data[i];
                        let g = data[i+1];
                        let b = data[i+2];
                        let a = data[i+3];
                        
                        if (a > 0 && r > 200 && g > 200 && b > 200) {
                            data[i] = 15;     // Deep charcoal black
                            data[i+1] = 23;
                            data[i+2] = 42;
                        }
                    }
                    ctx.putImageData(imgData, 0, 0);
                    logoImg.src = canvas.toDataURL();
                    hasSwapped = true;
                } catch (e) {
                    console.error('Failed to bake light-mode logo pixels for PDF rendering:', e);
                }
            }
            
            // Map point-to-point (595pt x 842pt) to match standard A4 perfectly for a 1-page borderless dark experience
            const opt = {
                margin:       0,
                filename:     'Penjualan-' + '<?= str_replace('/', '_', esc($penjualan['nomor_tagihan'])) ?>' + '.pdf',
                image:        { type: 'jpeg', quality: 1.0 },
                html2canvas:  { scale: 2.5, useCORS: true, backgroundColor: bgColor, logging: false },
                jsPDF:        { unit: 'pt', format: 'a4', orientation: 'portrait' }
            };
            
            // Show loading state
            const btn = document.getElementById('btn-download');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengunduh PDF...';
            btn.disabled = true;

            html2pdf().set(opt).from(element).save().then(() => {
                if (hasSwapped) logoImg.src = originalSrc;
                btn.innerHTML = originalText;
                btn.disabled = false;
            }).catch(err => {
                if (hasSwapped) logoImg.src = originalSrc;
                console.error('PDF Download Error:', err);
                alert('Terjadi kesalahan saat mengunduh PDF.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>

