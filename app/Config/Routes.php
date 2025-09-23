<?php
namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Authentication Routes
$routes->get('/', 'Auth::index');
$routes->match(['post'], 'auth/ajaxLogin', 'Auth::ajaxLogin');
$routes->match(['post'], 'auth/ajaxSignup', 'Auth::ajaxSignup');
$routes->get('auth/logout', 'Auth::logout');

// Home Routes (for regular users)
$routes->group('home', function($routes) {
    $routes->get('/', 'Home::index');
    $routes->post('book', 'Home::book');
});

// Admin Routes (for staff)
$routes->group('admin', function($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('chartdata', 'Admin::getChartData');
    $routes->get('roombook', 'Admin::roombook');
    $routes->post('addroombook', 'Admin::addroombook');
    $routes->get('payment', 'Admin::payment');
    $routes->get('room', 'Admin::room');
    $routes->get('staff', 'Admin::staff');
    $routes->post('addroom', 'Admin::addroom');
    $routes->get('roomdelete/(:num)', 'Admin::roomdelete/$1');
    $routes->get('roombookdelete/(:num)', 'Admin::roombookdelete/$1');
    $routes->get('paymantdelete/(:num)', 'Admin::paymantdelete/$1');
    $routes->get('invoiceprint/(:num)', 'Admin::invoiceprint/$1');
    $routes->post('exportdata', 'Admin::exportdata');
    $routes->get('roomconfirm/(:num)', 'Admin::roomconfirm/$1');
    $routes->get('roombookedit/(:num)', 'Admin::roombookedit/$1');
    $routes->post('roombookupdate/(:num)', 'Admin::roombookupdate/$1');
    $routes->get('staffdelete/(:num)', 'Admin::staffdelete/$1');
    $routes->post('addstaff', 'Admin::addstaff');
});

// Catch-all route for 404
$routes->get('(:any)', 'Auth::index');