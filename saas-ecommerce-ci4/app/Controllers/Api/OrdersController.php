<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\OrderModel;

class OrdersController extends BaseController
{
    protected OrderModel $orderModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request,
                                   \CodeIgniter\HTTP\ResponseInterface $response,
                                   \Psr\Log\LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);
        $this->orderModel = new OrderModel();
    }

    private function getTenantId(): int
    {
        return (int) ($this->request->getHeaderLine('X-Tenant-ID') ?: session('tenant_id') ?: 1);
    }

    private function requireAuth(): bool
    {
        return session()->get('user_id') !== null;
    }

    public function index(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->requireAuth()) {
            return $this->jsonResponse(['message' => 'Unauthorized'], 401);
        }

        $tenantId = $this->getTenantId();
        $page     = (int) ($this->request->getGet('page') ?? 1);
        $perPage  = min((int) ($this->request->getGet('per_page') ?? 20), 100);
        $status   = $this->request->getGet('status');

        $filters = [];
        if ($status) {
            $filters['status'] = $status;
        }

        $result = $this->orderModel->getByTenant($tenantId, $filters, $page, $perPage);

        return $this->jsonResponse([
            'data' => $result['orders'],
            'meta' => [
                'total'    => $result['total'],
                'page'     => $page,
                'per_page' => $perPage,
                'pages'    => (int) ceil($result['total'] / $perPage),
            ],
        ]);
    }

    public function show(int $id): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->requireAuth()) {
            return $this->jsonResponse(['message' => 'Unauthorized'], 401);
        }

        $tenantId = $this->getTenantId();
        $order    = $this->orderModel->getWithDetails($id, $tenantId);

        if (!$order) {
            return $this->jsonResponse(['message' => 'Order not found'], 404);
        }

        return $this->jsonResponse(['data' => $order]);
    }

    public function updateStatus(int $id): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->requireAuth()) {
            return $this->jsonResponse(['message' => 'Unauthorized'], 401);
        }

        $tenantId = $this->getTenantId();
        $order    = $this->orderModel->where('tenant_id', $tenantId)->find($id);

        if (!$order) {
            return $this->jsonResponse(['message' => 'Order not found'], 404);
        }

        $input  = $this->request->getJSON(true);
        $status = $input['status'] ?? null;

        $allowed = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];
        if (!in_array($status, $allowed, true)) {
            return $this->jsonResponse(['message' => 'Invalid status'], 422);
        }

        $this->orderModel->update($id, ['status' => $status]);

        return $this->jsonResponse(['message' => 'Status updated', 'status' => $status]);
    }

    public function stats(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->requireAuth()) {
            return $this->jsonResponse(['message' => 'Unauthorized'], 401);
        }

        $tenantId = $this->getTenantId();
        $stats    = $this->orderModel->getDashboardStats($tenantId);

        return $this->jsonResponse(['data' => $stats]);
    }
}
