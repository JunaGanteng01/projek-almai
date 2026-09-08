<?php

namespace App\Controllers;

class Tutorial extends BaseController
{
    public function aiwe()
    {
        $data = [
            'title' => 'Tutorial AIWE - Almai E-Learning',
            'meta_title' => 'Tutorial Penggunaan AIWE',
            'meta_description' => 'Panduan lengkap cara menggunakan AIWE (Artificial Intelligence Wealth Expert) untuk mengoptimalkan trading Anda.',
        ];

        return view('tutorial/aiwe', $data);
    }
}
