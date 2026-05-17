<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\CustomerModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $tenantId     = $this->currentUser['tenant_id'];
        $orderModel   = new OrderModel();
        $productModel = new ProductModel();
        $customerModel = new CustomerModel();

        $orderStats   = $orderModel->getDashboardStats($tenantId);
        $productStats = $productModel->getDashboardStats($tenantId);
        $customerStats = $customerModel->getDashboardStats($tenantId);
        $salesChart   = $orderModel->getSalesChart($tenantId, 30);
        $topProducts  = $orderModel->getTopProducts($tenantId, 5);
        $lowStock     = $productModel->getLowStock($tenantId);

        $recentOrders = $orderModel->select('orders.*, customers.name as customer_name')
            ->join('customers', 'customers.id = orders.customer_id', 'left')
            ->where('orders.tenant_id', $tenantId)
            ->orderBy('orders.created_at', 'DESC')
            ->limit(8)
            ->findAll();

        return view('admin/dashboard', [
            'title'         => 'Dashboard',
            'order_stats'   => $orderStats,
            'product_stats' => $productStats,
            'customer_stats' => $customerStats,
            'sales_chart'   => $salesChart,
            'top_products'  => $topProducts,
            'low_stock'     => $lowStock,
            'recent_orders' => $recentOrders,
            'currentUser'   => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }
}
