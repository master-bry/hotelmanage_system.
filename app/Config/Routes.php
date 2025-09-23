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
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('bookings', 'Admin::bookings');
    $routes->post('bookings/add', 'Admin::addBooking');
    $routes->get('bookings/confirm/(:num)', 'Admin::confirmBooking/$1');
    $routes->get('bookings/delete/(:num)', 'Admin::deleteBooking/$1');
    $routes->get('bookings/edit/(:num)', 'Admin::editBooking/$1');
    $routes->post('bookings/update/(:num)', 'Admin::updateBooking/$1');
    
    $routes->get('payments', 'Admin::payments');
    $routes->get('payments/invoice/(:num)', 'Admin::generateInvoice/$1');
    
    $routes->get('rooms', 'Admin::rooms');
    $routes->post('rooms/add', 'Admin::addRoom');
    $routes->get('rooms/delete/(:num)', 'Admin::deleteRoom/$1');
    
    $routes->get('staff', 'Admin::staff');
    $routes->post('staff/add', 'Admin::addStaff');
    $routes->get('staff/delete/(:num)', 'Admin::deleteStaff/$1');
    
    $routes->get('chart-data', 'Admin::getChartData');
});

// API routes
$routes->group('api', function($routes) {
    $routes->post('calculate-total', 'Api::calculateTotal');
});

// Catch-all route
$routes->get('(:any)', 'Auth::index');