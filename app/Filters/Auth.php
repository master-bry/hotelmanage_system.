<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = Services::session();
        $auth = Services::authentication();
        
        // Check if user is logged in
        if (!$session->get('logged_in')) {
            return redirect()->to('/')->with('error', 'Please login first');
        }
        
        // Check user type restrictions if provided
        if (!empty($arguments)) {
            $userType = $session->get('is_staff') ? 'staff' : 'user';
            
            if (!in_array($userType, $arguments)) {
                if ($userType === 'staff') {
                    return redirect()->to('admin')->with('error', 'Staff access required');
                } else {
                    return redirect()->to('home')->with('error', 'User access required');
                }
            }
        }
        
        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here after the controller execution
        return $response;
    }
}