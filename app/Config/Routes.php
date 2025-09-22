<?php
namespace Config;

use App\Controllers\Auth;
use App\Controllers\Home;
use App\Controllers\Admin;

$routes = Services::routes();

$routes->get('/', 'Auth::index');
$routes->post('auth/ajaxLogin', 'Auth::ajaxLogin');
$routes->post('auth/ajaxSignup', 'Auth::ajaxSignup');
$routes->get('auth/logout', 'Auth::logout');

$routes->group('home', ['filter' => 'auth:user'], function($routes) {
    $routes->get('/', [Home::class, 'index']);
    $routes->post('book', [Home::class, 'book']);
});

$routes->group('admin', ['filter' => 'auth:staff'], function($routes) {
    $routes->get('/', [Admin::class, 'index']);
    $routes->get('dashboard', [Admin::class, 'dashboard']);
    $routes->get('chartdata', [Admin::class, 'getChartData']);
    $routes->get('roombook', [Admin::class, 'roombook']);
    $routes->post('addroombook', [Admin::class, 'addroombook']);
    $routes->get('payment', [Admin::class, 'payment']);
    $routes->get('room', [Admin::class, 'room']);
    $routes->get('staff', [Admin::class, 'staff']);
    $routes->post('addroom', [Admin::class, 'addroom']);
    $routes->get('roomdelete/(:num)', [Admin::class, 'roomdelete/$1']);
    $routes->get('roombookdelete/(:num)', [Admin::class, 'roombookdelete/$1']);
    $routes->get('paymantdelete/(:num)', [Admin::class, 'paymantdelete/$1']);
    $routes->get('invoiceprint/(:num)', [Admin::class, 'invoiceprint/$1']);
    $routes->post('exportdata', [Admin::class, 'exportdata']);
    $routes->get('roomconfirm/(:num)', [Admin::class, 'roomconfirm/$1']);
    $routes->get('roombookedit/(:num)', [Admin::class, 'roombookedit/$1']);
    $routes->post('roombookupdate/(:num)', [Admin::class, 'roombookupdate/$1']);
    $routes->get('staffdelete/(:num)', [Admin::class, 'staffdelete/$1']);
    $routes->post('addstaff', [Admin::class, 'addstaff']);
});

$routes->get('(:any)', function() {
    return redirect()->to('/');
});