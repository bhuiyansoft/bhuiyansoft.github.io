<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TenantModel;

class SettingsController extends BaseController
{
    protected TenantModel $tenantModel;
    protected int $tenantId;

    public function __construct()
    {
        $this->tenantModel = new TenantModel();
    }

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->tenantId = $this->currentUser['tenant_id'];
    }

    public function index()
    {
        return view('admin/settings/index', [
            'title'         => 'Store Settings',
            'tenant'        => $this->currentTenant,
            'currentUser'   => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function update()
    {
        $this->tenantModel->update($this->tenantId, [
            'name'            => $this->request->getPost('name'),
            'phone'           => $this->request->getPost('phone'),
            'address'         => $this->request->getPost('address'),
            'city'            => $this->request->getPost('city'),
            'state'           => $this->request->getPost('state'),
            'country'         => $this->request->getPost('country'),
            'postal_code'     => $this->request->getPost('postal_code'),
            'currency'        => $this->request->getPost('currency'),
            'currency_symbol' => $this->request->getPost('currency_symbol'),
            'tax_rate'        => $this->request->getPost('tax_rate') ?? 0,
            'shipping_rate'   => $this->request->getPost('shipping_rate') ?? 0,
            'timezone'        => $this->request->getPost('timezone'),
        ]);

        $this->logActivity('settings.update', 'Store settings updated.');
        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function updateLogo()
    {
        $file = $this->request->getFile('logo');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Invalid file.');
        }

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        if (!in_array($file->getMimeType(), $allowed)) {
            return redirect()->back()->with('error', 'Only JPG, PNG, GIF, WEBP, and SVG files are allowed.');
        }

        $newName    = $file->getRandomName();
        $uploadPath = WRITEPATH . 'uploads/logos/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file->move($uploadPath, $newName);
        $this->tenantModel->update($this->tenantId, ['logo' => 'uploads/logos/' . $newName]);

        return redirect()->back()->with('success', 'Logo updated successfully.');
    }
}
