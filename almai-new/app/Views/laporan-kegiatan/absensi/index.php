<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Manajemen Absensi</h1>
            <p class="text-gray-400 text-sm">Kelola daftar absensi acara/kegiatan</p>
        </div>
        <a href="<?= base_url('laporan-kegiatan/absensi/create') ?>" class="px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
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

    <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden">
        <div class="p-6 border-b border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="font-bold">Daftar Kegiatan Absensi</h3>
            
            <form action="<?= base_url('laporan-kegiatan/absensi') ?>" method="GET" class="flex flex-col md:flex-row gap-3">
                <input type="text" name="q" value="<?= esc($filters['q'] ?? '') ?>" placeholder="Cari kegiatan..." class="bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-2 text-sm w-full md:w-64">
                
                <select name="tipe" class="bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-2 text-sm">
                    <option value="">Semua Tipe</option>
                    <option value="seminar_fgd" <?= ($filters['tipe'] ?? '') == 'seminar_fgd' ? 'selected' : '' ?>>Seminar FGD</option>
                    <option value="pelatihan_simulasi" <?= ($filters['tipe'] ?? '') == 'pelatihan_simulasi' ? 'selected' : '' ?>>Pelatihan Simulasi</option>
                    <option value="signals" <?= ($filters['tipe'] ?? '') == 'signals' ? 'selected' : '' ?>>Signal</option>
                    <option value="konsultasi" <?= ($filters['tipe'] ?? '') == 'konsultasi' ? 'selected' : '' ?>>Konsultasi</option>
                    <option value="expert_advisor" <?= ($filters['tipe'] ?? '') == 'expert_advisor' ? 'selected' : '' ?>>Expert Advisor</option>
                    <option value="kegiatan_lainnya" <?= ($filters['tipe'] ?? '') == 'kegiatan_lainnya' ? 'selected' : '' ?>>Kegiatan Lainnya</option>
                </select>

                <select name="wpa_id" class="bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-2 text-sm">
                    <option value="">Semua WPA</option>
                    <?php foreach ($wpa_list as $wpa): ?>
                        <option value="<?= $wpa['id'] ?>" <?= ($filters['wpa_id'] ?? '') == $wpa['id'] ? 'selected' : '' ?>><?= esc($wpa['name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="cwpa_id" class="bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-2 text-sm">
                    <option value="">Semua CWPA</option>
                    <?php foreach ($cwpa_list as $cwpa): ?>
                        <option value="<?= $cwpa['id'] ?>" <?= ($filters['cwpa_id'] ?? '') == $cwpa['id'] ? 'selected' : '' ?>><?= esc($cwpa['user_name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="px-4 py-2 bg-accent/20 text-accent font-medium rounded-lg hover:bg-accent/30 transition text-sm">Filter</button>
                <?php if(!empty($filters['q']) || !empty($filters['tipe']) || !empty($filters['wpa_id']) || !empty($filters['cwpa_id'])): ?>
                    <a href="<?= base_url('laporan-kegiatan/absensi') ?>" class="px-4 py-2 bg-white/5 text-white font-medium rounded-lg hover:bg-white/10 transition text-sm">Reset</a>
                <?php endif; ?>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-black/40 text-gray-400">
                    <tr>
                        <th class="px-6 py-4 font-medium">No</th>
                        <th class="px-6 py-4 font-medium">Kegiatan</th>
                        <th class="px-6 py-4 font-medium">Tipe</th>
                        <th class="px-6 py-4 font-medium">WPA</th>
                        <th class="px-6 py-4 font-medium">CWPA</th>
                        <th class="px-6 py-4 font-medium">Link Absensi</th>
                        <th class="px-6 py-4 font-medium">Tanggal</th>
                        <th class="px-6 py-4 font-medium">Dibuat Oleh</th>
                        <th class="px-6 py-4 font-medium">Tanggal Expired</th>
                        <th class="px-6 py-4 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php $no = 1; ?>
                    <?php if (empty($all_activities)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-3 block opacity-50"></i>
                                Belum ada data absensi
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($all_activities as $item): ?>
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-6 py-4 text-gray-400">
                                    <?= $no++ ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium"><?= esc($item['nama']) ?></div>
                                </td>
                                <td class="px-6 py-4 text-gray-400">
                                    <span class="px-2 py-1 bg-white/5 rounded text-xs"><?= esc($item['tipe']) ?></span>
                                </td>
                                <td class="px-6 py-4 text-gray-400 text-sm">
                                    <?php
                                        $wpaName = '-';
                                        if (!empty($item['wpa_id'])) {
                                            foreach($wpa_list as $w) {
                                                if ($w['id'] == $item['wpa_id']) {
                                                    $wpaName = $w['name'];
                                                    break;
                                                }
                                            }
                                        }
                                        echo esc($wpaName);
                                    ?>
                                </td>
                                <td class="px-6 py-4 text-gray-400 text-sm">
                                    <?php
                                        $cwpaName = '-';
                                        if (!empty($item['cwpa_id'])) {
                                            foreach($cwpa_list as $c) {
                                                if ($c['id'] == $item['cwpa_id']) {
                                                    $cwpaName = $c['user_name'];
                                                    break;
                                                }
                                            }
                                        }
                                        echo esc($cwpaName);
                                    ?>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="<?= esc($item['link_absensi']) ?>" target="_blank" class="text-xs text-blue-400 hover:text-blue-300 hover:underline truncate block max-w-[200px]" title="<?= esc($item['link_absensi']) ?>">
                                        <?= esc($item['link_absensi']) ?>
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-400 whitespace-nowrap">
                                    <?= date('d M Y', strtotime($item['tanggal'] ?? $item['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 text-gray-400">
                                    <?php
                                        $creator = '-';
                                        if (!empty($item['created_by_admin_id'])) {
                                            $userModel = new \App\Models\UserModel();
                                            $admin = $userModel->find($item['created_by_admin_id']);
                                            $creator = $admin ? '<span class="text-xs bg-red-500/20 text-red-400 px-2 py-1 rounded">Admin: ' . esc($admin['name']) . '</span>' : 'Admin';
                                        } elseif (!empty($item['created_by_wpa_id'])) {
                                            $wpaModel = new \App\Models\WpaModel();
                                            $wpa = $wpaModel->find($item['created_by_wpa_id']);
                                            $creator = $wpa ? '<span class="text-xs bg-blue-500/20 text-blue-400 px-2 py-1 rounded">WPA: ' . esc($wpa['name']) . '</span>' : 'WPA';
                                        } elseif (!empty($item['created_by_cwpa_id'])) {
                                            $cwpaModel = new \App\Models\CwpaModel();
                                            $cwpa = $cwpaModel->select('users.name')->join('users', 'users.id = cwpa.user_id')->where('cwpa.id', $item['created_by_cwpa_id'])->first();
                                            $creator = $cwpa ? '<span class="text-xs bg-green-500/20 text-green-400 px-2 py-1 rounded">CWPA: ' . esc($cwpa['name']) . '</span>' : 'CWPA';
                                        }
                                        echo $creator;
                                    ?>
                                </td>
                                <td class="px-6 py-4 text-gray-400">
                                    <?= !empty($item['expired_link_kode_qr']) ? date('d M Y, H:i', strtotime($item['expired_link_kode_qr'])) : '-' ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="<?= base_url('laporan-kegiatan/absensi/detail/' . $item['kegiatan_type_id'] . '/' . $item['id']) ?>" class="p-2 text-green-400 hover:bg-green-400/10 rounded transition flex items-center gap-2" title="Detail">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <button type="button" onclick="showQR('<?= esc($item['kode_qr']) ?>', '<?= esc($item['link_absensi']) ?>')" class="p-2 text-blue-400 hover:bg-blue-400/10 rounded transition flex items-center gap-2" title="Lihat & Download QR">
                                            <i class="fas fa-qrcode"></i> QR
                                        </button>
                                        <a href="<?= base_url('laporan-kegiatan/absensi/edit/' . $item['kegiatan_type_id'] . '/' . $item['id']) ?>" class="p-2 text-yellow-400 hover:bg-yellow-400/10 rounded transition flex items-center gap-2" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
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

