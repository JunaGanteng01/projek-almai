<?php

namespace App\Libraries;

use CodeIgniter\Files\File;

class FileUploadService
{
    protected $lastError = '';

    /**
     * Handle file upload securely
     * 
     * @param \CodeIgniter\HTTP\Files\UploadedFile $file
     * @param int|string $userId
     * @param string $category
     * @return string|null The file path or null on failure
     */
    public function save($file, $userId, $category = 'document')
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            $this->lastError = 'File tidak valid atau sudah dipindahkan.';
            return null;
        }

        // Generate a secure random name
        $newName = $file->getRandomName();

        // Determine destination folder based on category
        $uploadPath = ROOTPATH . 'public/uploads/' . $category . '/' . $userId;
        
        // Ensure directory exists
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        try {
            $file->move($uploadPath, $newName);
            
            // Return relative path for database storage
            return 'uploads/' . $category . '/' . $userId . '/' . $newName;
        } catch (\Exception $e) {
            $this->lastError = $e->getMessage();
            return null;
        }
    }

    /**
     * Get the last error message
     */
    public function getLastError()
    {
        return $this->lastError;
    }
}
