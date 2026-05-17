<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class OrdersController extends BaseController
{
    protected OrderModel $orderModel;
    protected OrderItemModel $itemModel;
    protected int $tenantId;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->itemModel  = new OrderItemModel();
    }

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->tenantId = $this->currentUser['tenant_id'];
    }

    public function index()
    {
        $filters = [
            'status'         => $this->request->getGet('status'),
            'payment_status' => $this->request->getGet('payment_status'),
            'search'         => $this->request->getGet('search'),
            'date_from'      => $this->request->getGet('date_from'),
            'date_to'        => $this->request->getGet('date_to'),
        ];

        return view('admin/orders/index', [
            'title'       => 'Orders',
            'orders'      => $this->orderModel->getByTenant($this->tenantId, $filters),
            'pager'       => $this->orderModel->pager,
            'filters'     => $filters,
            'currentUser' => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function show(int $id)
    {
        $order = $this->orderModel->getWithDetails($id, $this->tenantId);
        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Order not found.');
        }

        return view('admin/orders/show', [
            'title'       => 'Order #' . $order['order_number'],
            'order'       => $order,
            'items'       => $this->itemModel->getByOrder($id),
            'currentUser' => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function updateStatus(int $id)
    {
        $order = $this->orderModel->where('tenant_id', $this->tenantId)->find($id);
        if (!$order) {
            return $this->jsonResponse(['success' => false, 'message' => 'Order not found.'], 404);
        }

        $status        = $this->request->getPost('status');
        $paymentStatus = $this->request->getPost('payment_status');
        $data          = [];

        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];
        if ($status && in_array($status, $validStatuses)) {
            $data['status'] = $status;
            if ($status === 'shipped')   $data['shipped_at']   = date('Y-m-d H:i:s');
            if ($status === 'delivered') $data['delivered_at'] = date('Y-m-d H:i:s');
            if ($status === 'cancelled') $data['cancelled_at'] = date('Y-m-d H:i:s');
        }

        $validPayment = ['unpaid', 'paid', 'partial', 'refunded'];
        if ($paymentStatus && in_array($paymentStatus, $validPayment)) {
            $data['payment_status'] = $paymentStatus;
        }

        if ($data) {
            $this->orderModel->update($id, $data);
            $this->logActivity('order.update', "Updated order #{$order['order_number']} status", 'order', $id);
        }

        if ($this->request->isAJAX()) {
            return $this->jsonResponse(['success' => true]);
        }

        return redirect()->back()->with('success', 'Order status updated.');
    }

    public function invoice(int $id)
    {
        $order = $this->orderModel->getWithDetails($id, $this->tenantId);
        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Order not found.');
        }

        return view('admin/orders/invoice', [
            'title'         => 'Invoice #' . $order['order_number'],
            'order'         => $order,
            'items'         => $this->itemModel->getByOrder($id),
            'currentTenant' => $this->currentTenant,
        ]);
    }
}
