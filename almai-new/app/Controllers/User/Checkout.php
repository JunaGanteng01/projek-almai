<?php

namespace App\Controllers\User;

use App\Controllers\Checkout as BaseCheckout;

class Checkout extends BaseCheckout
{
    protected $viewPath = 'user/checkout/index';

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Initialize parent (BaseController/Checkout)
        parent::initController($request, $response, $logger);

        // Force override the view path to ensure user dashboard layout is used
        $this->viewPath = 'user/checkout/index';
    }
}
