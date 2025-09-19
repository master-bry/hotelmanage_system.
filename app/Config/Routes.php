<?php
namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = \Config\Services::routes();

$routes->get('/', 'Auth::index'); // Default to login page (auth.php)
$routes->get('home', 'Home::index'); // Home page after login
$routes->post('book', 'Home::book'); // Booking form submission
$routes->post('login', 'Auth::doLogin');
$routes->post('signup', 'Auth::signup');
$routes->get('logout', 'Auth::logout');
$routes->get('admin', 'Admin::index');
$routes->get('admin/dashboard', 'Admin::dashboard');
$routes->get('admin/roombook', 'Admin::roombook');
$routes->get('admin/payment', 'Admin::payment');
$routes->get('admin/room', 'Admin::room');
$routes->get('admin/staff', 'Admin::staff');
$routes->post('admin/addroom', 'Admin::addroom');
$routes->get('admin/roomdelete/(:num)', 'Admin::roomdelete/$1');
$routes->get('admin/roombookdelete/(:num)', 'Admin::roombookdelete/$1');
$routes->get('admin/paymantdelete/(:num)', 'Admin::paymantdelete/$1');
$routes->get('admin/invoiceprint/(:num)', 'Admin::invoiceprint/$1');
$routes->post('admin/exportdata', 'Admin::exportdata');
$routes->get('admin/roomconfirm/(:num)', 'Admin::roomconfirm/$1');
$routes->get('admin/roombookedit/(:num)', 'Admin::roombookedit/$1');
$routes->post('admin/roombookupdate/(:num)', 'Admin::roombookupdate/$1');
$routes->get('admin/staffdelete/(:num)', 'Admin::staffdelete/$1');
$routes->post('admin/addstaff', 'Admin::addstaff');

?>