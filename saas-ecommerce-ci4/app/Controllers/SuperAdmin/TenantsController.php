<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\TenantModel;
use App\Models\PlanModel;
use App\Models\UserModel;

class TenantsController extends BaseController
{
    protected TenantModel $tenantModel;
    protected PlanModel $planModel;

    public function __construct()
    {
        $this->tenantModel = new TenantModel();
        $this->planModel   = new PlanModel();
    }

    public function index()
    {
        $filters = [
            'status'  => $this->request->getGet('status'),
            'plan_id' => $this->request->getGet('plan_id'),
            'search'  => $this->request->getGet('search'),
        ];

        $query = $this->tenantModel->select('tenants.*, plans.name as plan_name, plans.slug as plan_slug')
            ->join('plans', 'plans.id = tenants.plan_id', 'left');

        if (!empty($filters['status'])) {
            $query->where('tenants.status', $filters['status']);
        }
        if (!empty($filters['plan_id'])) {
            $query->where('tenants.plan_id', $filters['plan_id']);
        }
        if (!empty($filters['search'])) {
            $query->groupStart()
                ->like('tenants.name', $filters['search'])
                ->orLike('tenants.email', $filters['search'])
                ->orLike('tenants.subdomain', $filters['search'])
                ->groupEnd();
        }

        return view('superadmin/tenants/index', [
            'title'       => 'Manage Tenants',
            'tenants'     => $query->orderBy('tenants.created_at', 'DESC')->paginate(20),
            'pager'       => $this->tenantModel->pager,
            'plans'       => $this->planModel->getActivePlans(),
            'filters'     => $filters,
            'currentUser' => $this->currentUser,
        ]);
    }

    public function show(int $id)
    {
        $tenant = $this->tenantModel->getWithPlan($id);
        if (!$tenant) {
            return redirect()->to('/superadmin/tenants')->with('error', 'Tenant not found.');
        }

        $userModel = new UserModel();
        $orderStats = db_connect()->query(
            "SELECT COUNT(*) as total_orders, SUM(total) as total_revenue FROM orders WHERE tenant_id = ? AND payment_status = 'paid'",
            [$id]
        )->getRowArray();

        return view('superadmin/tenants/show', [
            'title'       => $tenant['name'],
            'tenant'      => $tenant,
            'users'       => $userModel->getByTenant($id),
            'order_stats' => $orderStats,
            'currentUser' => $this->currentUser,
        ]);
    }

    public function create()
    {
        return view('superadmin/tenants/create', [
            'title'       => 'Create Tenant',
            'plans'       => $this->planModel->getActivePlans(),
            'currentUser' => $this->currentUser,
        ]);
    }

    public function store()
    {
        $rules = [
            'name'       => 'required|min_length[2]|max_length[200]',
            'email'      => 'required|valid_email|is_unique[tenants.email]',
            'subdomain'  => 'required|min_length[3]|alpha_dash|is_unique[tenants.subdomain]',
            'plan_id'    => 'required|is_natural_no_zero',
            'admin_name' => 'required|min_length[2]',
            'admin_pass' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = db_connect();
        $db->transStart();

        $tenantId = $this->tenantModel->insert([
            'name'          => $this->request->getPost('name'),
            'email'         => $this->request->getPost('email'),
            'phone'         => $this->request->getPost('phone'),
            'subdomain'     => strtolower($this->request->getPost('subdomain')),
            'plan_id'       => $this->request->getPost('plan_id'),
            'status'        => $this->request->getPost('status', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'trial',
            'trial_ends_at' => date('Y-m-d H:i:s', strtotime('+30 days')),
        ]);

        $userModel = new UserModel();
        $userModel->insert([
            'tenant_id'         => $tenantId,
            'name'              => $this->request->getPost('admin_name'),
            'email'             => $this->request->getPost('email'),
            'password'          => $this->request->getPost('admin_pass'),
            'role'              => 'admin',
            'is_active'         => 1,
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        if ($db->transStatus()) {
            $this->logActivity('tenant.create', "Created tenant: {$this->request->getPost('name')}");
            return redirect()->to('/superadmin/tenants')->with('success', 'Tenant created successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create tenant.');
    }

    public function edit(int $id)
    {
        $tenant = $this->tenantModel->find($id);
        if (!$tenant) {
            return redirect()->to('/superadmin/tenants')->with('error', 'Tenant not found.');
        }

        return view('superadmin/tenants/edit', [
            'title'       => 'Edit Tenant',
            'tenant'      => $tenant,
            'plans'       => $this->planModel->getActivePlans(),
            'currentUser' => $this->currentUser,
        ]);
    }

    public function update(int $id)
    {
        $tenant = $this->tenantModel->find($id);
        if (!$tenant) {
            return redirect()->to('/superadmin/tenants')->with('error', 'Tenant not found.');
        }

        $this->tenantModel->update($id, [
            'name'          => $this->request->getPost('name'),
            'phone'         => $this->request->getPost('phone'),
            'plan_id'       => $this->request->getPost('plan_id'),
            'status'        => $this->request->getPost('status'),
            'custom_domain' => $this->request->getPost('custom_domain'),
            'tax_rate'      => $this->request->getPost('tax_rate') ?? 0,
        ]);

        $this->logActivity('tenant.update', "Updated tenant ID: $id");
        return redirect()->to('/superadmin/tenants')->with('success', 'Tenant updated successfully.');
    }

    public function suspend(int $id)
    {
        $this->tenantModel->update($id, ['status' => 'suspended']);
        $this->logActivity('tenant.suspend', "Suspended tenant ID: $id");
        return redirect()->back()->with('success', 'Tenant suspended.');
    }

    public function activate(int $id)
    {
        $this->tenantModel->update($id, ['status' => 'active']);
        $this->logActivity('tenant.activate', "Activated tenant ID: $id");
        return redirect()->back()->with('success', 'Tenant activated.');
    }
}
