<?php
use App\Controllers\Auth;
use App\Controllers\Home;
use App\Controllers\Admin;
use App\Controllers\TestEmail;

// Authentication routes
$routes->get('/', [Auth::class, 'index']);
$routes->post('auth/ajaxLogin', [Auth::class, 'ajaxLogin']);
$routes->post('auth/ajaxSignup', [Auth::class, 'ajaxSignup']);
$routes->post('doLogin', [Auth::class, 'doLogin']); // Keep for fallback
$routes->post('signup', [Auth::class, 'signup']); // Keep for fallback
$routes->get('logout', [Auth::class, 'logout']);
$routes->get('auth/verify', [Auth::class, 'verify']);
$routes->post('auth/verify', [Auth::class, 'verify']);

// Home routes
$routes->get('home', [Home::class, 'index']);
$routes->post('book', [Home::class, 'book']);

// Admin routes
$routes->group('admin', function($routes) {
    $routes->get('/', [Admin::class, 'index']);
    $routes->get('dashboard', [Admin::class, 'dashboard']);
    $routes->get('roombook', [Admin::class, 'roombook']);
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


// Fallback route
$routes->get('(:any)', function() {
    return redirect()->to('/');
});