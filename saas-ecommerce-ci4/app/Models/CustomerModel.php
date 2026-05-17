<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table      = 'customers';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'tenant_id', 'name', 'email', 'phone', 'password', 'avatar',
        'date_of_birth', 'gender', 'address', 'city', 'state', 'country',
        'postal_code', 'is_active', 'total_orders', 'total_spent', 'notes'
    ];

    protected $useTimestamps = true;

    public function getByTenant(int $tenantId, array $filters = [], int $perPage = 20)
    {
        $query = $this->where('tenant_id', $tenantId);

        if (!empty($filters['search'])) {
            $query->groupStart()
                ->like('name', $filters['search'])
                ->orLike('email', $filters['search'])
                ->orLike('phone', $filters['search'])
                ->groupEnd();
        }
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('created_at', 'DESC')->paginate($perPage);
    }

    public function getWithStats(int $id, int $tenantId)
    {
        return $this->select('customers.*, COUNT(orders.id) as order_count, SUM(orders.total) as lifetime_value')
            ->join('orders', 'orders.customer_id = customers.id AND orders.payment_status = "paid"', 'left')
            ->where('customers.id', $id)
            ->where('customers.tenant_id', $tenantId)
            ->groupBy('customers.id')
            ->first();
    }

    public function findByEmail(int $tenantId, string $email)
    {
        return $this->where('tenant_id', $tenantId)->where('email', $email)->first();
    }

    public function updateOrderStats(int $customerId, float $orderTotal): void
    {
        $this->db->query(
            "UPDATE customers SET total_orders = total_orders + 1, total_spent = total_spent + ? WHERE id = ?",
            [$orderTotal, $customerId]
        );
    }

    public function getDashboardStats(int $tenantId): array
    {
        return [
            'total'  => $this->where('tenant_id', $tenantId)->countAllResults(),
            'active' => $this->where('tenant_id', $tenantId)->where('is_active', 1)->countAllResults(),
            'new_this_month' => $this->where('tenant_id', $tenantId)
                ->where("DATE_FORMAT(created_at, '%Y-%m')", date('Y-m'))
                ->countAllResults(),
        ];
    }
}
