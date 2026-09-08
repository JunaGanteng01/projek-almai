<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtikelPurchaseModel extends Model
{
    protected $table = 'artikel_purchases';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'artikel_id', 'poin_spent'];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function hasPurchased($userId, $artikelId)
    {
        return $this->where('user_id', $userId)
                    ->where('artikel_id', $artikelId)
                    ->first() !== null;
    }

    public function getPurchasedByUser($userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }

    public function getPurchasedArtikelIds($userId)
    {
        $purchases = $this->where('user_id', $userId)->findAll();
        return array_column($purchases, 'artikel_id');
    }
}
