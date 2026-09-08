<?php

namespace App\Services\Keuangan;

use App\Models\AkunModel;
use App\Models\JurnalModel;
use App\Models\PeriodeAkuntansiModel;

/**
 * Pusat posting jurnal double-entry (debit = kredit).
 */
class JournalService
{
    protected JurnalModel $jurnalModel;
    protected AkunModel $akunModel;
    protected PeriodeAkuntansiModel $periodeModel;

    public function __construct()
    {
        $this->jurnalModel = new JurnalModel();
        $this->akunModel = new AkunModel();
        $this->periodeModel = new PeriodeAkuntansiModel();
    }

    /**
     * @param array<int, array{akun_id:int,debit:float,kredit:float}> $lines
     * @return array{ok:bool,message?:string}
     */
    public function post(string $tanggal, string $noReff, string $deskripsi, array $lines, bool $replaceExisting = true): array
    {
        $normalized = $this->normalizeLines($lines);
        if ($normalized === null) {
            return ['ok' => false, 'message' => 'Jurnal harus memiliki minimal satu baris transaksi.'];
        }

        [$validLines, $totalDebit, $totalKredit] = $normalized;

        if (abs($totalDebit - $totalKredit) > 0.01) {
            return [
                'ok' => false,
                'message' => 'Total debit dan kredit tidak balance. Selisih: Rp '
                    . number_format(abs($totalDebit - $totalKredit), 0, ',', '.'),
            ];
        }

        if ($this->isPeriodClosed($tanggal)) {
            $t = strtotime($tanggal);
            return [
                'ok' => false,
                'message' => 'Periode akuntansi ' . date('n', $t) . '/' . date('Y', $t) . ' sudah ditutup.',
            ];
        }

        $userId = session()->get('userId');
        $db = \Config\Database::connect();
        $db->transStart();

        if ($replaceExisting) {
            $this->jurnalModel->where('no_reff', $noReff)->delete();
        }

        foreach ($validLines as $line) {
            $this->jurnalModel->insert([
                'tanggal' => $tanggal,
                'no_reff' => $noReff,
                'deskripsi' => $deskripsi,
                'akun_id' => $line['akun_id'],
                'debit' => $line['debit'],
                'kredit' => $line['kredit'],
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return ['ok' => false, 'message' => 'Gagal menyimpan jurnal ke database.'];
        }

        return ['ok' => true];
    }

    public function deleteByReff(string $noReff): void
    {
        $this->jurnalModel->where('no_reff', $noReff)->delete();
    }

    public function exists(string $noReff): bool
    {
        return $this->jurnalModel->where('no_reff', $noReff)->countAllResults() > 0;
    }

    public function isPeriodClosed(string $tanggal): bool
    {
        $time = strtotime($tanggal);
        return $this->periodeModel->isClosed((int) date('n', $time), (int) date('Y', $time));
    }

    /**
     * Posting penjualan: pengakuan pendapatan.
     */
    public function postPenjualan(array $row): array
    {
        $total = (float) ($row['total'] ?? 0);
        if ($total <= 0) {
            return ['ok' => true];
        }

        $revenue = $this->resolveAkun(
            $row['kode_akun_revenue'] ?? null,
            'pendapatan'
        );
        if (!$revenue) {
            return ['ok' => false, 'message' => 'Akun pendapatan belum dikonfigurasi di COA.'];
        }

        $isLunas = ($row['status_pembayaran'] ?? '') === 'lunas';
        $debitSide = $isLunas
            ? $this->akunModel->getFirstByKategori('kas & bank')
            : $this->akunModel->getFirstByKategori('akun piutang');

        if (!$debitSide) {
            $kategori = $isLunas ? 'kas & bank' : 'akun piutang';
            return ['ok' => false, 'message' => "Akun {$kategori} belum ada di COA."];
        }

        $tanggal = $row['tanggal_transaksi'] ?? date('Y-m-d');
        $noReff = 'PJ-' . ($row['nomor_tagihan'] ?? $row['id']);
        $deskripsi = 'Penjualan ' . ($row['nomor_tagihan'] ?? '') . ' - ' . ($row['nama_kontak'] ?? '');

        return $this->post($tanggal, $noReff, $deskripsi, [
            ['akun_id' => (int) $debitSide['id'], 'debit' => $total, 'kredit' => 0],
            ['akun_id' => (int) $revenue['id'], 'debit' => 0, 'kredit' => $total],
        ]);
    }

    /**
     * Pelunasan piutang penjualan: Dr Kas, Cr Piutang.
     */
    public function postPenjualanPelunasan(array $row): array
    {
        $total = (float) ($row['total'] ?? 0);
        if ($total <= 0) {
            return ['ok' => true];
        }

        $kas = $this->akunModel->getFirstByKategori('kas & bank');
        $piutang = $this->akunModel->getFirstByKategori('akun piutang');
        if (!$kas || !$piutang) {
            return ['ok' => false, 'message' => 'Akun kas atau piutang belum dikonfigurasi di COA.'];
        }

        $tanggal = $row['tanggal_pembayaran'] ?? $row['tanggal_transaksi'] ?? date('Y-m-d');
        $noReff = 'PJ-PAY-' . ($row['nomor_tagihan'] ?? $row['id']);
        $deskripsi = 'Pelunasan penjualan ' . ($row['nomor_tagihan'] ?? '');

        return $this->post($tanggal, $noReff, $deskripsi, [
            ['akun_id' => (int) $kas['id'], 'debit' => $total, 'kredit' => 0],
            ['akun_id' => (int) $piutang['id'], 'debit' => 0, 'kredit' => $total],
        ]);
    }

    /**
     * Posting pembelian: pengakuan beban/hutang.
     */
    public function postPembelian(array $row): array
    {
        $total = (float) ($row['total'] ?? 0);
        if ($total <= 0) {
            return ['ok' => true];
        }

        $beban = $this->resolveAkun($row['kode_akun_beban'] ?? null, 'beban');
        if (!$beban) {
            return ['ok' => false, 'message' => 'Akun beban belum dikonfigurasi di COA.'];
        }

        $isLunas = ($row['status_pembayaran'] ?? '') === 'lunas';
        if ($isLunas) {
            $kreditSide = $this->akunModel->getFirstByKategori('kas & bank');
            if (!$kreditSide) {
                return ['ok' => false, 'message' => 'Akun kas & bank belum ada di COA.'];
            }
        } else {
            $kreditSide = $this->resolveAkun($row['kode_akun_hutang'] ?? null, 'akun hutang');
            if (!$kreditSide) {
                return ['ok' => false, 'message' => 'Akun hutang belum dikonfigurasi di COA.'];
            }
        }

        $tanggal = $row['tanggal_transaksi'] ?? date('Y-m-d');
        $noReff = 'PB-' . ($row['nomor_pi'] ?? $row['id']);
        $deskripsi = 'Pembelian ' . ($row['nomor_pi'] ?? '') . ' - ' . ($row['nama_supplier'] ?? '');

        return $this->post($tanggal, $noReff, $deskripsi, [
            ['akun_id' => (int) $beban['id'], 'debit' => $total, 'kredit' => 0],
            ['akun_id' => (int) $kreditSide['id'], 'debit' => 0, 'kredit' => $total],
        ]);
    }

    /**
     * Pelunasan hutang pembelian: Dr Hutang, Cr Kas.
     */
    public function postPembelianPelunasan(array $row): array
    {
        $total = (float) ($row['total'] ?? 0);
        if ($total <= 0) {
            return ['ok' => true];
        }

        $kas = $this->akunModel->getFirstByKategori('kas & bank');
        $hutang = $this->akunModel->getFirstByKategori('akun hutang');
        if (!$kas || !$hutang) {
            return ['ok' => false, 'message' => 'Akun kas atau hutang belum dikonfigurasi di COA.'];
        }

        $tanggal = $row['tanggal_pembayaran'] ?? $row['tanggal_transaksi'] ?? date('Y-m-d');
        $noReff = 'PB-PAY-' . ($row['nomor_pi'] ?? $row['id']);
        $deskripsi = 'Pelunasan pembelian ' . ($row['nomor_pi'] ?? '');

        return $this->post($tanggal, $noReff, $deskripsi, [
            ['akun_id' => (int) $hutang['id'], 'debit' => $total, 'kredit' => 0],
            ['akun_id' => (int) $kas['id'], 'debit' => 0, 'kredit' => $total],
        ]);
    }

    /**
     * Invoice customer lunas: Dr Kas, Cr Piutang (atau Pendapatan).
     */
    public function postInvoicePaid(array $invoice): array
    {
        $total = (float) ($invoice['total'] ?? 0);
        if ($total <= 0) {
            return ['ok' => true];
        }

        $kas = $this->akunModel->getFirstByKategori('kas & bank');
        $kredit = $this->akunModel->getFirstByKategori('akun piutang')
            ?: $this->akunModel->getFirstByKategori('pendapatan');
        if (!$kas || !$kredit) {
            return ['ok' => false, 'message' => 'Akun kas atau piutang/pendapatan belum dikonfigurasi.'];
        }

        $tanggal = $invoice['tanggal'] ?? date('Y-m-d');
        $noReff = 'INV-' . ($invoice['invoice_number'] ?? $invoice['id']);
        $deskripsi = 'Invoice ' . ($invoice['invoice_number'] ?? '') . ' - ' . ($invoice['customer_name'] ?? '');

        return $this->post($tanggal, $noReff, $deskripsi, [
            ['akun_id' => (int) $kas['id'], 'debit' => $total, 'kredit' => 0],
            ['akun_id' => (int) $kredit['id'], 'debit' => 0, 'kredit' => $total],
        ]);
    }

    /**
     * Pengeluaran tunai (legacy module): Dr Beban, Cr Kas.
     */
    public function postPengeluaranLunas(array $row): array
    {
        $total = (float) ($row['total'] ?? 0);
        if ($total <= 0) {
            return ['ok' => true];
        }

        $beban = $this->akunModel->getFirstByKategori('beban')
            ?: $this->akunModel->getFirstByKategori('harga pokok penjualan');
        $kas = $this->akunModel->getFirstByKategori('kas & bank');
        if (!$beban || !$kas) {
            return ['ok' => false, 'message' => 'Akun beban atau kas belum dikonfigurasi.'];
        }

        $tanggal = $row['tanggal'] ?? date('Y-m-d');
        $noReff = 'PNG-' . ($row['no_faktur'] ?? $row['id'] ?? time());
        $deskripsi = 'Pengeluaran ' . ($row['no_faktur'] ?? '');

        return $this->post($tanggal, $noReff, $deskripsi, [
            ['akun_id' => (int) $beban['id'], 'debit' => $total, 'kredit' => 0],
            ['akun_id' => (int) $kas['id'], 'debit' => 0, 'kredit' => $total],
        ]);
    }

    /**
     * Registrasi aset: Dr Aset, Cr Kas/Hutang/Ekuitas.
     */
    public function postAsetRegistrasi(array $aset): array
    {
        $nilai = (float) ($aset['harga_beli'] ?? 0);
        if ($nilai <= 0) {
            return ['ok' => true];
        }

        $akunAset = $this->resolveAkun($aset['akun_aset_kode'] ?? null, 'aktiva tetap');
        $akunKredit = $this->resolveAkun($aset['akun_kredit_kode'] ?? null, 'kas & bank');
        if (!$akunAset || !$akunKredit) {
            return ['ok' => false, 'message' => 'Akun aset atau sumber kredit belum dikonfigurasi.'];
        }

        $tanggal = $aset['tanggal_pembelian'] ?? date('Y-m-d');
        $noReff = 'AST-' . ($aset['nomor_aset'] ?? $aset['id']);
        $deskripsi = 'Perolehan aset ' . ($aset['nama_aset'] ?? '');

        return $this->post($tanggal, $noReff, $deskripsi, [
            ['akun_id' => (int) $akunAset['id'], 'debit' => $nilai, 'kredit' => 0],
            ['akun_id' => (int) $akunKredit['id'], 'debit' => 0, 'kredit' => $nilai],
        ]);
    }

    /**
     * Penyusutan bulanan: Dr Beban penyusutan, Cr Akumulasi.
     */
    public function postAsetPenyusutan(array $aset, float $nilaiPenyusutan, string $tanggal): array
    {
        if ($nilaiPenyusutan <= 0) {
            return ['ok' => true];
        }

        $akunBeban = $this->resolveAkun($aset['akun_penyusutan_kode'] ?? null, 'beban');
        $akunAkum = $this->resolveAkun($aset['akun_akumulasi_kode'] ?? null, 'depresiasi & amortisasi');
        if (!$akunBeban || !$akunAkum) {
            return ['ok' => false, 'message' => 'Akun beban penyusutan atau akumulasi belum dikonfigurasi.'];
        }

        $noReff = 'DEP-' . ($aset['nomor_aset'] ?? $aset['id']) . '-' . date('Ym', strtotime($tanggal));
        $deskripsi = 'Penyusutan ' . ($aset['nama_aset'] ?? '') . ' ' . date('M Y', strtotime($tanggal));

        return $this->post($tanggal, $noReff, $deskripsi, [
            ['akun_id' => (int) $akunBeban['id'], 'debit' => $nilaiPenyusutan, 'kredit' => 0],
            ['akun_id' => (int) $akunAkum['id'], 'debit' => 0, 'kredit' => $nilaiPenyusutan],
        ], false);
    }

    protected function resolveAkun(?string $kode, string $fallbackKategori): ?array
    {
        if ($kode) {
            $akun = $this->akunModel->findByKode($kode);
            if ($akun) {
                return $akun;
            }
        }

        return $this->akunModel->getFirstByKategori($fallbackKategori);
    }

    /**
     * @return array{0:array,1:float,2:float}|null
     */
    protected function normalizeLines(array $lines): ?array
    {
        $validLines = [];
        $totalDebit = 0.0;
        $totalKredit = 0.0;

        foreach ($lines as $line) {
            if (empty($line['akun_id'])) {
                continue;
            }

            $debit = (float) ($line['debit'] ?? 0);
            $kredit = (float) ($line['kredit'] ?? 0);
            if ($debit == 0 && $kredit == 0) {
                continue;
            }

            $akun = $this->akunModel->find($line['akun_id']);
            if (!$akun) {
                return null;
            }

            $validLines[] = [
                'akun_id' => (int) $line['akun_id'],
                'debit' => $debit,
                'kredit' => $kredit,
            ];
            $totalDebit += $debit;
            $totalKredit += $kredit;
        }

        if (empty($validLines)) {
            return null;
        }

        return [$validLines, $totalDebit, $totalKredit];
    }
}
