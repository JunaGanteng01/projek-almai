<?php

namespace App\Models;

use CodeIgniter\Model;

class LegalDocumentModel extends Model
{
    protected $table = 'legal_documents';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['title', 'slug', 'content', 'type', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'slug' => 'required|alpha_dash',
        'content' => 'required',
        'type' => 'required|in_list[perjanjian,risiko,other]',
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Judul dokumen harus diisi',
            'min_length' => 'Judul minimal 3 karakter',
        ],
        'slug' => [
            'required' => 'Slug harus diisi',
            'alpha_dash' => 'Slug hanya boleh berisi huruf, angka, dash dan underscore',
            'is_unique' => 'Slug sudah digunakan',
        ],
        'content' => [
            'required' => 'Konten dokumen harus diisi',
        ],
        'type' => [
            'required' => 'Tipe dokumen harus dipilih',
            'in_list' => 'Tipe dokumen tidak valid',
        ],
    ];

    /**
     * Get active documents
     */
    public function getActive()
    {
        return $this->where('is_active', 1)->findAll();
    }

    /**
     * Get document by slug
     */
    public function getBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }

    /**
     * Get documents by type
     */
    public function getByType($type)
    {
        return $this->where('type', $type)
            ->where('is_active', 1)
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    /**
     * Generate unique slug
     */
    public function generateSlug($title, $id = null)
    {
        $slug = url_title($title, '-', true);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = $this->where('slug', $slug);
            if ($id) {
                $query->where('id !=', $id);
            }
            $existing = $query->first();

            if (!$existing) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
