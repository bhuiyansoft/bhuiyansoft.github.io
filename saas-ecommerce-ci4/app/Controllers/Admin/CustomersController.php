<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\OrderModel;

class CustomersController extends BaseController
{
    protected CustomerModel $customerModel;
    protected int $tenantId;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->tenantId = $this->currentUser['tenant_id'];
    }

    public function index()
    {
        $filters = ['search' => $this->request->getGet('search')];

        return view('admin/customers/index', [
            'title'       => 'Customers',
            'customers'   => $this->customerModel->getByTenant($this->tenantId, $filters),
            'pager'       => $this->customerModel->pager,
            'filters'     => $filters,
            'currentUser' => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function show(int $id)
    {
        $customer = $this->customerModel->getWithStats($id, $this->tenantId);
        if (!$customer) {
            return redirect()->to('/admin/customers')->with('error', 'Customer not found.');
        }

        $orderModel = new OrderModel();
        $orders = $orderModel->where('tenant_id', $this->tenantId)
            ->where('customer_id', $id)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();

        return view('admin/customers/show', [
            'title'       => $customer['name'],
            'customer'    => $customer,
            'orders'      => $orders,
            'currentUser' => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function create()
    {
        return view('admin/customers/create', [
            'title'       => 'Add Customer',
            'currentUser' => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function store()
    {
        $rules = [
            'name'  => 'required|min_length[2]|max_length[200]',
            'email' => 'required|valid_email',
            'phone' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $existing = $this->customerModel->findByEmail($this->tenantId, $this->request->getPost('email'));
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'A customer with this email already exists.');
        }

        $this->customerModel->insert([
            'tenant_id'   => $this->tenantId,
            'name'        => $this->request->getPost('name'),
            'email'       => $this->request->getPost('email'),
            'phone'       => $this->request->getPost('phone'),
            'address'     => $this->request->getPost('address'),
            'city'        => $this->request->getPost('city'),
            'country'     => $this->request->getPost('country') ?? 'Bangladesh',
            'is_active'   => 1,
            'notes'       => $this->request->getPost('notes'),
        ]);

        $this->logActivity('customer.create', "Created customer: {$this->request->getPost('email')}");
        return redirect()->to('/admin/customers')->with('success', 'Customer added successfully.');
    }
}
