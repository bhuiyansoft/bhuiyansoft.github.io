<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table      = 'orders';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'tenant_id', 'customer_id', 'order_number', 'status', 'payment_status',
        'payment_method', 'transaction_id', 'subtotal', 'tax_amount', 'shipping_amount',
        'discount_amount', 'total', 'coupon_id', 'shipping_name', 'shipping_email',
        'shipping_phone', 'shipping_address', 'shipping_city', 'shipping_state',
        'shipping_country', 'shipping_postal', 'billing_name', 'billing_address',
        'notes', 'shipped_at', 'delivered_at', 'cancelled_at'
    ];

    protected $useTimestamps = true;

    public function generateOrderNumber(int $tenantId): string
    {
        $prefix = 'ORD-' . $tenantId . '-';
        $number = $prefix . strtoupper(substr(uniqid(), -8));
        while ($this->where('tenant_id', $tenantId)->where('order_number', $number)->first()) {
            $number = $prefix . strtoupper(substr(uniqid(), -8));
        }
        return $number;
    }

    public function getByTenant(int $tenantId, array $filters = [], int $perPage = 20)
    {
        $query = $this->select('orders.*, customers.name as customer_name, customers.email as customer_email')
            ->join('customers', 'customers.id = orders.customer_id', 'left')
            ->where('orders.tenant_id', $tenantId);

        if (!empty($filters['status'])) {
            $query->where('orders.status', $filters['status']);
        }
        if (!empty($filters['payment_status'])) {
            $query->where('orders.payment_status', $filters['payment_status']);
        }
        if (!empty($filters['search'])) {
            $query->groupStart()
                ->like('orders.order_number', $filters['search'])
                ->orLike('customers.name', $filters['search'])
                ->orLike('customers.email', $filters['search'])
                ->groupEnd();
        }
        if (!empty($filters['date_from'])) {
            $query->where('orders.created_at >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->where('orders.created_at <=', $filters['date_to'] . ' 23:59:59');
        }

        return $query->orderBy('orders.created_at', 'DESC')->paginate($perPage);
    }

    public function getWithDetails(int $id, int $tenantId)
    {
        return $this->select('orders.*, customers.name as customer_name, customers.email as customer_email, customers.phone as customer_phone')
            ->join('customers', 'customers.id = orders.customer_id', 'left')
            ->where('orders.id', $id)
            ->where('orders.tenant_id', $tenantId)
            ->first();
    }

    public function getDashboardStats(int $tenantId): array
    {
        $today     = date('Y-m-d');
        $thisMonth = date('Y-m');

        return [
            'total'         => $this->where('tenant_id', $tenantId)->countAllResults(),
            'pending'       => $this->where('tenant_id', $tenantId)->where('status', 'pending')->countAllResults(),
            'processing'    => $this->where('tenant_id', $tenantId)->where('status', 'processing')->countAllResults(),
            'today'         => $this->where('tenant_id', $tenantId)->where('DATE(created_at)', $today)->countAllResults(),
            'revenue_today' => $this->selectSum('total')->where('tenant_id', $tenantId)->where('DATE(created_at)', $today)->where('payment_status', 'paid')->first()['total'] ?? 0,
            'revenue_month' => $this->selectSum('total')->where('tenant_id', $tenantId)->where("DATE_FORMAT(created_at, '%Y-%m')", $thisMonth)->where('payment_status', 'paid')->first()['total'] ?? 0,
        ];
    }

    public function getSalesChart(int $tenantId, int $days = 30): array
    {
        return $this->db->query("
            SELECT DATE(created_at) as date,
                   COUNT(*) as orders,
                   SUM(total) as revenue
            FROM orders
            WHERE tenant_id = ?
              AND payment_status = 'paid'
              AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
            GROUP BY DATE(created_at)
            ORDER BY date ASC
        ", [$tenantId, $days])->getResultArray();
    }

    public function getTopProducts(int $tenantId, int $limit = 5): array
    {
        return $this->db->query("
            SELECT p.name, p.sku, SUM(oi.quantity) as qty_sold, SUM(oi.total_price) as revenue
            FROM order_items oi
            JOIN orders o ON o.id = oi.order_id
            JOIN products p ON p.id = oi.product_id
            WHERE o.tenant_id = ? AND o.payment_status = 'paid'
            GROUP BY oi.product_id
            ORDER BY qty_sold DESC
            LIMIT ?
        ", [$tenantId, $limit])->getResultArray();
    }
}
