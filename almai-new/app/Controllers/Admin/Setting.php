<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class Setting extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        $settings = $this->settingModel->getAllAsArray();

        return view('admin/setting/index', [
            'title' => 'Settings - Admin Dashboard',
            'pageTitle' => 'Settings',
            'pageSubtitle' => 'Pengaturan sistem',
            'settings' => $settings,
            'activeMenu' => 'setting'
        ]);
    }

    public function update()
    {
        $fields = [
            'site_name' => ['type' => 'text', 'group' => 'general'],
            'site_description' => ['type' => 'textarea', 'group' => 'general'],
            'whatsapp_cs' => ['type' => 'text', 'group' => 'contact'],
            'email_cs' => ['type' => 'text', 'group' => 'contact'],
            'instagram' => ['type' => 'text', 'group' => 'social'],
            'youtube' => ['type' => 'text', 'group' => 'social'],
            'telegram' => ['type' => 'text', 'group' => 'social'],
            // Poin settings
            // Poin settings
            'poin_referral_registration' => ['type' => 'number', 'group' => 'poin'],
            'poin_new_user_referral_bonus' => ['type' => 'number', 'group' => 'poin'],
            'poin_review_bonus' => ['type' => 'number', 'group' => 'poin'],
            'poin_mlm_percentage' => ['type' => 'number', 'group' => 'poin'],
            'poin_mlm_depth' => ['type' => 'number', 'group' => 'poin'],
            'poin_wpa_commission_percent' => ['type' => 'number', 'group' => 'poin'],
            'poin_minimum_redeem' => ['type' => 'number', 'group' => 'poin'],
        ];

        foreach ($fields as $key => $config) {
            $value = $this->request->getPost($key);
            if ($value !== null) {
                $this->settingModel->setValue($key, $value, $config['type'], $config['group']);
            }
        }

        return redirect()->to('/admin/setting')->with('success', 'Settings berhasil disimpan!');
    }

    public function vouchers()
    {
        $vouchers = $this->settingModel->get('vouchers', '[]');
        $vouchers = json_decode($vouchers, true) ?: [];

        return view('admin/setting/vouchers', [
            'title' => 'Kelola Voucher - Admin Dashboard',
            'pageTitle' => 'Kelola Voucher',
            'pageSubtitle' => 'Buat dan kelola voucher diskon',
            'vouchers' => $vouchers,
            'activeMenu' => 'setting'
        ]);
    }

    public function saveVoucher()
    {
        $code = strtoupper($this->request->getPost('code'));
        $discount = $this->request->getPost('discount');
        $type = $this->request->getPost('type'); // percent or fixed
        $maxUse = $this->request->getPost('max_use');
        $expiry = $this->request->getPost('expiry');

        $vouchers = $this->settingModel->get('vouchers', '[]');
        $vouchers = json_decode($vouchers, true) ?: [];

        $vouchers[$code] = [
            'discount' => $discount,
            'type' => $type,
            'max_use' => $maxUse,
            'used' => 0,
            'expiry' => $expiry,
            'active' => true
        ];

        $this->settingModel->setValue('vouchers', json_encode($vouchers), 'json', 'voucher');

        return redirect()->to('/admin/setting/vouchers')->with('success', 'Voucher berhasil ditambahkan!');
    }

    public function deleteVoucher($code)
    {
        $vouchers = $this->settingModel->get('vouchers', '[]');
        $vouchers = json_decode($vouchers, true) ?: [];

        if (isset($vouchers[$code])) {
            unset($vouchers[$code]);
            $this->settingModel->setValue('vouchers', json_encode($vouchers), 'json', 'voucher');
        }

        return redirect()->to('/admin/setting/vouchers')->with('success', 'Voucher berhasil dihapus!');
    }

    public function terms()
    {
        $terms = $this->settingModel->get('terms_and_conditions', '');

        return view('admin/setting/terms', [
            'title' => 'Syarat & Ketentuan - Admin Dashboard',
            'pageTitle' => 'Syarat & Ketentuan',
            'pageSubtitle' => 'Kelola konten halaman syarat dan ketentuan',
            'terms' => $terms,
            'activeMenu' => 'syarat_ketentuan'
        ]);
    }

    public function updateTerms()
    {
        $terms = $this->request->getPost('terms');
        $this->settingModel->setValue('terms_and_conditions', $terms, 'html', 'pages');

        return redirect()->back()->with('success', 'Syarat & Ketentuan berhasil diperbarui!');
    }
}
