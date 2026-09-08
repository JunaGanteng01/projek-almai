<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\AsetModel;
use App\Models\AkunModel;
use App\Services\Keuangan\JournalService;

class Aset extends BaseController
{
    public function index()
    {
        $asetModel = new AsetModel();
        $akunModel = new AkunModel();

        $status = $this->request->getGet('status') ?? 'Terdaftar';
        $search = $this->request->getGet('search');

        $query = $asetModel->where('status', $status);
        if ($search) {
            $query->like('nama_aset', $search)->orLike('nomor_aset', $search);
        }

        
        $assets = $query->paginate(20);
        $pager = $query->pager;
        $summary = $asetModel->getSummary();

        $data = [
            'title' => 'Aset Tetap',
            'activeMenu' => 'aset',
            'assets' => $assets,
            'pager' => $pager,
            'summary' => $summary,

            'currentStatus' => $status,
            'currentSearch' => $search
        ];

        return view('keuangan/aset/index', $data);
    }

    public function create()
    {
        $akunModel = new AkunModel();
        
        // Get relevant accounts for dropdowns
        $data = [
            'title' => 'Tambah Aset Tetap',
            'activeMenu' => 'aset',
            'akunAset' => $akunModel->whereIn('kategori', ['aktiva tetap', 'aktiva lancar lainya'])->findAll(),
            'akunKredit' => $akunModel->whereIn('kategori', ['kas & bank', 'akun hutang', 'ekuitas'])->findAll(),
            'akunAkumulasi' => $akunModel->where('kategori', 'depresiasi & amortisasi')->findAll(),
            'akunBeban' => $akunModel->where('kategori', 'beban')->findAll(),
            'asset' => null
        ];

        return view('keuangan/aset/form', $data);
    }

    public function edit($id)
    {
        $asetModel = new AsetModel();
        $akunModel = new AkunModel();
        
        $asset = $asetModel->find($id);
        if (!$asset) return redirect()->to('/keuangan/aset')->with('error', 'Aset tidak ditemukan');

        $data = [
            'title' => 'Edit Aset Tetap',
            'activeMenu' => 'aset',
            'akunAset' => $akunModel->whereIn('kategori', ['aktiva tetap', 'aktiva lancar lainya'])->findAll(),
            'akunKredit' => $akunModel->whereIn('kategori', ['kas & bank', 'akun hutang', 'ekuitas'])->findAll(),
            'akunAkumulasi' => $akunModel->where('kategori', 'depresiasi & amortisasi')->findAll(),
            'akunBeban' => $akunModel->where('kategori', 'beban')->findAll(),
            'asset' => $asset
        ];

        return view('keuangan/aset/form', $data);
    }

