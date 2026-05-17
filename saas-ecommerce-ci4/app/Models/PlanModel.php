<?php

namespace App\Models;

use CodeIgniter\Model;

class PlanModel extends Model
{
    protected $table      = 'plans';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'name', 'slug', 'price', 'billing_cycle', 'max_products',
        'max_orders', 'max_staff', 'storage_gb', 'features', 'is_active', 'sort_order'
    ];

    protected $useTimestamps = true;

    public function getActivePlans()
    {
        return $this->where('is_active', 1)->orderBy('sort_order', 'ASC')->findAll();
    }

    public function getPlanWithTenantCount()
    {
        return $this->select('plans.*, COUNT(tenants.id) as tenant_count')
            ->join('tenants', 'tenants.plan_id = plans.id', 'left')
            ->groupBy('plans.id')
            ->orderBy('plans.sort_order', 'ASC')
            ->findAll();
    }

    public function decodeFeatures(array $plan): array
    {
        if (is_string($plan['features'])) {
            $plan['features'] = json_decode($plan['features'], true) ?? [];
        }
        return $plan;
    }
}
