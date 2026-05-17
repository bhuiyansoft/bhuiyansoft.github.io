<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'products';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'tenant_id', 'category_id', 'sku', 'name', 'slug', 'description',
        'short_description', 'price', 'sale_price', 'cost_price', 'stock',
        'low_stock_alert', 'weight', 'unit', 'image', 'gallery', 'type',
        'status', 'is_featured', 'meta_title', 'meta_description', 'total_sold', 'views'
    ];

    protected $useTimestamps = true;

    public function getByTenant(int $tenantId, array $filters = [], int $perPage = 20)
    {
        $query = $this->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.tenant_id', $tenantId);

        if (!empty($filters['status'])) {
            $query->where('products.status', $filters['status']);
        }
        if (!empty($filters['category_id'])) {
            $query->where('products.category_id', $filters['category_id']);
        }
        if (!empty($filters['search'])) {
            $query->groupStart()
                ->like('products.name', $filters['search'])
                ->orLike('products.sku', $filters['search'])
                ->groupEnd();
        }
        if (!empty($filters['featured'])) {
            $query->where('products.is_featured', 1);
        }

        return $query->orderBy('products.created_at', 'DESC')->paginate($perPage);
    }

    public function getActiveByTenant(int $tenantId, array $filters = [], int $perPage = 12)
    {
        $query = $this->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.tenant_id', $tenantId)
            ->where('products.status', 'active');

        if (!empty($filters['category_id'])) {
            $query->where('products.category_id', $filters['category_id']);
        }
        if (!empty($filters['search'])) {
            $query->groupStart()
                ->like('products.name', $filters['search'])
                ->orLike('products.description', $filters['search'])
                ->groupEnd();
        }
        if (!empty($filters['min_price'])) {
            $query->where('products.price >=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('products.price <=', $filters['max_price']);
        }

        $sort = $filters['sort'] ?? 'newest';
        match ($sort) {
            'price_asc'  => $query->orderBy('COALESCE(products.sale_price, products.price)', 'ASC'),
            'price_desc' => $query->orderBy('COALESCE(products.sale_price, products.price)', 'DESC'),
            'popular'    => $query->orderBy('products.total_sold', 'DESC'),
            default      => $query->orderBy('products.created_at', 'DESC'),
        };

        return $query->paginate($perPage);
    }

    public function getBySlug(int $tenantId, string $slug)
    {
        return $this->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.tenant_id', $tenantId)
            ->where('products.slug', $slug)
            ->where('products.status', 'active')
            ->first();
    }

    public function getFeatured(int $tenantId, int $limit = 6)
    {
        return $this->where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->where('is_featured', 1)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getLowStock(int $tenantId)
    {
        return $this->where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->where('stock <=', $this->db->escapeIdentifiers('low_stock_alert'), false)
            ->findAll();
    }

    public function getDashboardStats(int $tenantId): array
    {
        return [
            'total'    => $this->where('tenant_id', $tenantId)->countAllResults(),
            'active'   => $this->where('tenant_id', $tenantId)->where('status', 'active')->countAllResults(),
            'inactive' => $this->where('tenant_id', $tenantId)->where('status', 'inactive')->countAllResults(),
            'low_stock' => $this->where('tenant_id', $tenantId)->where('stock <=', $this->db->escapeIdentifiers('low_stock_alert'), false)->countAllResults(),
        ];
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

    public function incrementViews(int $id)
    {
        $this->db->query("UPDATE products SET views = views + 1 WHERE id = ?", [$id]);
    }
}
