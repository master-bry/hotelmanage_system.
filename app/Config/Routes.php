<?php
use App\Controllers\Auth;
use App\Controllers\Home;
use App\Controllers\Admin;

// Authentication routes
$routes->get('/', [Auth::class, 'index']);
$routes->post('doLogin', [Auth::class, 'doLogin']);
$routes->post('signup', [Auth::class, 'signup']);
$routes->get('logout', [Auth::class, 'logout']);

// Home routes
$routes->get('home', [Home::class, 'index']);
$routes->post('book', [Home::class, 'book']);

// Admin routes
$routes->get('admin', [Admin::class, 'index']);
$routes->get('admin/dashboard', [Admin::class, 'dashboard']);
$routes->get('admin/roombook', [Admin::class, 'roombook']);
$routes->get('admin/payment', [Admin::class, 'payment']);
$routes->get('admin/room', [Admin::class, 'room']);
$routes->get('admin/staff', [Admin::class, 'staff']);
$routes->post('admin/addroom', [Admin::class, 'addroom']);
$routes->get('admin/roomdelete/(:num)', [Admin::class, 'roomdelete/$1']);
$routes->get('admin/roombookdelete/(:num)', [Admin::class, 'roombookdelete/$1']);
$routes->get('admin/paymantdelete/(:num)', [Admin::class, 'paymantdelete/$1']);
$routes->get('admin/invoiceprint/(:num)', [Admin::class, 'invoiceprint/$1']);
$routes->post('admin/exportdata', [Admin::class, 'exportdata']);
$routes->get('admin/roomconfirm/(:num)', [Admin::class, 'roomconfirm/$1']);
$routes->get('admin/roombookedit/(:num)', [Admin::class, 'roombookedit/$1']);
$routes->post('admin/roombookupdate/(:num)', [Admin::class, 'roombookupdate/$1']);
$routes->get('admin/staffdelete/(:num)', [Admin::class, 'staffdelete/$1']);
$routes->post('admin/addstaff', [Admin::class, 'addstaff']);