<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\KontakModel;
use App\Models\PembelianModel;
use App\Services\Keuangan\FakturFormService;
use App\Services\Keuangan\JournalService;

class Pembelian extends BaseController
{
    protected $pembelianModel;

    public function __construct()
    {
        $this->pembelianModel = new PembelianModel();
    }

    /**
     * Tampilkan daftar pembelian
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

        $query = $this->pembelianModel->getWithFilters($filters);
        $pembelian = $query->paginate(20, 'pembelian');
        $pager = $this->pembelianModel->pager;

        // Summary
        $summary = $this->pembelianModel->getSummary($filters);
        $hutang = $this->pembelianModel->getHutang();
        $hutang_total = array_sum(array_column($hutang, 'total'));

        $data = [
            'title' => 'Daftar Pembelian',
            'activeMenu' => 'pembelian',
            'pembelian' => $pembelian,
            'pager' => $pager,
            'search' => $search,
            'status' => $status,
            'status_pembayaran' => $status_pembayaran,
            'tanggal_dari' => $tanggal_dari,
            'tanggal_sampai' => $tanggal_sampai,
            'summary' => $summary,
            'hutang_total' => $hutang_total,
        ];

        return view('keuangan/pembelian/index', $data);
    }

    /**
     * Tampilkan detail pembelian
     */
    public function detail($id)
    {
        $pembelian = $this->pembelianModel->find($id);

        if (!$pembelian) {
            return redirect()->to('/keuangan/pembelian')->with('error', 'Data pembelian tidak ditemukan');
        }

        // Decode itemized list from JSON
        $items = [];
        if (!empty($pembelian['items_json'])) {
            $items = json_decode($pembelian['items_json'], true);
        }

        $data = [
            'title' => 'Detail Pembelian - ' . $pembelian['nomor_pi'],
            'activeMenu' => 'pembelian',
            'pembelian' => $pembelian,
            'items' => $items,
        ];

        return view('keuangan/pembelian/detail', $data);
    }

    /**
     * Form tambah/edit pembelian
     */
    public function form($id = null)
    {
        $pembelian = null;
        if ($id) {
            $pembelian = $this->pembelianModel->find($id);
            if (!$pembelian) {
                return redirect()->to('/keuangan/pembelian')->with('error', 'Data pembelian tidak ditemukan');
            }
        }

        $kontakModel = new KontakModel();

        return view('keuangan/pembelian/form', [
            'title' => $id ? 'Edit Pembelian' : 'Tambah Pembelian',
            'activeMenu' => 'pembelian',
            'pembelian' => $pembelian,
            'kontakList' => $kontakModel->getActiveForSelect('pemasok'),
        ]);
    }

    public function generateNomor()
    {
        $date = $this->request->getGet('date') ?: date('Y-m-d');
        $prefix = 'PI/' . date('ymd', strtotime($date)) . '/';
        $latest = $this->pembelianModel->like('nomor_pi', $prefix, 'after')->orderBy('id', 'DESC')->first();
        $num = 1;
        if ($latest && preg_match('/(\d+)$/', $latest['nomor_pi'], $m)) {
            $num = (int) $m[1] + 1;
        }

        return $this->response->setJSON(['success' => true, 'nomor' => $prefix . str_pad((string) $num, 4, '0', STR_PAD_LEFT)]);
    }

