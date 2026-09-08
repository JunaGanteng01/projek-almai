<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\KategoriLayananModel;
use App\Models\LayananArtikelModel;
use App\Models\LayananEventModel;
use App\Models\LayananToolsModel;
use App\Models\LayananSubscriptionModel;
use App\Models\LayananModel;
use App\Models\LayananPriceModel;

class Layanan extends BaseController
{
    protected $kategoriModel;
    protected $artikelModel;
    protected $eventModel;
    protected $toolsModel;
    protected $subscriptionModel;
    protected $layananModel;
    protected $priceModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriLayananModel();
        $this->artikelModel = new LayananArtikelModel();
        $this->eventModel = new LayananEventModel();
        $this->toolsModel = new LayananToolsModel();
        $this->subscriptionModel = new LayananSubscriptionModel();
        $this->layananModel = new LayananModel();
        $this->priceModel = new LayananPriceModel();
    }

    public function index()
    {
        $kategori = $this->request->getGet('kategori');
        $subcategory = $this->request->getGet('subcategory');

        $layananList = $this->getAllLayanan($kategori, $subcategory);

        $stats = [
            'total' => count($layananList),
            'artikel' => $this->artikelModel->countAllResults(),
            'event' => $this->eventModel->countAllResults(),
            'tools' => $this->toolsModel->countAllResults(),
            'subscription' => $this->subscriptionModel->countAllResults(),
        ];

        return view('laporan-kegiatan/layanan/index', [
            'title' => 'Data Layanan',
            'activeMenu' => 'layanan',
            'layananList' => $layananList,
            'kategoriList' => $this->kategoriModel->getActive(),
            'currentKategori' => $kategori,
            'currentSubcategory' => $subcategory,
            'stats' => $stats
        ]);
    }

    private function getAllLayanan($kategori = null, $subcategory = null)
    {
        $result = [];

        // Get from main layanan table
        $layananItems = $this->layananModel->getAllWithWpa($kategori);
        foreach ($layananItems as $item) {
            $item = $this->addPackageInfo($item, 'layanan');
            $result[] = array_merge($item, [
                'layanan_type' => 'layanan',
                'kategori' => $item['category'],
                'table_name' => 'layanan'
            ]);
        }

        // Get Artikel
        if (!$kategori || $kategori === 'advokasi') {
            if (!$subcategory || $subcategory === 'artikel') {
                $artikels = $this->artikelModel->getWithWpa();
                foreach ($artikels as $item) {
                    $item = $this->addPackageInfo($item, 'artikel');
                    $result[] = array_merge($item, [
                        'layanan_type' => 'artikel',
                        'kategori' => 'Advokasi',
                        'subcategory' => 'Artikel',
                        'table_name' => 'layanan_artikel'
                    ]);
                }
            }
        }

        // Get Events
        if (!$kategori || $kategori === 'advokasi') {
            $events = $this->eventModel->getWithWpa();
            foreach ($events as $item) {
                if (!$subcategory || $subcategory === $item['type']) {
                    $item = $this->addPackageInfo($item, $item['type']);
                    $result[] = array_merge($item, [
                        'layanan_type' => $item['type'],
                        'kategori' => 'Advokasi',
                        'subcategory' => ucfirst($item['type']),
                        'table_name' => 'layanan_event',
                        'name' => $item['title']
                    ]);
                }
            }
        }

        // Get Tools
        $tools = $this->toolsModel->findAll();
        foreach ($tools as $item) {
            $kat = $item['type'] === 'ea' ? 'expert-advisor' : 'almai-ultimate';
            $katLabel = $item['type'] === 'ea' ? 'Expert Advisor' : 'Almai Ultimate';
            if (!$kategori || $kategori === $kat) {
                if (!$subcategory || $subcategory === $item['type']) {
                    $item = $this->addPackageInfo($item, $item['type']);
                    $result[] = array_merge($item, [
                        'layanan_type' => $item['type'],
                        'kategori' => $katLabel,
                        'subcategory' => $item['type'] === 'ea' ? 'Expert Advisor' : 'Almai Toolkits',
                        'table_name' => 'layanan_tools'
                    ]);
                }
            }
        }

        // Get Subscriptions
        $subs = $this->subscriptionModel->getWithWpa();
        foreach ($subs as $item) {
            $kat = in_array($item['type'], ['pendampingan', 'profirm']) ? 'advokasi' : 'almai-ultimate';
            $katLabel = in_array($item['type'], ['pendampingan', 'profirm']) ? 'Advokasi' : 'Almai Ultimate';
            if (!$kategori || $kategori === $kat) {
                if (!$subcategory || $subcategory === $item['type']) {
                    $item = $this->addPackageInfo($item, $item['type']);
                    $result[] = array_merge($item, [
                        'layanan_type' => $item['type'],
                        'kategori' => $katLabel,
                        'subcategory' => $this->getSubcategoryLabel($item['type']),
                        'table_name' => 'layanan_subscription'
                    ]);
                }
            }
        }

        return $result;
    }

    private function addPackageInfo($item, $type)
    {
        $packages = $this->priceModel->where('layanan_type', $type)
            ->where('layanan_id', $item['id'])
            ->orderBy('price', 'ASC')
            ->findAll();

        if (!empty($packages)) {
            $item['has_packages'] = true;
            $item['packages'] = $packages;
            $item['min_price'] = $packages[0]['price'];
            $item['max_price'] = $packages[count($packages) - 1]['price'];
        } else {
            $item['has_packages'] = false;
        }

        return $item;
    }

    private function getSubcategoryLabel($type)
    {
        $labels = [
            'artikel' => 'Artikel',
            'webinar' => 'Webinar',
            'workshop' => 'Workshop',
            'pendampingan' => 'Pendampingan CWPA',
            'profirm' => 'Profirm',
            'live_trade' => 'Live Trade',
            'ea' => 'Expert Advisor',
            'toolkit' => 'Almai Toolkits',
            'private_konsultan' => 'Private Konsultan',
            'vip_member' => 'VIP Member',
        ];
        return $labels[$type] ?? ucfirst($type);
    }
}
