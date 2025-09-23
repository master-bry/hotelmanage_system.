<?php
namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Main route
$routes->get('/', 'Auth::index');

// Auth routes
$routes->post('auth/login', 'Auth::login');
$routes->post('auth/register', 'Auth::register');
$routes->get('auth/logout', 'Auth::logout');

// Home routes
$routes->get('home', 'Home::index');
$routes->post('home/book', 'Home::book');

// Admin routes
$routes->group('admin', function($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('roombook', 'Admin::roombook');
    $routes->post('roombook/add', 'Admin::addRoombook');
    $routes->get('roombook/delete/(:num)', 'Admin::deleteRoombook/$1');
    $routes->get('roombook/edit/(:num)', 'Admin::editRoombook/$1');
    $routes->post('roombook/update/(:num)', 'Admin::updateRoombook/$1');
    $routes->get('roombook/confirm/(:num)', 'Admin::confirmRoombook/$1');
    
    $routes->get('payment', 'Admin::payment');
    $routes->get('payment/delete/(:num)', 'Admin::deletePayment/$1');
    $routes->get('payment/invoice/(:num)', 'Admin::invoice/$1');
    
    $routes->get('rooms', 'Admin::rooms');
    $routes->post('rooms/add', 'Admin::addRoom');
    $routes->get('rooms/delete/(:num)', 'Admin::deleteRoom/$1');
    
    $routes->get('staff', 'Admin::staff');
    $routes->post('staff/add', 'Admin::addStaff');
    $routes->get('staff/delete/(:num)', 'Admin::deleteStaff/$1');
    
    $routes->get('chart-data', 'Admin::getChartData');
});

// Catch-all route
$routes->get('(:any)', 'Auth::index');