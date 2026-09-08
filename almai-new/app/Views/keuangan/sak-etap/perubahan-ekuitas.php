<?php
/**
 * @var array $perubahanEkuitas
 * @var string|int $tahunBuku
 * @var string $bulanBuku
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-exchange-alt"></i> Laporan Perubahan Ekuitas (Statement of Changes in Equity)
                    </h1>
                    <p class="text-muted">Untuk Tahun yang Berakhir <?= date('d M Y', strtotime($perubahanEkuitas['periode']['end_date'])) ?></p>
                </div>
                <div>
                    <a href="<?= base_url('keuangan/sak-etap') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="<?= base_url('keuangan/sak-etap/perubahan-ekuitas?tahun=' . $tahunBuku . '&bulan=' . $bulanBuku . '&format=pdf') ?>" class="btn btn-danger" target="_blank">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="get" class="row g-3">
                        <div class="col-md-3">
                            <label for="tahun" class="form-label">Tahun Buku</label>
                            <input type="number" class="form-control" id="tahun" name="tahun" value="<?= $tahunBuku ?>" min="2020" max="<?= date('Y') + 1 ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select class="form-select" id="bulan" name="bulan">
                                <option value="all" <?= $bulanBuku == 'all' ? 'selected' : '' ?>>Semua Bulan (Tahunan)</option>
                                <?php 
                                $namaBulan = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                                for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= $bulanBuku == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' ?>>
                                        <?= $namaBulan[$i] ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Perubahan Ekuitas Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Keterangan</th>
                                <th class="text-end">Jumlah (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ekuitas Awal Periode</td>
                                <td class="text-end"><?= number_format($perubahanEkuitas['ekuitas_awal_periode'], 0, ',', '.') ?></td>
                            </tr>
                            <tr class="table-light">
                                <td>Laba/Rugi Tahun Berjalan</td>
                                <td class="text-end"><?= number_format($perubahanEkuitas['laba_rugi_tahun_berjalan'], 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td>Tambahan Modal Disetor</td>
                                <td class="text-end"><?= number_format($perubahanEkuitas['tambahan_modal'], 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td>Dividen/Prive</td>
                                <td class="text-end">(<?= number_format($perubahanEkuitas['dividen'], 0, ',', '.') ?>)</td>
                            </tr>
                            <tr class="table-warning fw-bold">
                                <td>Ekuitas Akhir Periode</td>
                                <td class="text-end"><?= number_format($perubahanEkuitas['ekuitas_akhir_periode'], 0, ',', '.') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ringkasan Perubahan Ekuitas</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted mb-2">Ekuitas Awal</p>
                            <p class="h6">Rp <?= number_format($perubahanEkuitas['ekuitas_awal_periode'], 0, ',', '.') ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-2">Ekuitas Akhir</p>
                            <p class="h6">Rp <?= number_format($perubahanEkuitas['ekuitas_akhir_periode'], 0, ',', '.') ?></p>
                        </div>
                    </div>
                    <hr>
                    <p class="text-muted mb-2">Perubahan Ekuitas</p>
                    <p class="h6 <?= ($perubahanEkuitas['ekuitas_akhir_periode'] - $perubahanEkuitas['ekuitas_awal_periode']) >= 0 ? 'text-success' : 'text-danger' ?>">
                        Rp <?= number_format($perubahanEkuitas['ekuitas_akhir_periode'] - $perubahanEkuitas['ekuitas_awal_periode'], 0, ',', '.') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
