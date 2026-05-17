<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TenantModel;
use CodeIgniter\Controller;

class SettingsController extends Controller
{
    protected int $tenantId;
    protected int $userId;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->tenantId = (int)session()->get('tenant_id');
        $this->userId = (int)session()->get('user_id');
    }

    public function index(): string
    {
        $userModel = new UserModel();
        $tenantModel = new TenantModel();

        return view('settings/index', [
            'user'   => $userModel->find($this->userId),
            'tenant' => $tenantModel->find($this->tenantId),
        ]);
    }

    public function updateProfile()
    {
        $rules = [
            'name'  => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|max_length[150]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $userModel->update($this->userId, [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ]);

        session()->set('user_name', $this->request->getPost('name'));
        session()->set('user_email', $this->request->getPost('email'));

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword()
    {
        $rules = [
            'current_password'  => 'required',
            'new_password'      => 'required|min_length[8]',
            'confirm_password'  => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->find($this->userId);

        if (!$userModel->verifyPassword($this->request->getPost('current_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $userModel->update($this->userId, [
            'password' => $userModel->hashPassword($this->request->getPost('new_password')),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    public function updateOrganization()
    {
        if (!in_array(session()->get('user_role'), ['owner', 'admin'])) {
            return redirect()->back()->with('error', 'You do not have permission to update organization settings.');
        }

        $rules = [
            'name'    => 'required|min_length[2]|max_length[100]',
            'website' => 'permit_empty|valid_url',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tenantModel = new TenantModel();
        $tenantModel->update($this->tenantId, [
            'name'          => $this->request->getPost('name'),
            'website'       => $this->request->getPost('website'),
            'industry'      => $this->request->getPost('industry'),
            'primary_color' => $this->request->getPost('primary_color') ?? '#4F46E5',
        ]);

        session()->set('tenant_name', $this->request->getPost('name'));

        return redirect()->back()->with('success', 'Organization settings updated.');
    }
}
