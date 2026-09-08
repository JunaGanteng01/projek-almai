<?php

namespace App\Controllers;

class Halaman extends BaseController
{
    public function index()
    {
        return view('pages/halaman', [
            'title' => 'Halaman - Almai',
        ]);
    }
}
