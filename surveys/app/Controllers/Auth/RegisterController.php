<?php

namespace App\Controllers\Auth;

use App\Models\UserModel;
use App\Models\TenantModel;
use App\Models\PlanModel;
use CodeIgniter\Controller;

class RegisterController extends Controller
{
    public function index(): string
    {
        return view('auth/register');
    }

    public function register()
    {
        $rules = [
            'name'             => 'required|min_length[2]|max_length[100]',
            'org_name'         => 'required|min_length[2]|max_length[100]',
            'email'            => 'required|valid_email|max_length[150]',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $existing = $userModel->findByEmailAnyTenant($this->request->getPost('email'));
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'An account with this email already exists.');
        }

        $tenantModel = new TenantModel();
        $planModel = new PlanModel();
        $freePlan = $planModel->findBySlug('free');

        $tenantId = $tenantModel->insert([
            'name'    => $this->request->getPost('org_name'),
            'slug'    => $tenantModel->generateSlug($this->request->getPost('org_name')),
            'plan_id' => $freePlan['id'] ?? 1,
            'status'  => 'active',
        ]);

        if (!$tenantId) {
            return redirect()->back()->withInput()->with('error', 'Failed to create organization. Please try again.');
        }

        $userId = $userModel->insert([
            'tenant_id'         => $tenantId,
            'name'              => $this->request->getPost('name'),
            'email'             => $this->request->getPost('email'),
            'password'          => $userModel->hashPassword($this->request->getPost('password')),
            'role'              => 'owner',
            'status'            => 'active',
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);

        if (!$userId) {
            $tenantModel->delete($tenantId);
            return redirect()->back()->withInput()->with('error', 'Failed to create account. Please try again.');
        }

        $tenant = $tenantModel->find($tenantId);

        session()->set([
            'user_id'     => $userId,
            'user_name'   => $this->request->getPost('name'),
            'user_email'  => $this->request->getPost('email'),
            'user_role'   => 'owner',
            'tenant_id'   => $tenantId,
            'tenant_name' => $this->request->getPost('org_name'),
            'tenant_slug' => $tenant['slug'],
        ]);

        return redirect()->to('/dashboard')->with('success', 'Welcome to SurveyStack! Your account is ready.');
    }
}
