<?php

namespace App\Filters;

use App\Libraries\JwtLibrary;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class JwtFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (empty($authHeader)) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Token tidak ditemukan. Silakan login.']);
        }

        $jwt = new JwtLibrary();
        $token = $jwt->extractBearerToken($authHeader);

        if (!$token) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Format token tidak valid.']);
        }

        $payload = $jwt->getPayload($token);

        if (!$payload) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Token expired atau tidak valid. Silakan login ulang.']);
        }

        if (($payload['type'] ?? '') !== 'access') {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Tipe token tidak valid.']);
        }

        // Simpan user info ke request untuk dipakai di controller
        $request->jwtUser = $payload;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tambahkan CORS headers untuk mobile
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->setHeader('Access-Control-Allow-Headers', 'Authorization, Content-Type, X-Requested-With');
    }
}
