<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        
        if (!$session->get('logged_in')) {
            return redirect()->to('/');
        }

        // Check user type for admin routes
        if (strpos($request->getUri()->getPath(), 'admin') !== false) {
            if (!$session->get('is_staff')) {
                $session->setFlashdata('error', 'Access denied. Staff privileges required.');
                return redirect()->to('home');
            }
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here after response is sent
    }
}