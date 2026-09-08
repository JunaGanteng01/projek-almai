<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\PedomanPerilakuModel;
use App\Models\WpaModel;
use App\Models\TransaksiModel;

class PedomanPerilaku extends BaseController
{
    protected $pedomanModel;
    protected $wpaModel;
    protected $transaksiModel;

    public function __construct()
    {
        $this->pedomanModel = new PedomanPerilakuModel();
        $this->wpaModel = new WpaModel();
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        $wpaId = $this->request->getGet('wpa_id');
        $keyword = $this->request->getGet('keyword');

        $data = $this->pedomanModel->getPaginatedData(20, $wpaId, $keyword);
        
        return view('laporan-kegiatan/pedoman-perilaku/index', [
            'title' => 'Pedoman Perilaku - Almai',
            'activeMenu' => 'pedoman-perilaku',
            'pedomanData' => $data['data'],
            'pager_links' => $data['pager_links'],
            'current_page' => $this->request->getGet('page') ?? 1,
            'wpaList' => $this->wpaModel->findAll(),
            'selectedWpa' => $wpaId,
            'keyword' => $keyword
        ]);
    }

    public function edit($transaksiId)
    {
        $transaksi = $this->transaksiModel->find($transaksiId);
        if (!$transaksi) {
            return redirect()->to('/laporan-kegiatan/pedoman-perilaku')->with('error', 'Transaksi tidak ditemukan');
        }

        $pedoman = $this->pedomanModel->where('transaksi_id', $transaksiId)->first();
        if (!$pedoman) {
            // Create empty placeholder if not exists
            $pedoman = [
                'transaksi_id' => $transaksiId,
                'no_akun' => '',
                'kuesioner_latar_belakang' => '',
                'kuesioner_profil_risiko' => '',
                'perjanjian_jasa_penjelasan' => '',
                'perjanjian_jasa_ttd' => '',
                'no_dok_perjanjian' => '',
                'ket_perusahaan_penjelasan' => '',
                'ket_perusahaan_ttd' => '',
                'pernyataan_risiko_penjelasan' => '',
                'pernyataan_risiko_ttd' => '',
                'keterangan' => ''
            ];
        }

        return view('laporan-kegiatan/pedoman-perilaku/form', [
            'title' => 'Edit Pedoman Perilaku',
            'activeMenu' => 'pedoman-perilaku',
            'pedoman' => $pedoman,
            'transaksi' => $transaksi
        ]);
    }

    public function update($transaksiId)
    {
        $data = [
            'transaksi_id' => $transaksiId,
            'no_akun' => $this->request->getPost('no_akun'),
            'kuesioner_latar_belakang' => $this->request->getPost('kuesioner_latar_belakang'),
            'kuesioner_profil_risiko' => $this->request->getPost('kuesioner_profil_risiko'),
            'perjanjian_jasa_penjelasan' => $this->request->getPost('perjanjian_jasa_penjelasan'),
            'perjanjian_jasa_ttd' => $this->request->getPost('perjanjian_jasa_ttd'),
            'no_dok_perjanjian' => $this->request->getPost('no_dok_perjanjian'),
            'ket_perusahaan_penjelasan' => $this->request->getPost('ket_perusahaan_penjelasan'),
            'ket_perusahaan_ttd' => $this->request->getPost('ket_perusahaan_ttd'),
            'pernyataan_risiko_penjelasan' => $this->request->getPost('pernyataan_risiko_penjelasan'),
            'pernyataan_risiko_ttd' => $this->request->getPost('pernyataan_risiko_ttd'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $existing = $this->pedomanModel->where('transaksi_id', $transaksiId)->first();
        if ($existing) {
            $this->pedomanModel->update($existing['id'], $data);
        } else {
            $this->pedomanModel->insert($data);
        }

        return redirect()->to('/laporan-kegiatan/pedoman-perilaku')->with('success', 'Data Pedoman Perilaku berhasil diupdate');
    }

    public function downloadPerjanjian($invoiceNumber)
    {
        return $this->generatePdfResponse($invoiceNumber, 'generatePerjanjianPdf', 'Perjanjian');
    }

    public function downloadProfilRisiko($invoiceNumber)
    {
        return $this->generatePdfResponse($invoiceNumber, 'generateProfilPerusahaanPdf', 'Ket_Perusahaan');
    }

    public function downloadPernyataanRisiko($invoiceNumber)
    {
        return $this->generatePdfResponse($invoiceNumber, 'generateRisikoPdf', 'Risiko');
    }

    private function generatePdfResponse($invoiceNumber, $method, $filenamePrefix)
    {
        $transaksi = $this->transaksiModel->where('invoice_number', $invoiceNumber)->first();
        if (!$transaksi) return redirect()->back()->with('error', 'Transaksi tidak ditemukan');

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($transaksi['user_id']);

        $layananData = (new \App\Controllers\User\Dashboard())->getLayananDataForLegal($transaksi);
        $wpaName = $layananData['wpa_name'] ?? 'Tim Almai';

        $legalPdfService = new \App\Libraries\LegalDocumentPdfService();

        if ($method === 'generatePerjanjianPdf') {
            $pdf = $legalPdfService->generatePerjanjianPdf($transaksi, $user, $layananData, $wpaName);
        } elseif ($method === 'generateProfilPerusahaanPdf') {
            $pdf = $legalPdfService->generateProfilPerusahaanPdf($transaksi, $user);
        } elseif ($method === 'generateRisikoPdf') {
            $pdf = $legalPdfService->generateRisikoPdf($transaksi, $user, $layananData);
        }

        if (empty($pdf)) return redirect()->back()->with('error', 'Gagal membuat file PDF');

        if (ob_get_level() > 0) ob_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="'.$filenamePrefix.'_' . $invoiceNumber . '.pdf"')
            ->setBody($pdf);
    }
}
