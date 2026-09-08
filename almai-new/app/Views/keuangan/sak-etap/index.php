<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-3">
                <i class="fas fa-file-invoice-dollar"></i> SAK-ETAP - Laporan Keuangan
            </h1>
            <p class="text-muted">Standar Akuntansi Keuangan untuk Entitas Tanpa Akuntabilitas Publik</p>
        </div>
    </div>

    <!-- Filter Periode -->
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
                                $bulanIndo = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                for ($i = 1; $i <= 12; $i++): 
                                ?>
                                    <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= $bulanBuku == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' ?>>
                                        <?= $bulanIndo[$i] ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan Cards -->
    <div class="row">
        <!-- Neraca -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-balance-scale fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">Neraca</h5>
                    <p class="card-text text-muted small">Balance Sheet</p>
                    <div class="btn-group" role="group">
                        <a href="<?= base_url('keuangan/sak-etap/neraca?tahun=' . $tahunBuku . '&bulan=' . $bulanBuku) ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                        <a href="<?= base_url('keuangan/sak-etap/neraca?tahun=' . $tahunBuku . '&bulan=' . $bulanBuku . '&format=pdf') ?>" class="btn btn-sm btn-outline-danger" target="_blank">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laba Rugi -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-chart-line fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title">Laba Rugi</h5>
                    <p class="card-text text-muted small">Income Statement</p>
                    <div class="btn-group" role="group">
                        <a href="<?= base_url('keuangan/sak-etap/laba-rugi?tahun=' . $tahunBuku . '&bulan=' . $bulanBuku) ?>" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                        <a href="<?= base_url('keuangan/sak-etap/laba-rugi?tahun=' . $tahunBuku . '&bulan=' . $bulanBuku . '&format=pdf') ?>" class="btn btn-sm btn-outline-danger" target="_blank">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Arus Kas -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-water fa-3x text-info"></i>
                    </div>
                    <h5 class="card-title">Arus Kas</h5>
                    <p class="card-text text-muted small">Cash Flow Statement</p>
                    <div class="btn-group" role="group">
                        <a href="<?= base_url('keuangan/sak-etap/arus-kas?tahun=' . $tahunBuku . '&bulan=' . $bulanBuku) ?>" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                        <a href="<?= base_url('keuangan/sak-etap/arus-kas?tahun=' . $tahunBuku . '&bulan=' . $bulanBuku . '&format=pdf') ?>" class="btn btn-sm btn-outline-danger" target="_blank">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Perubahan Ekuitas -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-exchange-alt fa-3x text-warning"></i>
                    </div>
                    <h5 class="card-title">Perubahan Ekuitas</h5>
                    <p class="card-text text-muted small">Changes in Equity</p>
                    <div class="btn-group" role="group">
                        <a href="<?= base_url('keuangan/sak-etap/perubahan-ekuitas?tahun=' . $tahunBuku . '&bulan=' . $bulanBuku) ?>" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                        <a href="<?= base_url('keuangan/sak-etap/perubahan-ekuitas?tahun=' . $tahunBuku . '&bulan=' . $bulanBuku . '&format=pdf') ?>" class="btn btn-sm btn-outline-danger" target="_blank">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan Lengkap -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-book"></i> Laporan Keuangan Lengkap
                    </h5>
                    <p class="card-text text-muted">Download semua laporan dalam satu file PDF</p>
                    <a href="<?= base_url('keuangan/sak-etap/laporan-lengkap?tahun=' . $tahunBuku . '&bulan=' . $bulanBuku) ?>" class="btn btn-primary" target="_blank">
                        <i class="fas fa-download"></i> Download PDF Lengkap
                    </a>
                </div>
            </div>
        </div>
        <!-- Laporan Bappebti -->
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-file-contract"></i> Laporan KAP Bappebti
                    </h5>
                    <p class="card-text text-muted">Download Laporan format Bappebti komparatif</p>
                    <a href="<?= base_url('keuangan/bappebti/generate?tahun=' . $tahunBuku) ?>" class="btn btn-danger" target="_blank">
                        <i class="fas fa-download"></i> Download PDF Bappebti
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow {
        transition: box-shadow 0.3s ease;
    }
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
<?= $this->endSection() ?>
