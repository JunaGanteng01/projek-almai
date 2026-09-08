<?php

namespace App\Libraries;

use Dompdf\Dompdf;

class BankIndonesiaPdfGenerator
{
    public function generate(array $reportData)
    {
        $html = view('keuangan/laporan/bi_pdf', $reportData);
        
        $dompdf = new Dompdf();
        
        // Dompdf Options for better rendering
        $options = $dompdf->getOptions();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $output = $dompdf->output();
        
        // Return response using CodeIgniter's Response service to prevent raw output bug
        $response = service('response');
        return $response->setHeader('Content-Type', 'application/pdf')
                        ->setHeader('Content-Disposition', 'inline; filename="Laporan_Bank_Indonesia_' . $reportData['tahunBerjalan'] . '.pdf"')
                        ->setBody($output);
    }
}
