<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('user_id')) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url('login'))->with('error', 'Please login to continue.');
        }

        if (!session()->get('is_active')) {
            session()->destroy();
            return redirect()->to(base_url('login'))->with('error', 'Your account has been deactivated.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
