<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\KontakModel;
use App\Models\PenjualanModel;
use App\Services\Keuangan\FakturFormService;
use App\Services\Keuangan\JournalService;

class Penjualan extends BaseController
{
    protected $penjualanModel;

    public function __construct()
    {
        $this->penjualanModel = new PenjualanModel();
    }

    /**
     * Tampilkan daftar penjualan
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $search = $this->request->getGet('search') ?? '';
        $status = $this->request->getGet('status') ?? '';
        $status_pembayaran = $this->request->getGet('status_pembayaran') ?? '';
        $tanggal_dari = $this->request->getGet('tanggal_dari') ?? '';
        $tanggal_sampai = $this->request->getGet('tanggal_sampai') ?? '';

        $filters = [
            'search' => $search,
            'status' => $status,
            'status_pembayaran' => $status_pembayaran,
            'tanggal_dari' => $tanggal_dari,
            'tanggal_sampai' => $tanggal_sampai,
        ];

        $query = $this->penjualanModel->getWithFilters($filters);
        $penjualan = $query->paginate(20, 'penjualan');
        $pager = $this->penjualanModel->pager;

        // Summary
        $summary = $this->penjualanModel->getSummary($filters);
        $piutang = $this->penjualanModel->getPiutang();
        $piutang_total = array_sum(array_column($piutang, 'total'));

        $data = [
            'title' => 'Daftar Penjualan',
            'activeMenu' => 'penjualan',
            'penjualan' => $penjualan,
            'pager' => $pager,
            'search' => $search,
            'status' => $status,
            'status_pembayaran' => $status_pembayaran,
            'tanggal_dari' => $tanggal_dari,
            'tanggal_sampai' => $tanggal_sampai,
            'summary' => $summary,
            'piutang_total' => $piutang_total,
        ];

        return view('keuangan/penjualan/index', $data);
    }

    /**
     * Tampilkan detail penjualan
     */
    public function detail($id)
    {
        $penjualan = $this->penjualanModel->find($id);

        if (!$penjualan) {
            return redirect()->to('/keuangan/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        // Decode itemized list from JSON
        $items = [];
        if (!empty($penjualan['items_json'])) {
            $items = json_decode($penjualan['items_json'], true);
        }

        $data = [
            'title' => 'Detail Penjualan - ' . $penjualan['nomor_tagihan'],
            'activeMenu' => 'penjualan',
            'penjualan' => $penjualan,
            'items' => $items,
        ];

        return view('keuangan/penjualan/detail', $data);
    }

    /**
     * Form tambah/edit penjualan
     */
    public function form($id = null)
    {
        $penjualan = null;
        if ($id) {
            $penjualan = $this->penjualanModel->find($id);
            if (!$penjualan) {
                return redirect()->to('/keuangan/penjualan')->with('error', 'Data penjualan tidak ditemukan');
            }
        }

        $kontakModel = new KontakModel();

        return view('keuangan/penjualan/form', [
            'title' => $id ? 'Edit Penjualan' : 'Tambah Penjualan',
            'activeMenu' => 'penjualan',
            'penjualan' => $penjualan,
            'kontakList' => $kontakModel->getActiveForSelect('pelanggan'),
        ]);
    }

    public function generateNomor()
    {
        $date = $this->request->getGet('date') ?: date('Y-m-d');
        $prefix = 'INV/' . date('ymd', strtotime($date)) . '/';
        $latest = $this->penjualanModel->like('nomor_tagihan', $prefix, 'after')->orderBy('id', 'DESC')->first();
        $num = 1;
        if ($latest && preg_match('/(\d+)$/', $latest['nomor_tagihan'], $m)) {
            $num = (int) $m[1] + 1;
        }

        return $this->response->setJSON(['success' => true, 'nomor' => $prefix . str_pad((string) $num, 4, '0', STR_PAD_LEFT)]);
    }

    /**
     * Simpan penjualan
     */
    public function save()
    {
        try {
            $id = $this->request->getPost('id');
            $kontakId = (int) $this->request->getPost('kontak_id');
            $namaKontak = $this->request->getPost('nama_kontak');
            $kontak = FakturFormService::resolveKontak($kontakId, $namaKontak);

            $parsed = FakturFormService::parseLineItems($this->request);
            if ($parsed['item_count'] === 0) {
                return redirect()->back()->withInput()->with('error', 'Minimal satu baris produk wajib diisi.');
            }

            $ppn = (float) ($this->request->getPost('ppn') ?? 0);
            $hargaTermasukPajak = $this->request->getPost('harga_termasuk_pajak') ? true : false;
            $totals = FakturFormService::calculateTotals(
                $parsed['subtotal'],
                $ppn,
                (float) ($this->request->getPost('biaya_pengiriman') ?? 0),
                (float) ($this->request->getPost('diskon_tambahan') ?? 0),
                (float) ($this->request->getPost('biaya_transaksi') ?? 0),
                (float) ($this->request->getPost('uang_muka') ?? 0),
                (float) ($this->request->getPost('pemotongan') ?? 0),
                $hargaTermasukPajak
            );

            $legacy = FakturFormService::legacyProductFields($parsed['items']);
            $status_pembayaran = $this->request->getPost('status_pembayaran');

            $data = array_merge($legacy, [
                'kontak_id' => $kontakId ?: null,
                'nomor_tagihan' => $this->request->getPost('nomor_tagihan'),
                'nama_kontak' => $kontak['nama'] ?: $namaKontak,
                'perusahaan' => $kontak['perusahaan'],
                'email' => $this->request->getPost('email') ?: $kontak['email'],
                'alamat' => $kontak['alamat'],
                'referensi' => $this->request->getPost('referensi'),
                'termin_hari' => (int) ($this->request->getPost('termin_hari') ?? 30),
                'tag' => $this->request->getPost('tag'),
                'items_json' => json_encode($parsed['items']),
                'diskon_tambahan' => (float) ($this->request->getPost('diskon_tambahan') ?? 0),
                'biaya_transaksi' => (float) ($this->request->getPost('biaya_transaksi') ?? 0),
                'uang_muka' => (float) ($this->request->getPost('uang_muka') ?? 0),
                'pemotongan' => (float) ($this->request->getPost('pemotongan') ?? 0),
                'harga_termasuk_pajak' => $hargaTermasukPajak ? 1 : 0,
                'tanggal_transaksi' => $this->request->getPost('tanggal_transaksi'),
                'tanggal_jatuh_tempo' => $this->request->getPost('tanggal_jatuh_tempo'),
                'ppn' => $ppn,
                'biaya_pengiriman' => (float) ($this->request->getPost('biaya_pengiriman') ?? 0),
                'subtotal' => $totals['subtotal'],
                'total' => $totals['total'],
                'status_pembayaran' => $status_pembayaran,
                'tanggal_pembayaran' => $this->request->getPost('tanggal_pembayaran'),
                'catatan' => $this->request->getPost('catatan'),
                'kode_akun_revenue' => $this->request->getPost('kode_akun_revenue'),
                'updated_by' => session()->get('userId'),
            ]);

            $oldRow = $id ? $this->penjualanModel->find($id) : null;
            $wasLunas = $oldRow && ($oldRow['status_pembayaran'] ?? '') === 'lunas';

            if ($id) {
                $this->penjualanModel->update($id, $data);
                $recordId = (int) $id;
                $msg = 'Penjualan berhasil diperbarui';
            } else {
                $data['created_by'] = session()->get('userId');
                $recordId = (int) $this->penjualanModel->insert($data);
                $msg = 'Penjualan berhasil ditambahkan';
            }

            $row = $this->penjualanModel->find($recordId);
            $journal = new JournalService();
            $jr = $journal->postPenjualan($row);
            if (!$jr['ok']) {
                return redirect()->to('/keuangan/penjualan')->with('error', $msg . ' — jurnal: ' . ($jr['message'] ?? 'gagal posting'));
            }

            if ($oldRow && !$wasLunas && $status_pembayaran === 'lunas') {
                $jp = $journal->postPenjualanPelunasan($row);
                if (!$jp['ok']) {
                    return redirect()->to('/keuangan/penjualan')->with('error', $msg . ' — pelunasan: ' . ($jp['message'] ?? 'gagal posting'));
                }
            } elseif ($wasLunas && $status_pembayaran !== 'lunas') {
                $journal->deleteByReff('PJ-PAY-' . $row['nomor_tagihan']);
            }

            return redirect()->to('/keuangan/penjualan')->with('success', $msg . ' (jurnal tercatat, balance)');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus penjualan
     */
    public function delete($id)
    {
        try {
            $penjualan = $this->penjualanModel->find($id);
            if (!$penjualan) {
                return redirect()->to('/keuangan/penjualan')->with('error', 'Data tidak ditemukan');
            }

            $journal = new JournalService();
            $journal->deleteByReff('PJ-' . $penjualan['nomor_tagihan']);
            $journal->deleteByReff('PJ-PAY-' . $penjualan['nomor_tagihan']);
            $this->penjualanModel->delete($id);
            return redirect()->to('/keuangan/penjualan')->with('success', 'Penjualan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Update status pembayaran
     */
    public function updateStatus()
    {
        $id = $this->request->getPost('id');
        $status_pembayaran = $this->request->getPost('status_pembayaran');
        $tanggal_pembayaran = $this->request->getPost('tanggal_pembayaran');

        $data = [
            'status_pembayaran' => $status_pembayaran,
        ];

        if ($status_pembayaran === 'lunas' && $tanggal_pembayaran) {
            $data['tanggal_pembayaran'] = $tanggal_pembayaran;
        }

        $data['updated_by'] = session()->get('userId');

        $oldRow = $this->penjualanModel->find($id);
        $this->penjualanModel->update($id, $data);
        $row = $this->penjualanModel->find($id);

        $journal = new JournalService();
        $journal->postPenjualan($row);

        if ($oldRow && ($oldRow['status_pembayaran'] ?? '') !== 'lunas' && $status_pembayaran === 'lunas') {
            $jp = $journal->postPenjualanPelunasan($row);
            if (!$jp['ok']) {
                return redirect()->back()->with('error', 'Status diubah tetapi jurnal pelunasan gagal: ' . ($jp['message'] ?? ''));
            }
        }

        return redirect()->back()->with('success', 'Status pembayaran & jurnal diperbarui');
    }

    /**
     * Export ke CSV
     */
    public function exportCsv()
    {
        $search = $this->request->getGet('search') ?? '';
        $status_pembayaran = $this->request->getGet('status_pembayaran') ?? '';
        $tanggal_dari = $this->request->getGet('tanggal_dari') ?? '';
        $tanggal_sampai = $this->request->getGet('tanggal_sampai') ?? '';

        $filters = [
            'search' => $search,
            'status_pembayaran' => $status_pembayaran,
            'tanggal_dari' => $tanggal_dari,
            'tanggal_sampai' => $tanggal_sampai,
        ];

        $data = $this->penjualanModel->getWithFilters($filters)->findAll();

        // Create CSV
        $output = fopen('php://memory', 'w');
        
        // Header
        fputcsv($output, [
            'No. Tagihan',
            'Nama Kontak',
            'Perusahaan',
            'Tanggal',
            'Produk',
            'Jumlah',
            'Harga',
            'Total',
            'Status Pembayaran',
            'Tanggal Pembayaran',
        ]);

        // Data
        foreach ($data as $row) {
            fputcsv($output, [
                $row['nomor_tagihan'],
                $row['nama_kontak'],
                $row['perusahaan'],
                date('d/m/Y', strtotime($row['tanggal_transaksi'])),
                $row['nama_produk'],
                $row['jumlah_produk'],
                number_format($row['harga_produk'], 0, ',', '.'),
                number_format($row['total'], 0, ',', '.'),
                ucfirst(str_replace('_', ' ', $row['status_pembayaran'])),
                $row['tanggal_pembayaran'] ? date('d/m/Y', strtotime($row['tanggal_pembayaran'])) : '-',
            ]);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename=penjualan_' . date('Y-m-d_H-i-s') . '.csv')
            ->setBody($csv);
    }

    /**
     * Import dari CSV
     */
    public function importCsv()
    {
        try {
            $file = $this->request->getFile('csv_file');

            if (!$file || $file->getError() !== UPLOAD_ERR_OK) {
                return redirect()->back()->with('error', 'File tidak valid');
            }

            // Read CSV
            $filePath = $file->getTempName();
            $handle = fopen($filePath, 'r');
            $header = fgetcsv($handle);
            
            $imported = 0;
            $errors = [];
            $userId = session()->get('userId') ?? 1; // Default to 1 if no session

            while (($row = fgetcsv($handle)) !== false) {
                try {
                    // Skip empty rows
                    if (empty($row[0])) {
                        continue;
                    }

                    $data = [
                        'nomor_tagihan' => trim($row[0] ?? ''),
                        'nama_kontak' => trim($row[1] ?? ''),
                        'perusahaan' => trim($row[2] ?? ''),
                        'email' => trim($row[3] ?? ''),
                        'alamat' => trim($row[4] ?? ''),
                        'provinsi' => trim($row[5] ?? ''),
                        'kota' => trim($row[6] ?? ''),
                        'tanggal_transaksi' => $this->parseDate($row[7] ?? '') ?? date('Y-m-d'),
                        'tanggal_jatuh_tempo' => $this->parseDate($row[8] ?? ''),
                        'nama_produk' => trim($row[9] ?? ''),
                        'kode_produk' => trim($row[10] ?? ''),
                        'jumlah_produk' => (float) ($row[11] ?? 0),
                        'satuan_produk' => trim($row[12] ?? ''),
                        'harga_produk' => (float) ($row[13] ?? 0),
                        'diskon_produk' => (float) ($row[14] ?? 0),
                        'ppn' => (float) ($row[15] ?? 0),
                        'biaya_pengiriman' => (float) ($row[16] ?? 0),
                        'status_pembayaran' => trim($row[17] ?? 'belum_dibayar'),
                        'tanggal_pembayaran' => !empty($row[18]) ? $this->parseDate($row[18]) : null,
                        'kode_akun_revenue' => trim($row[19] ?? '4-1-1'),
                        'catatan' => trim($row[20] ?? ''),
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ];

                    // Calculate totals
                    $subtotal = ($data['jumlah_produk'] * $data['harga_produk']) - $data['diskon_produk'];
                    $ppn_nominal = $subtotal * ($data['ppn'] / 100);
                    $data['subtotal'] = $subtotal;
                    $data['total'] = $subtotal + $ppn_nominal + $data['biaya_pengiriman'];

                    // Skip if nomor_tagihan is empty
                    if (empty($data['nomor_tagihan'])) {
                        continue;
                    }

                    // Check for duplicates
                    $existing = $this->penjualanModel
                        ->where('nomor_tagihan', $data['nomor_tagihan'])
                        ->first();
                    
                    if (!$existing) {
                        // Skip validation since we manually check duplicates above
                        $result = $this->penjualanModel->skipValidation()->insert($data);
                        if ($result) {
                            $imported++;
                        } else {
                            $modelErrors = $this->penjualanModel->errors();
                            $errorMsg = is_array($modelErrors) ? implode(', ', $modelErrors) : (string)$modelErrors;
                            $errors[] = 'Row ' . ($imported + count($errors)) . ': ' . $errorMsg;
                        }
                    }
                } catch (\Exception $e) {
                    $errors[] = 'Row error: ' . $e->getMessage();
                }
            }

            fclose($handle);

            $msg = "Import berhasil: $imported data dimasukkan";
            if (!empty($errors)) {
                $msg .= '. ' . count($errors) . ' baris gagal. Cek log untuk detail.';
            }

            return redirect()->to('/keuangan/penjualan')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Parse date from various formats
     */
    private function parseDate($dateStr)
    {
        if (empty($dateStr)) {
            return null;
        }

        $dateStr = trim($dateStr);
        
        // Try various formats
        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'Y/m/d'];
        
        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $dateStr);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }
}
