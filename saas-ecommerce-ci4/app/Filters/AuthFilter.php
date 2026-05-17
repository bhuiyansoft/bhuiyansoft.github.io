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

        if (!$session->get('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to continue.');
        }

        // Role-based access check
        if ($arguments) {
            $allowedRoles = is_array($arguments) ? $arguments : [$arguments];
            $userRole     = $session->get('user_role');

            // superadmin can access everything
            if ($userRole === 'superadmin') {
                return;
            }

            // For admin routes, allow admin + manager + staff
            if (in_array('admin', $allowedRoles) && in_array($userRole, ['admin', 'manager', 'staff'])) {
                return;
            }

            if (!in_array($userRole, $allowedRoles)) {
                return redirect()->to('/auth/login')->with('error', 'Access denied.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing
    }
}
