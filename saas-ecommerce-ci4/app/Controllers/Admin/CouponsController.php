<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CouponModel;

class CouponsController extends BaseController
{
    protected CouponModel $couponModel;
    protected int $tenantId;

    public function __construct()
    {
        $this->couponModel = new CouponModel();
    }

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->tenantId = $this->currentUser['tenant_id'];
    }

    public function index()
    {
        return view('admin/coupons/index', [
            'title'       => 'Coupons',
            'coupons'     => $this->couponModel->getByTenant($this->tenantId),
            'currentUser' => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function create()
    {
        return view('admin/coupons/create', [
            'title'       => 'Create Coupon',
            'currentUser' => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function store()
    {
        $rules = [
            'code'  => 'required|min_length[3]|max_length[50]',
            'type'  => 'required|in_list[percentage,fixed]',
            'value' => 'required|decimal',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $code = strtoupper(trim($this->request->getPost('code')));
        $existing = $this->couponModel->where('tenant_id', $this->tenantId)->where('code', $code)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'This coupon code already exists.');
        }

        $this->couponModel->insert([
            'tenant_id'  => $this->tenantId,
            'code'       => $code,
            'type'       => $this->request->getPost('type'),
            'value'      => $this->request->getPost('value'),
            'min_order'  => $this->request->getPost('min_order') ?? 0,
            'max_uses'   => $this->request->getPost('max_uses') ?: null,
            'expires_at' => $this->request->getPost('expires_at') ?: null,
            'is_active'  => 1,
        ]);

        return redirect()->to('/admin/coupons')->with('success', 'Coupon created successfully.');
    }

    public function delete(int $id)
    {
        $coupon = $this->couponModel->where('tenant_id', $this->tenantId)->find($id);
        if (!$coupon) {
            return redirect()->to('/admin/coupons')->with('error', 'Coupon not found.');
        }

        $this->couponModel->delete($id);
        return redirect()->to('/admin/coupons')->with('success', 'Coupon deleted.');
    }
}
