<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table      = 'categories';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'tenant_id', 'parent_id', 'name', 'slug', 'description', 'image', 'sort_order', 'is_active'
    ];

    protected $useTimestamps = true;

    public function getByTenant(int $tenantId, bool $activeOnly = false)
    {
        $query = $this->where('tenant_id', $tenantId);
        if ($activeOnly) {
            $query->where('is_active', 1);
        }
        return $query->orderBy('sort_order', 'ASC')->findAll();
    }

    public function getWithProductCount(int $tenantId)
    {
        return $this->select('categories.*, COUNT(products.id) as product_count')
            ->join('products', 'products.category_id = categories.id AND products.status = "active"', 'left')
            ->where('categories.tenant_id', $tenantId)
            ->groupBy('categories.id')
            ->orderBy('categories.sort_order', 'ASC')
            ->findAll();
    }

    public function getBySlug(int $tenantId, string $slug)
    {
        return $this->where('tenant_id', $tenantId)->where('slug', $slug)->first();
    }

    public function makeSlug(string $name, int $tenantId, ?int $excludeId = null): string
    {
        $slug = url_title($name, '-', true);
        $original = $slug;
        $counter = 1;

        while (true) {
            $query = $this->where('tenant_id', $tenantId)->where('slug', $slug);
            if ($excludeId) {
                $query->where('id !=', $excludeId);
            }
            if (!$query->first()) {
                break;
            }
            $slug = $original . '-' . $counter++;
        }
        return $slug;
    }
}
