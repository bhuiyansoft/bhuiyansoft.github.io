<?php

namespace App\Controllers\Store;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\TenantModel;

class ShopController extends BaseController
{
    protected int $tenantId;
    protected array $tenant;
    protected ProductModel $productModel;
    protected CategoryModel $categoryModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->tenantId     = (int)(session()->get('store_tenant_id') ?? 1);
        $tenantModel        = new TenantModel();
        $this->tenant       = $tenantModel->find($this->tenantId) ?? [];
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $filters = [
            'category_id' => $this->request->getGet('category'),
            'search'      => $this->request->getGet('q'),
            'min_price'   => $this->request->getGet('min_price'),
            'max_price'   => $this->request->getGet('max_price'),
            'sort'        => $this->request->getGet('sort') ?? 'newest',
        ];

        $products   = $this->productModel->getActiveByTenant($this->tenantId, $filters, 12);
        $featured   = $this->productModel->getFeatured($this->tenantId, 6);
        $categories = $this->categoryModel->getWithProductCount($this->tenantId);

        return view('store/shop', [
            'title'      => 'Shop — ' . ($this->tenant['name'] ?? ''),
            'products'   => $products,
            'pager'      => $this->productModel->pager,
            'featured'   => $featured,
            'categories' => $categories,
            'filters'    => $filters,
            'tenant'     => $this->tenant,
        ]);
    }

    public function product(string $slug)
    {
        $product = $this->productModel->getBySlug($this->tenantId, $slug);
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->productModel->incrementViews($product['id']);

        // Related products from same category
        $related = [];
        if ($product['category_id']) {
            $related = $this->productModel->where('tenant_id', $this->tenantId)
                ->where('status', 'active')
                ->where('category_id', $product['category_id'])
                ->where('id !=', $product['id'])
                ->limit(4)
                ->findAll();
        }

        return view('store/product', [
            'title'   => $product['name'] . ' — ' . ($this->tenant['name'] ?? ''),
            'product' => $product,
            'related' => $related,
            'tenant'  => $this->tenant,
        ]);
    }

    public function category(string $slug)
    {
        $category = $this->categoryModel->getBySlug($this->tenantId, $slug);
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $filters  = ['category_id' => $category['id'], 'sort' => $this->request->getGet('sort') ?? 'newest'];
        $products = $this->productModel->getActiveByTenant($this->tenantId, $filters, 12);

        return view('store/shop', [
            'title'      => $category['name'] . ' — ' . ($this->tenant['name'] ?? ''),
            'products'   => $products,
            'pager'      => $this->productModel->pager,
            'categories' => $this->categoryModel->getWithProductCount($this->tenantId),
            'filters'    => $filters,
            'tenant'     => $this->tenant,
            'category'   => $category,
        ]);
    }

    public function search()
    {
        $query   = $this->request->getGet('q') ?? '';
        $filters = ['search' => $query, 'sort' => 'newest'];
        $products = $this->productModel->getActiveByTenant($this->tenantId, $filters, 12);

        return view('store/shop', [
            'title'      => "Search: {$query}",
            'products'   => $products,
            'pager'      => $this->productModel->pager,
            'categories' => $this->categoryModel->getWithProductCount($this->tenantId),
            'filters'    => $filters,
            'tenant'     => $this->tenant,
        ]);
    }
}
