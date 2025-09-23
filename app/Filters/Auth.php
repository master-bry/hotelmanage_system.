<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        $uri = $request->getUri()->getPath();
        log_message('debug', "Auth filter triggered for URI: $uri, Arguments: " . print_r($arguments, true));

        if (!$session->get('logged_in')) {
            log_message('debug', 'No logged-in session found, redirecting to /');
            return redirect()->to('/')->with('error', 'Please log in first');
        }

        if ($arguments && in_array('staff', $arguments) && !$session->get('is_staff')) {
            log_message('debug', 'Non-staff user attempted to access staff route');
            return redirect()->to('/')->with('error', 'Staff access required');
        }

        if ($arguments && in_array('user', $arguments) && $session->get('is_staff')) {
            log_message('debug', 'Staff user attempted to access user route');
            return redirect()->to('/')->with('error', 'User access required');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}