<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\CustomerModel;

class ReportsController extends BaseController
{
    protected int $tenantId;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->tenantId = $this->currentUser['tenant_id'];
    }

    public function index()
    {
        $orderModel = new OrderModel();
        $period     = $this->request->getGet('period') ?? 30;

        return view('admin/reports/index', [
            'title'         => 'Reports & Analytics',
            'sales_chart'   => $orderModel->getSalesChart($this->tenantId, $period),
            'top_products'  => $orderModel->getTopProducts($this->tenantId, 10),
            'order_stats'   => $orderModel->getDashboardStats($this->tenantId),
            'period'        => $period,
            'currentUser'   => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function sales()
    {
        $orderModel = new OrderModel();
        $dateFrom   = $this->request->getGet('date_from') ?? date('Y-m-01');
        $dateTo     = $this->request->getGet('date_to') ?? date('Y-m-d');

        $orders = $orderModel->where('tenant_id', $this->tenantId)
            ->where('payment_status', 'paid')
            ->where('created_at >=', $dateFrom)
            ->where('created_at <=', $dateTo . ' 23:59:59')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $totalRevenue = array_sum(array_column($orders, 'total'));
        $totalOrders  = count($orders);
        $avgOrder     = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return view('admin/reports/sales', [
            'title'         => 'Sales Report',
            'orders'        => $orders,
            'total_revenue' => $totalRevenue,
            'total_orders'  => $totalOrders,
            'avg_order'     => $avgOrder,
            'date_from'     => $dateFrom,
            'date_to'       => $dateTo,
            'currentUser'   => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function products()
    {
        $orderModel = new OrderModel();
        return view('admin/reports/products', [
            'title'        => 'Product Report',
            'top_products' => $orderModel->getTopProducts($this->tenantId, 20),
            'currentUser'  => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function customers()
    {
        $db = db_connect();
        $topCustomers = $db->query("
            SELECT c.name, c.email, COUNT(o.id) as order_count, SUM(o.total) as total_spent
            FROM customers c
            JOIN orders o ON o.customer_id = c.id AND o.payment_status = 'paid'
            WHERE c.tenant_id = ?
            GROUP BY c.id
            ORDER BY total_spent DESC
            LIMIT 20
        ", [$this->tenantId])->getResultArray();

        return view('admin/reports/customers', [
            'title'          => 'Customer Report',
            'top_customers'  => $topCustomers,
            'currentUser'    => $this->currentUser,
            'currentTenant'  => $this->currentTenant,
        ]);
    }
}
