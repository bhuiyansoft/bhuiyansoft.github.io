<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;

class ProductsController extends BaseController
{
    protected ProductModel $productModel;
    protected CategoryModel $categoryModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request,
                                   \CodeIgniter\HTTP\ResponseInterface $response,
                                   \Psr\Log\LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    private function getTenantId(): int
    {
        return (int) ($this->request->getHeaderLine('X-Tenant-ID') ?: session('tenant_id') ?: 1);
    }

    public function index(): \CodeIgniter\HTTP\ResponseInterface
    {
        $tenantId   = $this->getTenantId();
        $page       = (int) ($this->request->getGet('page') ?? 1);
        $perPage    = min((int) ($this->request->getGet('per_page') ?? 20), 100);
        $categoryId = $this->request->getGet('category');
        $search     = $this->request->getGet('search');
        $featured   = $this->request->getGet('featured');

        $builder = $this->productModel
            ->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.tenant_id', $tenantId)
            ->where('products.status', 'active');

        if ($categoryId) {
            $builder->where('products.category_id', $categoryId);
        }
        if ($search) {
            $builder->groupStart()
                ->like('products.name', $search)
                ->orLike('products.sku', $search)
                ->groupEnd();
        }
        if ($featured === '1') {
            $builder->where('products.is_featured', 1);
        }

        $total    = $builder->countAllResults(false);
        $products = $builder->orderBy('products.name', 'ASC')
                            ->limit($perPage, ($page - 1) * $perPage)
                            ->findAll();

        foreach ($products as &$p) {
            $p['image_url'] = $p['image'] ? base_url($p['image']) : null;
        }

        return $this->jsonResponse([
            'data'  => $products,
            'meta'  => [
                'total'    => $total,
                'page'     => $page,
                'per_page' => $perPage,
                'pages'    => (int) ceil($total / $perPage),
            ],
        ]);
    }

    public function show(string $slug): \CodeIgniter\HTTP\ResponseInterface
    {
        $tenantId = $this->getTenantId();
        $product  = $this->productModel
            ->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.tenant_id', $tenantId)
            ->where('products.slug', $slug)
            ->where('products.status', 'active')
            ->first();

        if (!$product) {
            return $this->jsonResponse(['message' => 'Product not found'], 404);
        }

        $product['image_url'] = $product['image'] ? base_url($product['image']) : null;

        $this->productModel->incrementViews($product['id']);

        return $this->jsonResponse(['data' => $product]);
    }

    public function featured(): \CodeIgniter\HTTP\ResponseInterface
    {
        $tenantId = $this->getTenantId();
        $products = $this->productModel->getFeatured($tenantId, 8);

        foreach ($products as &$p) {
            $p['image_url'] = $p['image'] ? base_url($p['image']) : null;
        }

        return $this->jsonResponse(['data' => $products]);
    }

    public function search(): \CodeIgniter\HTTP\ResponseInterface
    {
        $tenantId = $this->getTenantId();
        $q        = trim($this->request->getGet('q') ?? '');

        if (strlen($q) < 2) {
            return $this->jsonResponse(['data' => [], 'message' => 'Query too short']);
        }

        $products = $this->productModel
            ->where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->groupStart()
                ->like('name', $q)
                ->orLike('sku', $q)
                ->orLike('short_description', $q)
            ->groupEnd()
            ->select('id, name, slug, price, sale_price, image, stock')
            ->limit(10)
            ->findAll();

        foreach ($products as &$p) {
            $p['image_url'] = $p['image'] ? base_url($p['image']) : null;
        }

        return $this->jsonResponse(['data' => $products]);
    }
}
