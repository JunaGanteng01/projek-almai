<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = ['url', 'form', 'text', 'cookie'];
    protected $session;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
    }

    protected function isLoggedIn(): bool
    {
        return $this->session->get('isLoggedIn') === true;
    }

    protected function getCurrentUser(): ?array
    {
        if (!$this->isLoggedIn()) {
            return null;
        }
        return [
            'id' => $this->session->get('userId'),
            'name' => $this->session->get('userName'),
            'email' => $this->session->get('userEmail'),
            'role' => $this->session->get('userRole'),
        ];
    }

    /**
     * Check if current user has write access (Super Admin only)
     * Admin (Level 5) and Accounting (Level 6) are read-only
     */
    protected function canWriteAdmin(): bool
    {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        $levelId = (int)$this->session->get('level_id');
        
        // Only Super Admin (7) has write access
        return $levelId === \App\Models\LevelModel::LEVEL_SUPER_ADMIN;
    }
}
