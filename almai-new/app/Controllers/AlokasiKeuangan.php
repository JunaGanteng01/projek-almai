<?php

namespace App\Controllers;

class AlokasiKeuangan extends BaseController
{
    public function index()
    {
        return view('pages/alokasi_keuangan', [
            'title' => 'Alokasi Keuangan Trading - Almai'
        ]);
    }
}
