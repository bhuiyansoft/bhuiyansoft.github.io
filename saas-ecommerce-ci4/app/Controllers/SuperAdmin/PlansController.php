<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\PlanModel;

class PlansController extends BaseController
{
    protected PlanModel $planModel;

    public function __construct()
    {
        $this->planModel = new PlanModel();
    }

    public function index()
    {
        return view('superadmin/plans/index', [
            'title'       => 'Subscription Plans',
            'plans'       => $this->planModel->getPlanWithTenantCount(),
            'currentUser' => $this->currentUser,
        ]);
    }

    public function create()
    {
        return view('superadmin/plans/create', [
            'title'       => 'Create Plan',
            'currentUser' => $this->currentUser,
        ]);
    }

    public function store()
    {
        $rules = [
            'name'  => 'required|min_length[2]|max_length[100]',
            'slug'  => 'required|alpha_dash|is_unique[plans.slug]',
            'price' => 'required|decimal',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $features = [
            'analytics'        => (bool)$this->request->getPost('feat_analytics'),
            'custom_domain'    => (bool)$this->request->getPost('feat_custom_domain'),
            'api_access'       => (bool)$this->request->getPost('feat_api'),
            'email_support'    => (bool)$this->request->getPost('feat_email_support'),
            'chat_support'     => (bool)$this->request->getPost('feat_chat_support'),
            'dedicated_support' => (bool)$this->request->getPost('feat_dedicated_support'),
        ];

        $this->planModel->insert([
            'name'          => $this->request->getPost('name'),
            'slug'          => strtolower($this->request->getPost('slug')),
            'price'         => $this->request->getPost('price'),
            'billing_cycle' => $this->request->getPost('billing_cycle') ?? 'monthly',
            'max_products'  => $this->request->getPost('max_products') ?? -1,
            'max_orders'    => $this->request->getPost('max_orders') ?? -1,
            'max_staff'     => $this->request->getPost('max_staff') ?? -1,
            'storage_gb'    => $this->request->getPost('storage_gb') ?? 1,
            'features'      => json_encode($features),
            'sort_order'    => $this->request->getPost('sort_order') ?? 0,
            'is_active'     => 1,
        ]);

        return redirect()->to('/superadmin/plans')->with('success', 'Plan created successfully.');
    }

    public function edit(int $id)
    {
        $plan = $this->planModel->find($id);
        if (!$plan) {
            return redirect()->to('/superadmin/plans')->with('error', 'Plan not found.');
        }

        $plan = $this->planModel->decodeFeatures($plan);
        return view('superadmin/plans/edit', [
            'title'       => 'Edit Plan: ' . $plan['name'],
            'plan'        => $plan,
            'currentUser' => $this->currentUser,
        ]);
    }

    public function update(int $id)
    {
        $features = [
            'analytics'        => (bool)$this->request->getPost('feat_analytics'),
            'custom_domain'    => (bool)$this->request->getPost('feat_custom_domain'),
            'api_access'       => (bool)$this->request->getPost('feat_api'),
            'email_support'    => (bool)$this->request->getPost('feat_email_support'),
            'chat_support'     => (bool)$this->request->getPost('feat_chat_support'),
            'dedicated_support' => (bool)$this->request->getPost('feat_dedicated_support'),
        ];

        $this->planModel->update($id, [
            'name'          => $this->request->getPost('name'),
            'price'         => $this->request->getPost('price'),
            'billing_cycle' => $this->request->getPost('billing_cycle'),
            'max_products'  => $this->request->getPost('max_products'),
            'max_orders'    => $this->request->getPost('max_orders'),
            'max_staff'     => $this->request->getPost('max_staff'),
            'storage_gb'    => $this->request->getPost('storage_gb'),
            'features'      => json_encode($features),
            'sort_order'    => $this->request->getPost('sort_order') ?? 0,
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('/superadmin/plans')->with('success', 'Plan updated successfully.');
    }
}
