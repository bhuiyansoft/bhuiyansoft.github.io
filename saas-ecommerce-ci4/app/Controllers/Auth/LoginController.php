<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class LoginController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (session()->get('user_id')) {
            return $this->redirectAfterLogin();
        }
        return view('auth/login', ['title' => 'Login']);
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

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Try superadmin (no tenant)
        $user = $this->userModel->where('email', $email)
            ->where('role', 'superadmin')
            ->where('is_active', 1)
            ->first();

        // Try tenant user
        if (!$user) {
            $user = $this->userModel->where('email', $email)
                ->whereIn('role', ['admin', 'manager', 'staff'])
                ->where('is_active', 1)
                ->first();
        }

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        $this->setSession($user);
        $this->userModel->updateLastLogin($user['id'], $this->request->getIPAddress());
        $this->logActivity('login', "User {$user['email']} logged in.");

        return $this->redirectAfterLogin();
    }

    public function logout()
    {
        $this->logActivity('logout', 'User logged out.');
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'You have been logged out.');
    }

    public function forgotPassword()
    {
        return view('auth/forgot_password', ['title' => 'Forgot Password']);
    }

    public function sendReset()
    {
        // Password reset logic (stub - would integrate with email service)
        return redirect()->back()->with('success', 'If an account exists, a password reset link will be sent.');
    }

    private function redirectAfterLogin()
    {
        $role = session()->get('user_role');
        return match ($role) {
            'superadmin' => redirect()->to('/superadmin/dashboard'),
            default      => redirect()->to('/admin/dashboard'),
        };
    }
}
