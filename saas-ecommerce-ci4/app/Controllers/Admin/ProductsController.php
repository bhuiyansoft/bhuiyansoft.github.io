<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;

class ProductsController extends BaseController
{
    protected ProductModel $productModel;
    protected CategoryModel $categoryModel;
    protected int $tenantId;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->tenantId = $this->currentUser['tenant_id'];
    }

    public function index()
    {
        $filters = [
            'status'      => $this->request->getGet('status'),
            'category_id' => $this->request->getGet('category_id'),
            'search'      => $this->request->getGet('search'),
        ];

        return view('admin/products/index', [
            'title'       => 'Products',
            'products'    => $this->productModel->getByTenant($this->tenantId, $filters, 20),
            'pager'       => $this->productModel->pager,
            'categories'  => $this->categoryModel->getByTenant($this->tenantId),
            'filters'     => $filters,
            'currentUser' => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function create()
    {
        return view('admin/products/create', [
            'title'       => 'Add Product',
            'categories'  => $this->categoryModel->getByTenant($this->tenantId, true),
            'currentUser' => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function store()
    {
        $rules = [
            'name'  => 'required|min_length[2]|max_length[300]',
            'price' => 'required|decimal',
            'stock' => 'required|is_natural',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $slug  = $this->productModel->makeSlug($this->request->getPost('name'), $this->tenantId);
        $image = $this->handleImageUpload();

        $this->productModel->insert([
            'tenant_id'         => $this->tenantId,
            'category_id'       => $this->request->getPost('category_id') ?: null,
            'sku'               => $this->request->getPost('sku') ?: 'SKU-' . strtoupper(substr(uniqid(), -6)),
            'name'              => $this->request->getPost('name'),
            'slug'              => $slug,
            'description'       => $this->request->getPost('description'),
            'short_description' => $this->request->getPost('short_description'),
            'price'             => $this->request->getPost('price'),
            'sale_price'        => $this->request->getPost('sale_price') ?: null,
            'cost_price'        => $this->request->getPost('cost_price') ?: null,
            'stock'             => $this->request->getPost('stock'),
            'low_stock_alert'   => $this->request->getPost('low_stock_alert') ?? 5,
            'unit'              => $this->request->getPost('unit') ?? 'pcs',
            'weight'            => $this->request->getPost('weight') ?: null,
            'type'              => $this->request->getPost('type') ?? 'physical',
            'status'            => $this->request->getPost('status') ?? 'active',
            'is_featured'       => $this->request->getPost('is_featured') ? 1 : 0,
            'image'             => $image,
            'meta_title'        => $this->request->getPost('meta_title'),
            'meta_description'  => $this->request->getPost('meta_description'),
        ]);

        $this->logActivity('product.create', "Created product: {$this->request->getPost('name')}");
        return redirect()->to('/admin/products')->with('success', 'Product created successfully.');
    }

    public function edit(int $id)
    {
        $product = $this->productModel->where('tenant_id', $this->tenantId)->find($id);
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Product not found.');
        }

        return view('admin/products/edit', [
            'title'       => 'Edit: ' . $product['name'],
            'product'     => $product,
            'categories'  => $this->categoryModel->getByTenant($this->tenantId, true),
            'currentUser' => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function update(int $id)
    {
        $product = $this->productModel->where('tenant_id', $this->tenantId)->find($id);
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Product not found.');
        }

        $rules = [
            'name'  => 'required|min_length[2]|max_length[300]',
            'price' => 'required|decimal',
            'stock' => 'required|is_natural',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'category_id'       => $this->request->getPost('category_id') ?: null,
            'sku'               => $this->request->getPost('sku'),
            'name'              => $this->request->getPost('name'),
            'slug'              => $this->productModel->makeSlug($this->request->getPost('name'), $this->tenantId, $id),
            'description'       => $this->request->getPost('description'),
            'short_description' => $this->request->getPost('short_description'),
            'price'             => $this->request->getPost('price'),
            'sale_price'        => $this->request->getPost('sale_price') ?: null,
            'cost_price'        => $this->request->getPost('cost_price') ?: null,
            'stock'             => $this->request->getPost('stock'),
            'low_stock_alert'   => $this->request->getPost('low_stock_alert') ?? 5,
            'unit'              => $this->request->getPost('unit'),
            'weight'            => $this->request->getPost('weight') ?: null,
            'type'              => $this->request->getPost('type'),
            'status'            => $this->request->getPost('status'),
            'is_featured'       => $this->request->getPost('is_featured') ? 1 : 0,
            'meta_title'        => $this->request->getPost('meta_title'),
            'meta_description'  => $this->request->getPost('meta_description'),
        ];

        $newImage = $this->handleImageUpload();
        if ($newImage) {
            $data['image'] = $newImage;
        }

        $this->productModel->update($id, $data);
        $this->logActivity('product.update', "Updated product ID: $id");
        return redirect()->to('/admin/products')->with('success', 'Product updated successfully.');
    }

    public function delete(int $id)
    {
        $product = $this->productModel->where('tenant_id', $this->tenantId)->find($id);
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Product not found.');
        }

        $this->productModel->delete($id);
        $this->logActivity('product.delete', "Deleted product: {$product['name']}");
        return redirect()->to('/admin/products')->with('success', 'Product deleted.');
    }

    public function show(int $id)
    {
        $product = $this->productModel->where('tenant_id', $this->tenantId)->find($id);
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Product not found.');
        }

        return view('admin/products/show', [
            'title'       => $product['name'],
            'product'     => $product,
            'currentUser' => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    private function handleImageUpload(): ?string
    {
        $file = $this->request->getFile('image');
        if (!$file || !$file->isValid() || $file->isMove()) {
            return null;
        }

        $newName  = $file->getRandomName();
        $uploadPath = WRITEPATH . 'uploads/products/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file->move($uploadPath, $newName);
        return 'uploads/products/' . $newName;
    }
}
