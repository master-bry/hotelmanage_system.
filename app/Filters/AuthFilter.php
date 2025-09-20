<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (!$session->has('usermail')) {
            return redirect()->to(base_url('/'))->with('error', 'Please log in to continue');
        }
        if (in_array('staff', $arguments) && !$session->get('isStaff')) {
            return redirect()->to(base_url('/'))->with('error', 'Staff access only');
        }
        if (in_array('user', $arguments) && $session->get('isStaff')) {
            return redirect()->to(base_url('/'))->with('error', 'User access only');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}