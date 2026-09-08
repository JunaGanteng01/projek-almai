<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class Throttler implements FilterInterface
{
    /**
     * This is a register of how many requests per second are allowed.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = Services::throttler();

        // Restrict by IP address
        // 1 request every 2 seconds (30 requests per minute)
        if ($throttler->check(md5($request->getIPAddress()), 30, MINUTE) === false) {
            return Services::response()->setStatusCode(429)->setBody('Too Many Requests. Please slow down.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
