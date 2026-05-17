<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TenantModel;

class TenantFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $tenantId = session()->get('tenant_id');

        if (!$tenantId) {
            return redirect()->to('/auth/login')->with('error', 'No tenant context found.');
        }

        $tenantModel = new TenantModel();
        $tenant      = $tenantModel->find($tenantId);

        if (!$tenant || $tenant['status'] === 'suspended') {
            session()->destroy();
            return redirect()->to('/auth/login')->with('error', 'Your account has been suspended. Please contact support.');
        }

        if ($tenant['status'] === 'cancelled') {
            session()->destroy();
            return redirect()->to('/auth/login')->with('error', 'Your subscription has been cancelled.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing
    }
}
