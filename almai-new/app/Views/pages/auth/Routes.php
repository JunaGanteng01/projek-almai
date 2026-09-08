<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Public Pages
$routes->get('layanan', 'Layanan::index');
$routes->get('layanan/detail/(:segment)', 'Layanan::detail/$1');
$routes->get('layanan/(:segment)', 'Layanan::detail/$1'); // Backward compatibility for old URLs
$routes->get('wpa', 'Wpa::index');
$routes->get('wpa/(:segment)', 'Wpa::detail/$1');
$routes->get('artikel', 'Artikel::index');
$routes->get('artikel/kategori/(:segment)', 'Artikel::index');
$routes->get('artikel/(:segment)', 'Artikel::detail/$1');
$routes->get('tentang-kami', 'Pages::tentangKami');
$routes->get('syarat-ketentuan', 'Pages::syaratKetentuan');
$routes->get('kebijakan-privasi', 'Pages::kebijakanPrivasi');
$routes->get('faq', 'Pages::faq');
$routes->get('kontak', 'Pages::kontak');

// Authentication Routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('register', 'Auth::login');
$routes->get('logout', 'Auth::logout');
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::processForgotPassword');
$routes->get('reset-password', 'Auth::resetPassword');
$routes->post('reset-password', 'Auth::processResetPassword');

// User Dashboard Routes
$routes->group('user', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'User\Dashboard::index');
    $routes->get('profile', 'User\Profile::index');
    $routes->post('profile/update', 'User\Profile::update');
    $routes->post('profile/change-password', 'User\Profile::changePassword');
});
