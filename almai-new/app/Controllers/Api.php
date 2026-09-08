<?php

namespace App\Controllers;

use App\Models\LegalDocumentModel;
use CodeIgniter\RESTful\ResourceController;

class Api extends ResourceController
{
    protected $format = 'json';

    public function legalDocument($slug = null)
    {
        if (!$slug) {
            return $this->failNotFound('Slug tidak ditemukan');
        }

        $legalDocumentModel = new LegalDocumentModel();

        // First, try exact slug match
        $document = $legalDocumentModel->where('slug', $slug)->first();

        // If not found, try partial match (for long slugs)
        if (!$document) {
            $document = $legalDocumentModel->like('slug', $slug)->first();
        }

        // If still not found and slug is 'cwpa', get specific CWPA pendampingan document
        if (!$document && $slug === 'cwpa') {
            // Try to find by title with all keywords (AND conditions)
            $document = $legalDocumentModel
                ->like('title', 'PENDAMPINGAN', 'both')
                ->like('title', 'CALON', 'both')
                ->like('title', 'WAKIL', 'both')
                ->first();

            // If not found, try by slug pattern
            if (!$document) {
                $document = $legalDocumentModel
                    ->like('slug', 'surat-perjanjian-pendampingan', 'both')
                    ->first();
            }

            // Last resort: get type 'other' ordered by ID DESC (newest first)
            if (!$document) {
                $document = $legalDocumentModel
                    ->where('type', 'other')
                    ->orderBy('id', 'DESC')
                    ->first();
            }
        }

        if (!$document) {
            return $this->respond([
                'success' => false,
                'message' => 'Dokumen tidak ditemukan'
            ], 404);
        }

        return $this->respond([
            'success' => true,
            'data' => [
                'id' => $document['id'],
                'title' => $document['title'],
                'slug' => $document['slug'],
                'content' => $document['content'],
                'type' => $document['type']
            ]
        ]);
    }
}
