<?php

namespace App\Models;

use CodeIgniter\Model;

class TenantModel extends Model
{
    protected $table = 'tenants';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name', 'slug', 'plan_id', 'plan_expires_at', 'status',
        'logo', 'primary_color', 'website', 'industry', 'size', 'settings',
    ];
    protected $useTimestamps = true;

    public function findBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }

    public function getPlan(int $tenantId): ?array
    {
        return $this->db->table('tenants t')
            ->select('p.*')
            ->join('plans p', 'p.id = t.plan_id')
            ->where('t.id', $tenantId)
            ->get()->getRowArray();
    }

    public function hasFeature(int $tenantId, string $feature): bool
    {
        $plan = $this->getPlan($tenantId);
        if (!$plan) return false;
        $features = json_decode($plan['features'] ?? '[]', true);
        return in_array($feature, $features);
    }

    public function generateSlug(string $name): string
    {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        $slug = trim($slug, '-');
        $original = $slug;
        $i = 1;
        while ($this->where('slug', $slug)->countAllResults() > 0) {
            $slug = $original . '-' . $i++;
        }
        return $slug;
    }
}