    public function save()
    {
        $asetModel = new AsetModel();
        $id = $this->request->getPost('id');

        $data = [
            'nama_aset' => $this->request->getPost('nama_aset'),
            'nomor_aset' => $this->request->getPost('nomor_aset'),
            'tanggal_pembelian' => $this->request->getPost('tanggal_pembelian'),
            'harga_beli' => $this->request->getPost('harga_beli'),
            'akun_aset_kode' => $this->request->getPost('akun_aset_kode'),
            'akun_kredit_kode' => $this->request->getPost('akun_kredit_kode'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'referensi' => $this->request->getPost('referensi'),
            'tag' => $this->request->getPost('tag'),
            'is_penyusutan' => $this->request->getPost('is_penyusutan') ? 1 : 0,
            'status' => $this->request->getPost('status') ?? 'Terdaftar',
        ];

        // If depreciation is enabled
        if ($data['is_penyusutan']) {
            $data['akun_akumulasi_kode'] = $this->request->getPost('akun_akumulasi_kode');
            $data['akun_penyusutan_kode'] = $this->request->getPost('akun_penyusutan_kode');
            $data['metode_penyusutan'] = $this->request->getPost('metode_penyusutan');
            $data['masa_manfaat'] = $this->request->getPost('masa_manfaat');
            $data['nilai_residu'] = $this->request->getPost('nilai_residu');
        }

        if ($id) {
            $asetModel->update($id, $data);
            $recordId = (int) $id;
            $msg = 'Aset berhasil diperbarui';
        } else {
            $recordId = (int) $asetModel->insert($data);
            $msg = 'Aset berhasil ditambahkan';
        }

        $row = $asetModel->find($recordId);
        $journal = new JournalService();

        if (($data['status'] ?? '') === 'Terdaftar') {
            $jr = $journal->postAsetRegistrasi($row);
            if (!$jr['ok']) {
                return redirect()->to('/keuangan/aset')->with('error', $msg . ' — jurnal aset: ' . ($jr['message'] ?? ''));
            }

            if (!empty($data['is_penyusutan']) && (int) ($data['masa_manfaat'] ?? 0) > 0) {
                $harga = (float) ($row['harga_beli'] ?? 0);
                $residu = (float) ($row['nilai_residu'] ?? 0);
                $bulan = max(1, (int) $data['masa_manfaat'] * 12);
                $nilaiBulan = ($harga - $residu) / $bulan;
                $tglDep = $row['tanggal_mulai_penyusutan'] ?? $row['tanggal_pembelian'] ?? date('Y-m-d');
                $tglDep = date('Y-m-t', strtotime($tglDep));
                $noDep = 'DEP-' . ($row['nomor_aset'] ?? $recordId) . '-' . date('Ym', strtotime($tglDep));
                if (!$journal->exists($noDep) && $nilaiBulan > 0) {
                    $jd = $journal->postAsetPenyusutan($row, round($nilaiBulan, 2), $tglDep);
                    if (!$jd['ok']) {
                        return redirect()->to('/keuangan/aset')->with('error', $msg . ' — penyusutan: ' . ($jd['message'] ?? ''));
                    }
                    $asetModel->update($recordId, [
                        'akumulasi_penyusutan' => round($nilaiBulan, 2),
                    ]);
                }
            }
        }

        return redirect()->to('/keuangan/aset')->with('success', $msg . ' (jurnal tercatat)');
    }

    /**
     * Posting penyusutan bulan berjalan untuk satu aset.
     */
    public function postPenyusutan($id)
    {
        $asetModel = new AsetModel();
        $asset = $asetModel->find($id);
        if (!$asset || empty($asset['is_penyusutan'])) {
            return redirect()->to('/keuangan/aset')->with('error', 'Aset tidak valid untuk penyusutan');
        }

        $harga = (float) ($asset['harga_beli'] ?? 0);
        $residu = (float) ($asset['nilai_residu'] ?? 0);
        $bulan = max(1, (int) ($asset['masa_manfaat'] ?? 1) * 12);
        $nilaiBulan = ($harga - $residu) / $bulan;
        $tgl = date('Y-m-t');

        $journal = new JournalService();
        $noDep = 'DEP-' . ($asset['nomor_aset'] ?? $id) . '-' . date('Ym');
        if ($journal->exists($noDep)) {
            return redirect()->to('/keuangan/aset')->with('error', 'Penyusutan bulan ini sudah diposting');
        }

        $jd = $journal->postAsetPenyusutan($asset, round($nilaiBulan, 2), $tgl);
        if (!$jd['ok']) {
            return redirect()->to('/keuangan/aset')->with('error', $jd['message'] ?? 'Gagal posting penyusutan');
        }

        $asetModel->update($id, [
            'akumulasi_penyusutan' => (float) ($asset['akumulasi_penyusutan'] ?? 0) + round($nilaiBulan, 2),
        ]);

        return redirect()->to('/keuangan/aset')->with('success', 'Penyusutan bulan ' . date('F Y') . ' berhasil diposting');
    }

    public function get($id)
    {
        $asetModel = new AsetModel();
        $asset = $asetModel->find($id);
        if ($asset) {
            return $this->response->setJSON(['status' => 'success', 'data' => $asset]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
    }

    public function delete($id)
    {
        $asetModel = new AsetModel();
        if (!$asetModel->find($id)) {
            return redirect()->back()->with('error', 'Aset tidak ditemukan');
        }
        
        $asetModel->delete($id);
        return redirect()->back()->with('success', 'Aset berhasil dihapus');
    }
}
