<?php
namespace Config;

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// Authentication routes
$routes->get('/', 'Auth::index', ['as' => 'login']);
$routes->post('auth/login', 'Auth::login');
$routes->post('auth/register', 'Auth::register');
$routes->match(['get', 'post'], 'auth/verify', 'Auth::verify');
$routes->post('auth/resend', 'Auth::resend');
$routes->get('auth/logout', 'Auth::logout');

// Home routes (for regular users)
$routes->group('home', ['filter' => 'auth:user'], function($routes) {
    $routes->get('/', 'Home::index');
    $routes->post('book', 'Home::book');
});

// Admin routes (for staff only)
$routes->group('admin', ['filter' => 'auth:staff'], function($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('chartdata', 'Admin::getChartData');
    
    // Bookings routes
    $routes->get('bookings', 'Admin::bookings');
    $routes->post('bookings/add', 'Admin::addBooking');
    $routes->get('bookings/confirm/(:num)', 'Admin::confirmBooking/$1');
    $routes->get('bookings/delete/(:num)', 'Admin::deleteBooking/$1');
    $routes->get('bookings/edit/(:num)', 'Admin::editBooking/$1');
    $routes->post('bookings/update/(:num)', 'Admin::updateBooking/$1');
    
    // Payments routes
    $routes->get('payments', 'Admin::payments');
    $routes->get('payments/invoice/(:num)', 'Admin::generateInvoice/$1');
    
    // Rooms routes
    $routes->get('rooms', 'Admin::rooms');
    $routes->post('rooms/add', 'Admin::addRoom');
    $routes->get('rooms/delete/(:num)', 'Admin::deleteRoom/$1');
    
    // Staff routes
    $routes->get('staff', 'Admin::staff');
    $routes->post('staff/add', 'Admin::addStaff');
    $routes->get('staff/delete/(:num)', 'Admin::deleteStaff/$1');
});

// Catch-all route for undefined routes
$routes->get('(:any)', function() {
    return redirect()->to('/');
});