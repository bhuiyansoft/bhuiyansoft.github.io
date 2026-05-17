<?php

namespace App\Controllers\Auth;

use App\Models\UserModel;
use CodeIgniter\Controller;

class ForgotPasswordController extends Controller
{
    public function index(): string
    {
        return view('auth/forgot-password');
    }

    public function send()
    {
        $rules = ['email' => 'required|valid_email'];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $userModel = new UserModel();
        $user = $userModel->findByEmailAnyTenant($email);

        // Always show success to prevent email enumeration
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $userModel->update($user['id'], [
                'reset_token'         => hash('sha256', $token),
                'reset_token_expires' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            ]);

            // In production, send email here
            // $this->sendResetEmail($user['email'], $token);
            log_message('info', 'Password reset token for ' . $email . ': ' . $token);
        }

        return redirect()->to('/auth/forgot-password')->with('success', 'If an account exists with that email, we sent password reset instructions.');
    }

    public function reset(string $token): string
    {
        $hashedToken = hash('sha256', $token);
        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $hashedToken)
            ->where('reset_token_expires >', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            return redirect()->to('/auth/forgot-password')->with('error', 'Invalid or expired reset link. Please request a new one.');
        }

        return view('auth/reset-password', ['token' => $token]);
    }

    public function update(string $token)
    {
        $rules = [
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $hashedToken = hash('sha256', $token);
        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $hashedToken)
            ->where('reset_token_expires >', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            return redirect()->to('/auth/forgot-password')->with('error', 'Invalid or expired reset link.');
        }

        $userModel->update($user['id'], [
            'password'            => $userModel->hashPassword($this->request->getPost('password')),
            'reset_token'         => null,
            'reset_token_expires' => null,
        ]);

        return redirect()->to('/auth/login')->with('success', 'Password updated successfully. Please log in.');
    }
}
