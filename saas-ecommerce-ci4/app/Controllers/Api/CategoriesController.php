<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class CategoriesController extends BaseController
{
    protected CategoryModel $categoryModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request,
                                   \CodeIgniter\HTTP\ResponseInterface $response,
                                   \Psr\Log\LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);
        $this->categoryModel = new CategoryModel();
    }

    private function getTenantId(): int
    {
        return (int) ($this->request->getHeaderLine('X-Tenant-ID') ?: session('tenant_id') ?: 1);
    }

    public function index(): \CodeIgniter\HTTP\ResponseInterface
    {
        $tenantId = $this->getTenantId();

        $categories = $this->categoryModel
            ->where('tenant_id', $tenantId)
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();

        $nested = $this->buildTree($categories);

        return $this->jsonResponse(['data' => $nested]);
    }

    public function show(int $id): \CodeIgniter\HTTP\ResponseInterface
    {
        $tenantId = $this->getTenantId();
        $category = $this->categoryModel
            ->where('tenant_id', $tenantId)
            ->where('is_active', 1)
            ->find($id);

        if (!$category) {
            return $this->jsonResponse(['message' => 'Category not found'], 404);
        }

        $products = db_connect()
            ->table('products')
            ->select('id, name, slug, price, sale_price, image, stock')
            ->where('tenant_id', $tenantId)
            ->where('category_id', $id)
            ->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();

        foreach ($products as &$p) {
            $p['image_url'] = $p['image'] ? base_url($p['image']) : null;
        }

        $category['products'] = $products;

        return $this->jsonResponse(['data' => $category]);
    }

    private function buildTree(array $categories, int $parentId = 0): array
    {
        $tree = [];
        foreach ($categories as $cat) {
            if ((int) $cat['parent_id'] === $parentId) {
                $cat['children'] = $this->buildTree($categories, (int) $cat['id']);
                $tree[] = $cat;
            }
        }
        return $tree;
    }
}
