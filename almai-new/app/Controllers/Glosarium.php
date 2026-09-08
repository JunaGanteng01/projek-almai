<?php

namespace App\Controllers;

use App\Models\GlossaryModel;

class Glosarium extends BaseController
{
    protected $glossaryModel;

    public function __construct()
    {
        $this->glossaryModel = new GlossaryModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $letter = $this->request->getGet('letter');

        // Default empty result
        $groupedGlossaries = [];
        $isFiltered = false;

        // Only fetch data if filter is active (search or letter)
        if ($search || $letter) {
            $isFiltered = true;
            $builder = $this->glossaryModel->orderBy('term', 'ASC');

            // Filter by search
            if ($search) {
                $builder->groupStart()
                    ->like('term', $search)
                    ->orLike('definition', $search)
                    ->orLike('short_description', $search)
                    ->groupEnd();
            }

            // Filter by first letter
            if ($letter) {
                $builder->like('term', $letter, 'after');
            }

            $glossaries = $builder->findAll();

            // Group by first letter
            foreach ($glossaries as $glossary) {
                $firstLetter = strtoupper(substr($glossary->term, 0, 1));
                if (!isset($groupedGlossaries[$firstLetter])) {
                    $groupedGlossaries[$firstLetter] = [];
                }
                $groupedGlossaries[$firstLetter][] = $glossary;
            }

            ksort($groupedGlossaries);
        }

        // Build dynamic title and description based on filter
        $pageTitle = 'Glosarium Trading Lengkap A-Z | Kamus Istilah Forex, Saham & Crypto - Almai';
        $metaDescription = 'Glosarium Trading Lengkap A-Z - Kamus istilah forex, saham, cryptocurrency, dan investasi dalam Bahasa Indonesia. Pelajari 500+ definisi trading untuk pemula hingga profesional.';
        $metaKeywords = 'glosarium trading, kamus trading, istilah forex, istilah saham, istilah crypto, definisi trading, terminologi investasi';

        if ($letter) {
            $pageTitle = "Glosarium Trading Huruf {$letter} | Kamus Istilah Trading - Almai";
            $metaDescription = "Daftar istilah trading huruf {$letter} - Pelajari definisi lengkap istilah forex, saham, dan cryptocurrency yang dimulai dengan huruf {$letter}.";
        } elseif ($search) {
            $pageTitle = "Hasil Pencarian '{$search}' | Glosarium Trading - Almai";
            $metaDescription = "Hasil pencarian istilah trading '{$search}' di Glosarium Almai. Temukan definisi dan penjelasan lengkap untuk istilah trading yang Anda cari.";
        }

        return view('pages/glosarium', [
            'title' => $pageTitle,
            'metaDescription' => $metaDescription,
            'metaKeywords' => $metaKeywords,
            'canonicalUrl' => base_url('glosarium'),
            'glossaries' => $groupedGlossaries,
            'search' => $search,
            'selectedLetter' => $letter,
            'isFiltered' => $isFiltered
        ]);
    }
    public function detail($slug)
    {
        $glossary = $this->glossaryModel->where('slug', $slug)->first();

        if (!$glossary) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('pages/glosarium_detail', [
            'title' => $glossary->term . ' - Glosarium Almai',
            'glossary' => $glossary
        ]);
    }
}
