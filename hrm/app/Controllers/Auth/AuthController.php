<?php

namespace App\Controllers\Auth;

use App\Models\UserModel;
use App\Models\EmployeeModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url', 'text']);
    }

    public function login(): string
    {
        if (session()->get('user_id')) {
            return redirect()->to(base_url('admin/dashboard'));
        }
        return view('auth/login', ['title' => 'Login - HRM System']);
    }

    public function loginProcess()
    {
        $rules = [
            'credential' => 'required|min_length[3]',
            'password'   => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $credential = $this->request->getPost('credential');
        $password   = $this->request->getPost('password');
        $remember   = $this->request->getPost('remember');

        $user = $this->userModel->findByCredential($credential);

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid credentials. Please try again.');
        }

        if (!$user['is_active']) {
            return redirect()->back()->withInput()->with('error', 'Your account has been deactivated. Contact administrator.');
        }

        // Load employee info
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($user['employee_id']);

        // Set session
        $sessionData = [
            'user_id'      => $user['id'],
            'employee_id'  => $user['employee_id'],
            'username'     => $user['username'],
            'email'        => $user['email'],
            'role'         => $user['role'],
            'is_active'    => $user['is_active'],
            'full_name'    => $employee ? $employee['first_name'] . ' ' . $employee['last_name'] : $user['username'],
            'photo'        => $employee['photo'] ?? null,
        ];
        session()->set($sessionData);

        // Update last login
        $this->userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $this->userModel->update($user['id'], ['remember_token' => $token]);
            setcookie('remember_token', $token, time() + (86400 * 30), '/', '', false, true);
        }

        $redirectUrl = session()->get('redirect_url') ?? base_url('admin/dashboard');
        session()->remove('redirect_url');

        return redirect()->to($redirectUrl)->with('success', 'Welcome back, ' . session()->get('full_name') . '!');
    }

    public function logout()
    {
        $userId = session()->get('user_id');
        if ($userId) {
            $this->userModel->update($userId, ['remember_token' => null]);
        }
        setcookie('remember_token', '', time() - 3600, '/');
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'You have been logged out successfully.');
    }

    public function forgotPassword(): string
    {
        return view('auth/forgot_password', ['title' => 'Forgot Password - HRM System']);
    }

    public function forgotPasswordProcess()
    {
        $email = $this->request->getPost('email');
        $user  = $this->userModel->findByEmail($email);

        if (!$user) {
            return redirect()->back()->with('error', 'No account found with that email address.');
        }

        $token   = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->userModel->update($user['id'], [
            'reset_token'   => $token,
            'reset_expires' => $expires,
        ]);

        // In production: send email with reset link
        $resetLink = base_url('reset-password/' . $token);

        return redirect()->back()->with('success', 'Password reset link has been sent. (Demo: ' . $resetLink . ')');
    }

    public function resetPassword(string $token): string
    {
        $user = $this->userModel->where('reset_token', $token)
            ->where('reset_expires >', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            return redirect()->to(base_url('login'))->with('error', 'Invalid or expired reset link.');
        }

        return view('auth/reset_password', ['title' => 'Reset Password', 'token' => $token]);
    }

    public function resetPasswordProcess(string $token)
    {
        $user = $this->userModel->where('reset_token', $token)
            ->where('reset_expires >', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            return redirect()->to(base_url('login'))->with('error', 'Invalid or expired reset link.');
        }

        $password = $this->request->getPost('password');
        $confirm  = $this->request->getPost('confirm_password');

        if ($password !== $confirm) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        $this->userModel->update($user['id'], [
            'password'      => password_hash($password, PASSWORD_BCRYPT),
            'reset_token'   => null,
            'reset_expires' => null,
        ]);

        return redirect()->to(base_url('login'))->with('success', 'Password reset successfully. Please login.');
    }
}
