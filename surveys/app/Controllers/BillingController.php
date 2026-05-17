<?php

namespace App\Controllers;

use App\Models\TenantModel;
use App\Models\PlanModel;
use CodeIgniter\Controller;

class BillingController extends Controller
{
    protected int $tenantId;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->tenantId = (int)session()->get('tenant_id');
    }

    public function index(): string
    {
        $tenantModel = new TenantModel();
        $planModel = new PlanModel();

        $tenant = $tenantModel->find($this->tenantId);
        $currentPlan = $planModel->find($tenant['plan_id']);
        $allPlans = $planModel->getActivePlans();

        return view('billing/index', [
            'tenant'       => $tenant,
            'current_plan' => $currentPlan,
            'plans'        => $allPlans,
        ]);
    }

    public function upgrade()
    {
        $planSlug = $this->request->getPost('plan_slug');
        $planModel = new PlanModel();
        $plan = $planModel->findBySlug($planSlug);

        if (!$plan) {
            return redirect()->back()->with('error', 'Invalid plan selected.');
        }

        // In production: integrate Stripe/PayPal here
        // For demo, we'll just simulate an upgrade
        $tenantModel = new TenantModel();
        $tenantModel->update($this->tenantId, [
            'plan_id'         => $plan['id'],
            'plan_expires_at' => date('Y-m-d H:i:s', strtotime('+1 year')),
        ]);

        return redirect()->to('/billing')->with('success', "Successfully upgraded to {$plan['name']} plan!");
    }
}
