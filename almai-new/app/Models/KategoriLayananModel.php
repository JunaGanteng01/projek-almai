<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriLayananModel extends Model
{
    protected $table = 'kategori_layanan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'slug', 'icon', 'description', 'sort_order', 'is_active'];
    protected $useTimestamps = true;

    public function getActive()
    {
        return $this->where('is_active', 1)->orderBy('sort_order', 'ASC')->findAll();
    }

    public function getSubcategories($kategoriSlug)
    {
        $subcategories = [
            'advokasi' => [
                ['value' => 'artikel', 'label' => 'Artikel', 'table' => 'layanan_artikel'],
                ['value' => 'webinar', 'label' => 'Webinar', 'table' => 'layanan_event'],
                ['value' => 'workshop', 'label' => 'Workshop', 'table' => 'layanan_event'],
                ['value' => 'pendampingan', 'label' => 'Pendampingan CWPA', 'table' => 'layanan_subscription'],
                ['value' => 'profirm', 'label' => 'Profirm', 'table' => 'layanan_subscription'],
            ],
            'expert-advisor' => [
                ['value' => 'ea', 'label' => 'Expert Advisor (EA)', 'table' => 'layanan_tools'],
            ],
            'almai-ultimate' => [
                ['value' => 'toolkit', 'label' => 'Almai Toolkits', 'table' => 'layanan_tools'],
                ['value' => 'private_konsultan', 'label' => 'Private Konsultan', 'table' => 'layanan_subscription'],
                ['value' => 'vip_member', 'label' => 'VIP Member', 'table' => 'layanan_subscription'],
            ],
        ];

        return $subcategories[$kategoriSlug] ?? [];
    }

    private static $namesCache = null;

    public static function getNameBySlug($slug, $default = null)
    {
        if (self::$namesCache === null) {
            try {
                $db = \Config\Database::connect();
                $rows = $db->table('kategori_layanan')->select('slug, name')->get()->getResultArray();
                self::$namesCache = [];
                foreach ($rows as $row) {
                    self::$namesCache[$row['slug']] = $row['name'];
                }
            } catch (\Exception $e) {
                self::$namesCache = [];
            }
        }
        
        return self::$namesCache[$slug] ?? ($default ?: ucfirst(str_replace('-', ' ', $slug)));
    }
}
