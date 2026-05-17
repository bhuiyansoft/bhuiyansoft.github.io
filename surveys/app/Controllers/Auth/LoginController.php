<?php

namespace App\Controllers\Auth;

use App\Models\UserModel;
use App\Models\TenantModel;
use CodeIgniter\Controller;

class LoginController extends Controller
{
    public function index(): string
    {
        return view('auth/login');
    }

    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->findByEmailAnyTenant($email);

        if (!$user || !$userModel->verifyPassword($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        if ($user['status'] !== 'active') {
            return redirect()->back()->with('error', 'Your account has been suspended. Please contact support.');
        }

        $tenantModel = new TenantModel();
        $tenant = $tenantModel->find($user['tenant_id']);

        if (!$tenant || $tenant['status'] !== 'active') {
            return redirect()->back()->with('error', 'Your organization account has been suspended.');
        }

        $userModel->updateLastLogin($user['id'], $this->request->getIPAddress());

        session()->set([
            'user_id'     => $user['id'],
            'user_name'   => $user['name'],
            'user_email'  => $user['email'],
            'user_role'   => $user['role'],
            'tenant_id'   => $user['tenant_id'],
            'tenant_name' => $tenant['name'],
            'tenant_slug' => $tenant['slug'],
        ]);

        $redirectUrl = session()->get('redirect_url') ?? '/dashboard';
        session()->remove('redirect_url');

        return redirect()->to($redirectUrl)->with('success', 'Welcome back, ' . $user['name'] . '!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'You have been logged out.');
    }
}
