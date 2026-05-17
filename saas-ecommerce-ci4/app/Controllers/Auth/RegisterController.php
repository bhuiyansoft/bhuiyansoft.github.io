<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\TenantModel;
use App\Models\PlanModel;

class RegisterController extends BaseController
{
    public function index()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/admin/dashboard');
        }
        $planModel = new PlanModel();
        return view('auth/register', [
            'title' => 'Create Your Store',
            'plans' => $planModel->getActivePlans(),
        ]);
    }

    public function store()
    {
        $rules = [
            'store_name' => 'required|min_length[2]|max_length[200]',
            'subdomain'  => 'required|min_length[3]|max_length[50]|alpha_dash|is_unique[tenants.subdomain]',
            'name'       => 'required|min_length[2]|max_length[200]',
            'email'      => 'required|valid_email|is_unique[users.email]',
            'password'   => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
            'plan_id'    => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = db_connect();
        $db->transStart();

        try {
            $tenantModel = new TenantModel();
            $userModel   = new UserModel();

            $tenantId = $tenantModel->insert([
                'name'          => $this->request->getPost('store_name'),
                'email'         => $this->request->getPost('email'),
                'subdomain'     => strtolower($this->request->getPost('subdomain')),
                'plan_id'       => $this->request->getPost('plan_id'),
                'status'        => 'trial',
                'trial_ends_at' => date('Y-m-d H:i:s', strtotime('+30 days')),
            ]);

            $userModel->insert([
                'tenant_id'          => $tenantId,
                'name'               => $this->request->getPost('name'),
                'email'              => $this->request->getPost('email'),
                'password'           => $this->request->getPost('password'),
                'role'               => 'admin',
                'is_active'          => 1,
                'email_verified_at'  => date('Y-m-d H:i:s'),
            ]);

            $db->transComplete();

            if ($db->transStatus()) {
                return redirect()->to('/auth/login')->with('success', 'Account created! Please login to continue.');
            }
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Registration error: ' . $e->getMessage());
        }

        return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
    }
}
