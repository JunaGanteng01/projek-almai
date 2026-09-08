<?= $this->extend('cwpa/layouts/main') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Absensi Kegiatan</h1>
            <p class="text-gray-400 text-sm">Daftar absensi kegiatan Anda</p>
        </div>
        <a href="<?= base_url('cwpa/dashboard/absensi/create') ?>" class="px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center justify-center gap-2">
            <i class="fas fa-plus"></i> Buat Absensi
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-xl flex items-start gap-3">
            <i class="fas fa-check-circle mt-0.5"></i>
            <div>
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl flex items-start gap-3">
            <i class="fas fa-exclamation-circle mt-0.5"></i>
            <div>
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden">
        <div class="p-6 border-b border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="font-bold">Daftar Kegiatan Absensi</h3>

            <form action="<?= base_url('cwpa/dashboard/absensi') ?>" method="GET" class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                <input type="text" name="q" value="<?= esc($filters['q'] ?? '') ?>" placeholder="Cari kegiatan..." class="bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-2 text-sm w-full md:w-64">
                
                <select name="tipe" class="bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-2 text-sm w-full md:w-48">
                    <option value="">Semua Tipe</option>
                    <option value="seminar_fgd" <?= ($filters['tipe'] ?? '') == 'seminar_fgd' ? 'selected' : '' ?>>Seminar FGD</option>
                    <option value="pelatihan_simulasi" <?= ($filters['tipe'] ?? '') == 'pelatihan_simulasi' ? 'selected' : '' ?>>Pelatihan Simulasi</option>
                    <option value="signals" <?= ($filters['tipe'] ?? '') == 'signals' ? 'selected' : '' ?>>Signal</option>
                    <option value="konsultasi" <?= ($filters['tipe'] ?? '') == 'konsultasi' ? 'selected' : '' ?>>Konsultasi</option>
                    <option value="expert_advisor" <?= ($filters['tipe'] ?? '') == 'expert_advisor' ? 'selected' : '' ?>>Expert Advisor</option>
                    <option value="kegiatan_lainnya" <?= ($filters['tipe'] ?? '') == 'kegiatan_lainnya' ? 'selected' : '' ?>>Kegiatan Lainnya</option>
                </select>

                <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-accent/20 text-accent font-medium rounded-lg hover:bg-accent/30 transition text-sm">Filter</button>
                    <?php if(!empty($filters['q']) || !empty($filters['tipe'])): ?>
                        <a href="<?= base_url('cwpa/dashboard/absensi') ?>" class="w-full sm:w-auto px-4 py-2 bg-white/5 text-white font-medium rounded-lg hover:bg-white/10 transition text-sm text-center">Reset</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-black/40 text-gray-400 whitespace-nowrap">
                    <tr>
                        <th class="px-6 py-4 font-medium">No</th>
                        <th class="px-6 py-4 font-medium">Kegiatan</th>
                        <th class="px-6 py-4 font-medium">Tipe</th>
                        <th class="px-6 py-4 font-medium">Kode QR</th>
                        <th class="px-6 py-4 font-medium">Link Absensi</th>
                        <th class="px-6 py-4 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php $no = 1; ?>
                    
                    <?php if (empty($all_activities)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-3 block opacity-50"></i>
                                Belum ada data absensi
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($all_activities as $item): ?>
                            <tr class="hover:bg-white/5 transition whitespace-nowrap">
                                <td class="px-6 py-4 text-gray-400">
                                    <?= $no++ ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium"><?= esc($item['nama']) ?></div>
                                    <div class="text-[10px] text-gray-500"><?= date('d M Y', strtotime($item['tanggal'] ?? $item['created_at'])) ?></div>
                                </td>
                                <td class="px-6 py-4 text-gray-400">
                                    <span class="px-2 py-1 bg-white/5 rounded text-xs"><?= esc($item['tipe']) ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-mono text-xs text-accent"><?= esc($item['kode_qr']) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="<?= esc($item['link_absensi']) ?>" target="_blank" class="text-xs text-blue-400 hover:text-blue-300 hover:underline truncate block max-w-[200px]" title="<?= esc($item['link_absensi']) ?>">
                                        <?= esc($item['link_absensi']) ?>
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="<?= base_url('cwpa/dashboard/absensi/detail/' . $item['kegiatan_type_id'] . '/' . $item['id']) ?>" class="p-2 text-green-400 hover:bg-green-400/10 rounded transition flex items-center gap-2" title="Detail">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <button type="button" onclick="showQR('<?= esc($item['kode_qr']) ?>', '<?= esc($item['link_absensi']) ?>')" class="p-2 text-blue-400 hover:bg-blue-400/10 rounded transition flex items-center gap-2" title="Lihat & Download QR">
                                            <i class="fas fa-qrcode"></i> QR
                                        </button>
                                        <a href="<?= base_url('cwpa/dashboard/absensi/delete/' . urlencode($item['tipe']) . '/' . $item['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data absensi ini?')" class="p-2 text-red-400 hover:bg-red-400/10 rounded transition flex items-center gap-2" title="Hapus">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal QR Code -->
<div id="qrModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeQR()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 text-center shadow-2xl">
            <h3 class="font-bold text-lg mb-4">Kode QR Absensi</h3>
            <div class="bg-white p-4 rounded-xl inline-block mb-4" id="qrcodeContainer"></div>
            
            <div class="flex gap-2 justify-center mb-6">
                <button onclick="downloadQR('png')" class="px-4 py-2 bg-accent/20 hover:bg-accent/30 text-accent text-xs font-bold rounded-lg transition border border-accent/30 flex items-center gap-2">
                    <i class="fas fa-download"></i> PNG
                </button>
                <button onclick="downloadQR('jpeg')" class="px-4 py-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 text-xs font-bold rounded-lg transition border border-blue-500/30 flex items-center gap-2">
                    <i class="fas fa-download"></i> JPG
                </button>
            </div>

            <div class="mb-6">
                <p class="text-sm text-gray-400 mb-2">Atau bagikan link berikut:</p>
                <div class="flex items-center gap-2 bg-[#0a0a0a] border border-white/10 p-2 rounded-lg">
                    <input type="text" id="qrLinkInput" class="w-full bg-transparent text-xs text-gray-400 outline-none" readonly>
                    <button type="button" onclick="copyLink()" class="p-2 text-accent hover:bg-accent/10 rounded transition">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>
            <button type="button" onclick="closeQR()" class="w-full py-3 bg-white/5 hover:bg-white/10 rounded-xl font-bold transition">Tutup</button>
        </div>
    </div>
</div>

<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script>
    let qrcode = null;
    let currentKodeQR = '';

    function showQR(kode, link) {
        currentKodeQR = kode;
        document.getElementById('qrModal').classList.remove('hidden');
        document.getElementById('qrLinkInput').value = link;
        
        const container = document.getElementById('qrcodeContainer');
        container.innerHTML = ''; // Clear previous
        
        qrcode = new QRCode(container, {
            text: link,
            width: 250,
            height: 250,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    }

    function downloadQR(format) {
        const container = document.getElementById('qrcodeContainer');
        const canvas = container.querySelector('canvas');
        if (!canvas) {
            alert('QR Code belum di-generate sempurna.');
            return;
        }

        // Get image URL from canvas
        const image = canvas.toDataURL(`image/${format}`);
        
        // Create temporary link and trigger download
        const link = document.createElement('a');
        link.href = image;
        link.download = `QR-Absensi-${currentKodeQR}.${format === 'jpeg' ? 'jpg' : 'png'}`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function closeQR() {
        document.getElementById('qrModal').classList.add('hidden');
    }

    function copyLink() {
        const input = document.getElementById('qrLinkInput');
        input.select();
        document.execCommand('copy');
        alert('Link berhasil disalin!');
    }
</script>

<?= $this->endSection() ?>
