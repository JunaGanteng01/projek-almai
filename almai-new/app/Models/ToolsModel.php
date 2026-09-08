<?php

namespace App\Models;

use CodeIgniter\Model;

class ToolsModel extends Model
{
    protected $table = 'tools';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'wpa_id', 'name', 'slug', 'thumbnail', 'price', 'original_price',
        'category', 'platform', 'description', 'features', 'compatibility',
        'download_url', 'documentation_url', 'rating', 'sales', 'status'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function getActive()
    {
        return $this->where('status', 'active')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getByCategory($category)
    {
        return $this->where('status', 'active')
                    ->where('category', $category)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getByWpaId($wpaId)
    {
        return $this->where('wpa_id', $wpaId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function incrementSales($id)
    {
        return $this->set('sales', 'sales + 1', false)
                    ->where('id', $id)
                    ->update();
    }

    public function getCategories()
    {
        return ['Expert Advisor', 'Copier', 'Signal', 'Indicator', 'Toolkit', 'Prop Firm'];
    }

    public function getPlatforms()
    {
        return ['MT4', 'MT5', 'MT4/MT5', 'TradingView', 'Multi Exchange', 'Web App'];
    }
}
