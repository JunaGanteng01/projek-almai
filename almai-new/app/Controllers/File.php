<?php

namespace App\Controllers;

class File extends BaseController
{
    public function serveAvatar($filename)
    {
        return $this->serveFile('uploads/avatars/' . $filename);
    }

    public function serveKyc($filename)
    {
        return $this->serveFile('uploads/kyc/' . $filename);
    }

    public function serveTransferProof($filename)
    {
        return $this->serveFile('uploads/transfer_proofs/' . $filename);
    }

    public function serve(...$segments)
    {
        $path = implode(DIRECTORY_SEPARATOR, $segments);
        return $this->serveFile($path);
    }

    private function serveFile($path)
    {
        // Normalize slashes for Windows/Linux
        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, ltrim($path, '/\\'));

        $writePath = rtrim(WRITEPATH, '/\\');
        $fcPath = rtrim(FCPATH, '/\\');

        $filePath = $writePath . DIRECTORY_SEPARATOR . $path;

        // If not found in writable, check public folder (FCPATH)
        if (!file_exists($filePath) || !is_file($filePath)) {
            $filePath = $fcPath . DIRECTORY_SEPARATOR . $path;
        }

        if (!file_exists($filePath) || !is_file($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("File not found: " . $path);
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // List of image mimes that should be rendered in browser
        // SECURITY: SVG dihapus dari inline rendering — bisa mengandung JavaScript
        $imageMimes = [
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'gif'  => 'image/gif',
            'webp' => 'image/webp',
            // 'svg' dihapus: SVG dapat mengandung <script> yang tereksekusi di browser
        ];

        // If it's an image, serve it with inline content-type
        if (array_key_exists($extension, $imageMimes)) {
            return $this->response
                ->setHeader('Content-Type', $imageMimes[$extension])
                ->setHeader('Cache-Control', 'public, max-age=86400')
                ->setBody(file_get_contents($filePath));
        }

        // For other files, trigger download
        // SECURITY: Hanya izinkan ekstensi dokumen dan EA file — hapus executable & archive berbahaya
        $allowedExtensions = [
            'pdf',
            'doc',
            'docx',
            'xls',
            'xlsx',
            'ppt',
            'pptx',
            'ex4',
            'ex5',
            'mq4',
            'mq5',
            'txt',
            'csv'
            // DIHAPUS: 'zip', 'rar', '7z', 'exe' — potensi download malware
        ];

        if (in_array($extension, $allowedExtensions)) {
            return $this->response->download($filePath, null);
        }

        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("File type not allowed: " . $extension);
    }
}
