<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-balance-scale"></i> Neraca (Balance Sheet)
                    </h1>
                    <p class="text-muted">Per <?= date('d M Y', strtotime($neraca['periode']['end_date'])) ?></p>
                </div>
                <div>
                    <a href="<?= base_url('keuangan/sak-etap') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="<?= base_url('keuangan/sak-etap/neraca?tahun=' . $tahunBuku . '&bulan=' . $bulanBuku . '&format=pdf') ?>" class="btn btn-danger" target="_blank">
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
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= $bulanBuku == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' ?>>
                                        <?= strftime('%B', mktime(0, 0, 0, $i, 1)) ?>
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

    <!-- Neraca Table -->
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
                            <!-- ASET -->
                            <tr class="table-secondary fw-bold">
                                <td colspan="2">ASET</td>
                            </tr>

                            <!-- Aset Lancar -->
                            <tr class="table-light fw-bold">
                                <td>Aset Lancar</td>
                                <td></td>
                            </tr>
                            <?php foreach ($neraca['aset']['aset_lancar']['detail'] as $akun): ?>
                                <tr>
                                    <td>&nbsp;&nbsp;<?= $akun['nama_akun'] ?></td>
                                    <td class="text-end"><?= number_format($akun['saldo'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="table-light fw-bold">
                                <td>Total Aset Lancar</td>
                                <td class="text-end"><?= number_format($neraca['aset']['aset_lancar']['total'], 0, ',', '.') ?></td>
                            </tr>

                            <!-- Aset Tidak Lancar -->
                            <tr class="table-light fw-bold">
                                <td>Aset Tidak Lancar</td>
                                <td></td>
                            </tr>
                            <?php foreach ($neraca['aset']['aset_tidak_lancar']['detail'] as $akun): ?>
                                <tr>
                                    <td>&nbsp;&nbsp;<?= $akun['nama_akun'] ?></td>
                                    <td class="text-end"><?= number_format($akun['saldo'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="table-light fw-bold">
                                <td>Total Aset Tidak Lancar</td>
                                <td class="text-end"><?= number_format($neraca['aset']['aset_tidak_lancar']['total'], 0, ',', '.') ?></td>
                            </tr>

                            <!-- Total Aset -->
                            <tr class="table-warning fw-bold">
                                <td>TOTAL ASET</td>
                                <td class="text-end"><?= number_format($neraca['aset']['total_aset'], 0, ',', '.') ?></td>
                            </tr>

                            <!-- LIABILITAS & EKUITAS -->
                            <tr class="table-secondary fw-bold">
                                <td colspan="2">LIABILITAS & EKUITAS</td>
                            </tr>

                            <!-- Liabilitas Jangka Pendek -->
                            <tr class="table-light fw-bold">
                                <td>Liabilitas Jangka Pendek</td>
                                <td></td>
                            </tr>
                            <?php foreach ($neraca['liabilitas']['liabilitas_jangka_pendek']['detail'] as $akun): ?>
                                <tr>
                                    <td>&nbsp;&nbsp;<?= $akun['nama_akun'] ?></td>
                                    <td class="text-end"><?= number_format($akun['saldo'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="table-light fw-bold">
                                <td>Total Liabilitas Jangka Pendek</td>
                                <td class="text-end"><?= number_format($neraca['liabilitas']['liabilitas_jangka_pendek']['total'], 0, ',', '.') ?></td>
                            </tr>

                            <!-- Liabilitas Jangka Panjang -->
                            <tr class="table-light fw-bold">
                                <td>Liabilitas Jangka Panjang</td>
                                <td></td>
                            </tr>
                            <?php foreach ($neraca['liabilitas']['liabilitas_jangka_panjang']['detail'] as $akun): ?>
                                <tr>
                                    <td>&nbsp;&nbsp;<?= $akun['nama_akun'] ?></td>
                                    <td class="text-end"><?= number_format($akun['saldo'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="table-light fw-bold">
                                <td>Total Liabilitas Jangka Panjang</td>
                                <td class="text-end"><?= number_format($neraca['liabilitas']['liabilitas_jangka_panjang']['total'], 0, ',', '.') ?></td>
                            </tr>

                            <!-- Total Liabilitas -->
                            <tr class="table-light fw-bold">
                                <td>Total Liabilitas</td>
                                <td class="text-end"><?= number_format($neraca['liabilitas']['total_liabilitas'], 0, ',', '.') ?></td>
                            </tr>

                            <!-- Ekuitas -->
                            <tr class="table-light fw-bold">
                                <td>Ekuitas</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>&nbsp;&nbsp;Modal Disetor</td>
                                <td class="text-end"><?= number_format($neraca['ekuitas']['total_ekuitas'], 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td>&nbsp;&nbsp;Saldo Laba Ditahan</td>
                                <td class="text-end"><?= number_format($neraca['ekuitas']['laba_rugi_tahun_lalu'], 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td>&nbsp;&nbsp;Laba/Rugi Tahun Berjalan</td>
                                <td class="text-end"><?= number_format($neraca['ekuitas']['laba_rugi_tahun_berjalan'], 0, ',', '.') ?></td>
                            </tr>
                            <tr class="table-light fw-bold">
                                <td>Total Ekuitas</td>
                                <td class="text-end"><?= number_format($neraca['ekuitas']['total_ekuitas_with_profit'], 0, ',', '.') ?></td>
                            </tr>

                            <!-- Total Liabilitas & Ekuitas -->
                            <tr class="table-warning fw-bold">
                                <td>TOTAL LIABILITAS & EKUITAS</td>
                                <td class="text-end"><?= number_format($neraca['total_liabilitas_ekuitas'], 0, ',', '.') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Check -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="alert <?= $neraca['balance_check']['is_balance'] ? 'alert-success' : 'alert-danger' ?>" role="alert">
                <strong>Status Keseimbangan:</strong>
                <?php if ($neraca['balance_check']['is_balance']): ?>
                    <i class="fas fa-check-circle"></i> Neraca SEIMBANG
                    <br>
                    <small>Total Aset: Rp <?= number_format($neraca['balance_check']['total_aset'], 0, ',', '.') ?></small>
                    <br>
                    <small>Total Liabilitas & Ekuitas: Rp <?= number_format($neraca['balance_check']['total_liabilitas_ekuitas'], 0, ',', '.') ?></small>
                <?php else: ?>
                    <i class="fas fa-exclamation-circle"></i> Neraca TIDAK SEIMBANG
                    <br>
                    <small>Selisih: Rp <?= number_format($neraca['balance_check']['selisih'], 0, ',', '.') ?></small>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
