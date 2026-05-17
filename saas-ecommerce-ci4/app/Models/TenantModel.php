<?php

namespace App\Models;

use CodeIgniter\Model;

class TenantModel extends Model
{
    protected $table      = 'tenants';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'name', 'email', 'phone', 'subdomain', 'custom_domain', 'plan_id',
        'logo', 'address', 'city', 'state', 'country', 'postal_code',
        'currency', 'currency_symbol', 'timezone', 'tax_rate', 'shipping_rate',
        'status', 'trial_ends_at', 'settings'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name'      => 'required|min_length[2]|max_length[200]',
        'email'     => 'required|valid_email|is_unique[tenants.email,id,{id}]',
        'subdomain' => 'required|min_length[3]|max_length[100]|alpha_dash|is_unique[tenants.subdomain,id,{id}]',
    ];

    public function getWithPlan($id)
    {
        return $this->select('tenants.*, plans.name as plan_name, plans.price as plan_price')
            ->join('plans', 'plans.id = tenants.plan_id', 'left')
            ->where('tenants.id', $id)
            ->first();
    }

    public function getAllWithPlan($perPage = 20)
    {
        return $this->select('tenants.*, plans.name as plan_name, plans.slug as plan_slug')
            ->join('plans', 'plans.id = tenants.plan_id', 'left')
            ->paginate($perPage);
    }

    public function getBySubdomain($subdomain)
    {
        return $this->where('subdomain', $subdomain)
            ->where('status !=', 'cancelled')
            ->first();
    }

    public function getDashboardStats()
    {
        $db = \Config\Database::connect();
        return [
            'total'     => $this->countAll(),
            'active'    => $this->where('status', 'active')->countAllResults(),
            'trial'     => $this->where('status', 'trial')->countAllResults(),
            'suspended' => $this->where('status', 'suspended')->countAllResults(),
        ];
    }

    public function getMonthlyRevenue()
    {
        return $this->db->query("
            SELECT DATE_FORMAT(s.created_at, '%Y-%m') as month,
                   SUM(s.amount) as revenue,
                   COUNT(s.id) as subscriptions
            FROM subscriptions s
            WHERE s.status = 'active'
            GROUP BY month
            ORDER BY month DESC
            LIMIT 12
        ")->getResultArray();
    }
}
