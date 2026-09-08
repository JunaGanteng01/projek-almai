<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-chart-line"></i> Laporan Laba Rugi (Income Statement)
                    </h1>
                    <p class="text-muted">Untuk Tahun yang Berakhir <?= date('d M Y', strtotime($labaRugi['periode']['end_date'])) ?></p>
                </div>
                <div>
                    <a href="<?= base_url('keuangan/sak-etap') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="<?= base_url('keuangan/sak-etap/laba-rugi?tahun=' . $tahunBuku . '&bulan=' . $bulanBuku . '&format=pdf') ?>" class="btn btn-danger" target="_blank">
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

    <!-- Laba Rugi Table -->
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
                            <!-- PENDAPATAN -->
                            <tr class="table-secondary fw-bold">
                                <td colspan="2">PENDAPATAN</td>
                            </tr>
                            <?php foreach ($labaRugi['pendapatan']['pendapatan_usaha']['detail'] as $akun): ?>
                                <tr>
                                    <td>&nbsp;&nbsp;<?= $akun['nama_akun'] ?></td>
                                    <td class="text-end"><?= number_format($akun['saldo'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="table-light fw-bold">
                                <td>Total Pendapatan Usaha</td>
                                <td class="text-end"><?= number_format($labaRugi['pendapatan']['pendapatan_usaha']['total'], 0, ',', '.') ?></td>
                            </tr>

                            <!-- BEBAN POKOK PENJUALAN -->
                            <tr class="table-secondary fw-bold">
                                <td colspan="2">BEBAN POKOK PENJUALAN</td>
                            </tr>
                            <?php foreach ($labaRugi['hpp']['detail'] as $akun): ?>
                                <tr>
                                    <td>&nbsp;&nbsp;<?= $akun['nama_akun'] ?></td>
                                    <td class="text-end"><?= number_format($akun['saldo'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="table-light fw-bold">
                                <td>Total HPP</td>
                                <td class="text-end"><?= number_format($labaRugi['hpp']['total'], 0, ',', '.') ?></td>
                            </tr>

                            <!-- LABA KOTOR -->
                            <tr class="table-warning fw-bold">
                                <td>LABA KOTOR</td>
                                <td class="text-end"><?= number_format($labaRugi['laba_kotor'], 0, ',', '.') ?></td>
                            </tr>

                            <!-- BEBAN OPERASIONAL -->
                            <tr class="table-secondary fw-bold">
                                <td colspan="2">BEBAN OPERASIONAL</td>
                            </tr>
                            <?php foreach ($labaRugi['beban']['beban_operasional']['detail'] as $akun): ?>
                                <tr>
                                    <td>&nbsp;&nbsp;<?= $akun['nama_akun'] ?></td>
                                    <td class="text-end"><?= number_format($akun['saldo'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="table-light fw-bold">
                                <td>Total Beban Operasional</td>
                                <td class="text-end"><?= number_format($labaRugi['beban']['beban_operasional']['total'], 0, ',', '.') ?></td>
                            </tr>

                            <!-- LABA/RUGI TAHUN BERJALAN -->
                            <tr class="table-warning fw-bold">
                                <td>LABA/RUGI TAHUN BERJALAN</td>
                                <td class="text-end"><?= number_format($labaRugi['laba_rugi_tahun_berjalan'], 0, ',', '.') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
