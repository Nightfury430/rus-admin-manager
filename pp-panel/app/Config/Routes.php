<?php

declare(strict_types=1);

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', static function () {
    return "";
});

$routes->group('admin-panel', static function ($routes) {
    $routes->group('', [
        'filter' => ['auth-setup', 'auth-required', 'auth-group:ap-admin,ap-user'],
        'namespace' => '\App\Controllers\AdminPanel',
    ], static function ($routes) {
        $routes->get('/', 'AdminPages::clients');
        $routes->get('users', 'AdminPages::users');
        $routes->get('partners', 'AdminPages::partners');
        $routes->get('tariffs', 'AdminPages::tariffs');
        $routes->get('services', 'AdminPages::services');
        $routes->get('info', 'AdminPages::info');
    });

    $routes->group('', [
        'filter' => ['auth-setup'],
        'namespace' => '\App\Controllers\AdminPanel',
    ], static function ($routes) {
        $routes->get('init', 'AdminPages::init');
        $routes->get('login', 'AdminPages::login');
        $routes->get('logout', 'AdminPages::logout');
    });
});

$routes->group('admin-panel/api', static function ($routes) {
    $routes->group('', [
        'filter' => ['auth-setup'],
        'namespace' => '\App\Controllers\AdminPanel\Api',
    ], static function ($routes) {
        $routes->post('auth/(:any)', 'Auth::route/$1');
        $routes->post('users/create-super-user', 'Users::postCreateSuperUser');
    });

    $routes->group('', [
        'filter' => ['auth-setup', 'auth-required', 'auth-group:ap-admin,ap-user'],
        'namespace' => '\App\Controllers\AdminPanel\Api',
    ], static function ($routes) {
        $routes->post('clients/(:any)', 'Clients::route/$1');
        $routes->get('clients/(:any)', 'Clients::route/$1');
        $routes->post('users/(:any)', 'Users::route/$1');
        $routes->get('users/(:any)', 'Users::route/$1');
    });
});