    /**
     * Simpan pembelian
     */
    public function save()
    {
        try {
            $id = $this->request->getPost('id');
            $kontakId = (int) $this->request->getPost('kontak_id');
            $namaSupplier = $this->request->getPost('nama_supplier');
            $kontak = FakturFormService::resolveKontak($kontakId, $namaSupplier);

            $parsed = FakturFormService::parseLineItems($this->request);
            if ($parsed['item_count'] === 0) {
                return redirect()->back()->withInput()->with('error', 'Minimal satu baris produk wajib diisi.');
            }

            $ppn = (float) ($this->request->getPost('ppn') ?? 0);
            $hargaTermasukPajak = $this->request->getPost('harga_termasuk_pajak') ? true : false;
            $biayaLainnya = (float) ($this->request->getPost('biaya_lainnya') ?? 0);
            $totals = FakturFormService::calculateTotals(
                $parsed['subtotal'],
                $ppn,
                (float) ($this->request->getPost('biaya_pengiriman') ?? 0) + $biayaLainnya,
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
                'nomor_pi' => $this->request->getPost('nomor_pi'),
                'nama_supplier' => $kontak['nama'] ?: $namaSupplier,
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
                'biaya_lainnya' => $biayaLainnya,
                'subtotal' => $totals['subtotal'],
                'total' => $totals['total'],
                'status_pembayaran' => $status_pembayaran,
                'tanggal_pembayaran' => $this->request->getPost('tanggal_pembayaran'),
                'catatan' => $this->request->getPost('catatan'),
                'kode_akun_beban' => $this->request->getPost('kode_akun_beban'),
                'kode_akun_hutang' => $this->request->getPost('kode_akun_hutang'),
                'updated_by' => session()->get('userId'),
            ]);

            $oldRow = $id ? $this->pembelianModel->find($id) : null;
            $wasLunas = $oldRow && ($oldRow['status_pembayaran'] ?? '') === 'lunas';

            if ($id) {
                $this->pembelianModel->update($id, $data);
                $recordId = (int) $id;
                $msg = 'Pembelian berhasil diperbarui';
            } else {
                $data['created_by'] = session()->get('userId');
                $recordId = (int) $this->pembelianModel->insert($data);
                $msg = 'Pembelian berhasil ditambahkan';
            }

            $row = $this->pembelianModel->find($recordId);
            $journal = new JournalService();
            $jr = $journal->postPembelian($row);
            if (!$jr['ok']) {
                return redirect()->to('/keuangan/pembelian')->with('error', $msg . ' — jurnal: ' . ($jr['message'] ?? 'gagal posting'));
            }

            if ($oldRow && !$wasLunas && $status_pembayaran === 'lunas') {
                $jp = $journal->postPembelianPelunasan($row);
                if (!$jp['ok']) {
                    return redirect()->to('/keuangan/pembelian')->with('error', $msg . ' — pelunasan: ' . ($jp['message'] ?? 'gagal posting'));
                }
            } elseif ($wasLunas && $status_pembayaran !== 'lunas') {
                $journal->deleteByReff('PB-PAY-' . $row['nomor_pi']);
            }

            return redirect()->to('/keuangan/pembelian')->with('success', $msg . ' (jurnal tercatat, balance)');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus pembelian
     */
    public function delete($id)
    {
        try {
            $pembelian = $this->pembelianModel->find($id);
            if (!$pembelian) {
                return redirect()->to('/keuangan/pembelian')->with('error', 'Data tidak ditemukan');
            }

            $journal = new JournalService();
            $journal->deleteByReff('PB-' . $pembelian['nomor_pi']);
            $journal->deleteByReff('PB-PAY-' . $pembelian['nomor_pi']);
            $this->pembelianModel->delete($id);
            return redirect()->to('/keuangan/pembelian')->with('success', 'Pembelian berhasil dihapus');
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

        $oldRow = $this->pembelianModel->find($id);
        $this->pembelianModel->update($id, $data);
        $row = $this->pembelianModel->find($id);

        $journal = new JournalService();
        $journal->postPembelian($row);

        if ($oldRow && ($oldRow['status_pembayaran'] ?? '') !== 'lunas' && $status_pembayaran === 'lunas') {
            $jp = $journal->postPembelianPelunasan($row);
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

        $data = $this->pembelianModel->getWithFilters($filters)->findAll();

        // Create CSV
        $output = fopen('php://memory', 'w');
        
        // Header
        fputcsv($output, [
            'No. PI',
            'Nama Supplier',
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
                $row['nomor_pi'],
                $row['nama_supplier'],
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
            ->setHeader('Content-Disposition', 'attachment; filename=pembelian_' . date('Y-m-d_H-i-s') . '.csv')
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
                        'nomor_pi' => trim($row[0] ?? ''),
                        'nama_supplier' => trim($row[1] ?? ''),
                        'email' => trim($row[2] ?? ''),
                        'alamat' => trim($row[3] ?? ''),
                        'provinsi' => trim($row[4] ?? ''),
                        'kota' => trim($row[5] ?? ''),
                        'tanggal_transaksi' => $this->parseDate($row[6] ?? '') ?? date('Y-m-d'),
                        'tanggal_jatuh_tempo' => $this->parseDate($row[7] ?? ''),
                        'nama_produk' => trim($row[8] ?? ''),
                        'kode_produk' => trim($row[9] ?? ''),
                        'jumlah_produk' => (float) ($row[10] ?? 0),
                        'satuan_produk' => trim($row[11] ?? ''),
                        'harga_produk' => (float) ($row[12] ?? 0),
                        'diskon_produk' => (float) ($row[13] ?? 0),
                        'ppn' => (float) ($row[14] ?? 0),
                        'biaya_pengiriman' => (float) ($row[15] ?? 0),
                        'biaya_lainnya' => (float) ($row[16] ?? 0),
                        'status_pembayaran' => trim($row[17] ?? 'belum_dibayar'),
                        'tanggal_pembayaran' => !empty($row[18]) ? $this->parseDate($row[18]) : null,
                        'kode_akun_beban' => trim($row[19] ?? '5-1-1'),
                        'kode_akun_hutang' => trim($row[20] ?? '2-1-2'),
                        'catatan' => trim($row[21] ?? ''),
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ];

                    // Calculate totals
                    $subtotal = ($data['jumlah_produk'] * $data['harga_produk']) - $data['diskon_produk'];
                    $ppn_nominal = $subtotal * ($data['ppn'] / 100);
                    $data['subtotal'] = $subtotal;
                    $data['total'] = $subtotal + $ppn_nominal + $data['biaya_pengiriman'] + $data['biaya_lainnya'];

                    // Skip if nomor_pi is empty
                    if (empty($data['nomor_pi'])) {
                        continue;
                    }

                    // Check for duplicates
                    $existing = $this->pembelianModel
                        ->where('nomor_pi', $data['nomor_pi'])
                        ->first();
                    
                    if (!$existing) {
                        $result = $this->pembelianModel->insert($data);
                        if ($result) {
                            $imported++;
                        } else {
                            $modelErrors = $this->pembelianModel->errors();
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

            return redirect()->to('/keuangan/pembelian')->with('success', $msg);
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
