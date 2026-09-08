<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class SakEtapPdfGenerator
{
    private function renderPdf($html, $filename)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $output = $dompdf->output();
        
        $response = service('response');
        return $response->setHeader('Content-Type', 'application/pdf')
                        ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
                        ->setBody($output);
    }

    public function generateNeracaPdf(array $neraca, string $tahunBuku, string $bulanBuku)
    {
        $data = ['neraca' => $neraca, 'tahunBuku' => $tahunBuku, 'bulanBuku' => $bulanBuku];
        // Menggunakan view yang ada atau bisa dibuat versi khusus PDF nanti
        $html = view('keuangan/sak-etap/neraca', $data); 
        return $this->renderPdf($html, "Neraca_{$tahunBuku}_{$bulanBuku}.pdf");
    }

    public function generateLabaRugiPdf(array $labaRugi, string $tahunBuku, string $bulanBuku)
    {
        $data = ['labaRugi' => $labaRugi, 'tahunBuku' => $tahunBuku, 'bulanBuku' => $bulanBuku];
        $html = view('keuangan/sak-etap/laba-rugi', $data);
        return $this->renderPdf($html, "Laba_Rugi_{$tahunBuku}_{$bulanBuku}.pdf");
    }

    public function generateArusKasPdf(array $arusKas, string $tahunBuku, string $bulanBuku)
    {
        $data = ['arusKas' => $arusKas, 'tahunBuku' => $tahunBuku, 'bulanBuku' => $bulanBuku];
        $html = view('keuangan/sak-etap/pdf/arus-kas', $data);
        return $this->renderPdf($html, "Arus_Kas_{$tahunBuku}_{$bulanBuku}.pdf");
    }

    public function generatePerubahanEkuitasPdf(array $perubahanEkuitas, string $tahunBuku, string $bulanBuku)
    {
        $data = ['perubahanEkuitas' => $perubahanEkuitas, 'tahunBuku' => $tahunBuku, 'bulanBuku' => $bulanBuku];
        $html = view('keuangan/sak-etap/perubahan-ekuitas', $data);
        return $this->renderPdf($html, "Perubahan_Ekuitas_{$tahunBuku}_{$bulanBuku}.pdf");
    }

    public function generateLaporanLengkapPdf(array $neraca, array $labaRugi, array $arusKas, array $perubahanEkuitas, string $tahunBuku, string $bulanBuku)
    {
        $data = [
            'neraca' => $neraca,
            'labaRugi' => $labaRugi,
            'arusKas' => $arusKas,
            'perubahanEkuitas' => $perubahanEkuitas,
            'tahunBuku' => $tahunBuku,
            'bulanBuku' => $bulanBuku
        ];
        // This view might not exist, but we implement the method anyway
        $html = "<h1>Laporan Lengkap</h1><p>Generate view for Laporan Lengkap PDF...</p>"; 
        return $this->renderPdf($html, "Laporan_Keuangan_Lengkap_{$tahunBuku}_{$bulanBuku}.pdf");
    }
}
