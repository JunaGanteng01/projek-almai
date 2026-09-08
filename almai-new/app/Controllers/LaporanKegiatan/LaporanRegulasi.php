<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\LaporanRegulasiModel;
use App\Models\CompanyProfileModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class LaporanRegulasi extends BaseController
{
    public function index()
    {
        $laporanModel = new LaporanRegulasiModel();
        $companyModel = new CompanyProfileModel();

        $jenis = $this->request->getGet('jenis') ?: 'bulanan';
        $bulan = $this->request->getGet('bulan') ?: date('m');
        $tahun = $this->request->getGet('tahun') ?: date('Y');

        $profile = $companyModel->getProfile();
        $identitas = $profile['identitas'] ?? [];

        $bulanListIndo = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        $data = [
            'title' => 'Laporan Regulasi - Almai',
            'activeMenu' => 'laporan-regulasi',
            'jenis' => $jenis,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bulanList' => $bulanListIndo,
            'namaPerusahaan' => $identitas['nama_perusahaan'] ?? 'PT Alma Indonesia Raya',
            'noIzin' => $identitas['nomor_izin_bappebti'] ?? '0005/UPTP/SI-WPA/2/2024'
        ];

        if ($jenis === 'bulanan') {
            $data['rekap'] = $laporanModel->getRekapBulanan($bulan, $tahun);
            $data['klien'] = $laporanModel->getDataKlien($bulan, $tahun);
            $data['listWpa'] = $laporanModel->getListWpa();
            
            $startDate = "$tahun-$bulan-01";
            $endDate = date('Y-m-t', strtotime($startDate));
            $data['detailSeminar'] = $laporanModel->getDetailActivities('seminar_fgd', $startDate, $endDate);
            $data['detailPelatihan'] = $laporanModel->getDetailActivities('pelatihan_simulasi', $startDate, $endDate);
            $data['detailSignal'] = $laporanModel->getDetailActivities('signals', $startDate, $endDate);
            $data['detailKonsultasi'] = $laporanModel->getDetailActivities('konsultasi', $startDate, $endDate);
        } else {
            $data['rekapTahunan'] = $laporanModel->getRekapTahunan($tahun);
            $data['klienBaru'] = $laporanModel->getKlienBaruBulanan($tahun);
            $data['klienAktif'] = $laporanModel->getDataKlien(date('m'), $tahun)['total_aktif'];
        }

        return view('laporan-kegiatan/laporan_regulasi/index', $data);
    }

    public function exportPdf()
    {
        $laporanModel = new LaporanRegulasiModel();
        $companyModel = new CompanyProfileModel();

        $jenis = $this->request->getGet('jenis') ?: 'bulanan';
        $bulan = $this->request->getGet('bulan') ?: date('m');
        $tahun = $this->request->getGet('tahun') ?: date('Y');

        $profile = $companyModel->getProfile();
        $identitas = $profile['identitas'] ?? [];
        $pejabat = $profile['pejabat'] ?? [];

        $bulanListIndo = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        $engMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $indoMonths = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tanggalDibuat = str_replace($engMonths, $indoMonths, date('d F Y, H:i:s'));

        $data = [
            'jenis' => $jenis,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bulanList' => $bulanListIndo,
            'namaPerusahaan' => !empty($identitas['nama_perusahaan']) ? $identitas['nama_perusahaan'] : 'PT Alma Indonesia Raya',
            'noIzin' => !empty($identitas['nomor_izin_bappebti']) ? $identitas['nomor_izin_bappebti'] : '0005/UPTP/SI-WPA/2/2024',
            'direktur' => !empty($pejabat['nama_dirut']) ? $pejabat['nama_dirut'] : 'Nama Direktur',
            'tanggalDibuat' => $tanggalDibuat
        ];

        if ($jenis === 'bulanan') {
            $data['rekap'] = $laporanModel->getRekapBulanan($bulan, $tahun);
            $data['klien'] = $laporanModel->getDataKlien($bulan, $tahun);
            $data['listWpa'] = $laporanModel->getListWpa();
            
            $startDate = "$tahun-$bulan-01";
            $endDate = date('Y-m-t', strtotime($startDate));
            $data['detailSeminar'] = $laporanModel->getDetailActivities('seminar_fgd', $startDate, $endDate);
            $data['detailPelatihan'] = $laporanModel->getDetailActivities('pelatihan_simulasi', $startDate, $endDate);
            $data['detailSignal'] = $laporanModel->getDetailActivities('signals', $startDate, $endDate);
            $data['detailKonsultasi'] = $laporanModel->getDetailActivities('konsultasi', $startDate, $endDate);
            
            $html = view('laporan-kegiatan/laporan_regulasi/pdf_bulanan', $data);
            $filename = 'Laporan_Kegiatan_Bulanan_' . $bulan . '_' . $tahun . '.pdf';
        } else {
            $data['rekapTahunan'] = $laporanModel->getRekapTahunan($tahun);
            $data['klienBaru'] = $laporanModel->getKlienBaruBulanan($tahun);
            $data['klienAktif'] = $laporanModel->getDataKlien(date('m'), $tahun)['total_aktif'];
            $html = view('laporan-kegiatan/laporan_regulasi/pdf_tahunan', $data);
            $filename = 'Laporan_Kegiatan_Tahunan_' . $tahun . '.pdf';
        }

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        // $dompdf->setPaper('A4', $jenis === 'tahunan' ? 'landscape' : 'portrait');
        $dompdf->render();

        $dompdf->stream($filename, ["Attachment" => true]);
    }
}
