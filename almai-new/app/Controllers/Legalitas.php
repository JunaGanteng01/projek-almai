<?php

namespace App\Controllers;

class Legalitas extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Legalitas & Izin Usaha - Almai',
            'meta_title' => 'Legalitas & Izin Usaha Resmi - Almai | BAPPEBTI & Kominfo',
            'meta_description' => 'Lihat dokumen legalitas resmi Almai termasuk izin Penasihat Berjangka BAPPEBTI, PSE Kominfo, dan kepatuhan regulasi lainnya.',
            'meta_image' => base_url('images/logo.png?v=3'), // Default fallback
            'documents' => [
                [
                    'title' => 'Persetujuan Bank Indonesia',
                    'number' => '-',
                    'image' => 'bi.jpg',
                    'description' => 'Persetujuan operasional terkait layanan keuangan digital.'
                ],
                [
                    'title' => 'Persetujuan OJK',
                    'number' => '-',
                    'image' => 'ojk.jpg',
                    'description' => 'Tanda terdaftar dan diawasi oleh Otoritas Jasa Keuangan.'
                ],
                [
                    'title' => 'Izin Penasihat Berjangka dan Expert Advisor',
                    'number' => '',
                    'image' => 'Izin-Penasihat-Berjangka&EA.jpg',
                    'description' => 'Izin resmi sebagai Penasihat Berjangka dan Expert Advisor dari BAPPEBTI.'
                ],
          
            ]
        ];

        return view('pages/legalitas/index', $data);
    }

    public function perjanjianPemberianJasa()
    {
        $data = [
            'title' => 'Perjanjian Pemberian Jasa - Almai',
            'meta_title' => 'Perjanjian Pemberian Jasa - Almai',
            'meta_description' => 'Syarat dan ketentuan perjanjian pemberian jasa layanan Almai.',
        ];

        return view('pages/legalitas/perjanjian', $data);
    }

    public function dokumenPemberitahuanRisiko()
    {
        $data = [
            'title' => 'Dokumen Pemberitahuan Adanya Risiko - Almai',
            'meta_title' => 'Dokumen Pemberitahuan Adanya Risiko - Almai',
            'meta_description' => 'Pemberitahuan risiko dalam perdagangan berjangka komoditi.',
        ];

        return view('pages/legalitas/risiko', $data);
    }
}
