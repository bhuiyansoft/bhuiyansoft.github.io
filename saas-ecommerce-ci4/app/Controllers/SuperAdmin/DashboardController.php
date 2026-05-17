<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\TenantModel;
use App\Models\PlanModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $tenantModel = new TenantModel();
        $planModel   = new PlanModel();

        $stats = $tenantModel->getDashboardStats();
        $revenue = $tenantModel->getMonthlyRevenue();

        return view('superadmin/dashboard', [
            'title'         => 'Super Admin Dashboard',
            'stats'         => $stats,
            'monthly_revenue' => $revenue,
            'plans'         => $planModel->getPlanWithTenantCount(),
            'recent_tenants' => $tenantModel->select('tenants.*, plans.name as plan_name')
                ->join('plans', 'plans.id = tenants.plan_id', 'left')
                ->orderBy('tenants.created_at', 'DESC')
                ->limit(8)
                ->findAll(),
            'currentUser'   => $this->currentUser,
        ]);
    }

    public function revenue()
    {
        $tenantModel = new TenantModel();
        return view('superadmin/revenue', [
            'title'          => 'Revenue Overview',
            'monthly_revenue' => $tenantModel->getMonthlyRevenue(),
            'currentUser'    => $this->currentUser,
        ]);
    }

    public function users()
    {
        $userModel = new UserModel();
        return view('superadmin/users', [
            'title'      => 'All Users',
            'users'      => $userModel->select('users.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = users.tenant_id', 'left')
                ->where('users.role !=', 'superadmin')
                ->paginate(20),
            'pager'      => $userModel->pager,
            'currentUser' => $this->currentUser,
        ]);
    }
}
