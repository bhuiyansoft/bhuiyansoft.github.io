<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class CategoriesController extends BaseController
{
    protected CategoryModel $categoryModel;
    protected int $tenantId;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->tenantId = $this->currentUser['tenant_id'];
    }

    public function index()
    {
        return view('admin/categories/index', [
            'title'       => 'Categories',
            'categories'  => $this->categoryModel->getWithProductCount($this->tenantId),
            'currentUser' => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function create()
    {
        return view('admin/categories/create', [
            'title'       => 'Add Category',
            'categories'  => $this->categoryModel->getByTenant($this->tenantId),
            'currentUser' => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function store()
    {
        $rules = ['name' => 'required|min_length[2]|max_length[200]'];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->insert([
            'tenant_id'  => $this->tenantId,
            'parent_id'  => $this->request->getPost('parent_id') ?: null,
            'name'       => $this->request->getPost('name'),
            'slug'       => $this->categoryModel->makeSlug($this->request->getPost('name'), $this->tenantId),
            'description' => $this->request->getPost('description'),
            'sort_order' => $this->request->getPost('sort_order') ?? 0,
            'is_active'  => 1,
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Category created.');
    }

    public function edit(int $id)
    {
        $category = $this->categoryModel->where('tenant_id', $this->tenantId)->find($id);
        if (!$category) {
            return redirect()->to('/admin/categories')->with('error', 'Category not found.');
        }

        return view('admin/categories/edit', [
            'title'       => 'Edit: ' . $category['name'],
            'category'    => $category,
            'categories'  => $this->categoryModel->getByTenant($this->tenantId),
            'currentUser' => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function update(int $id)
    {
        $category = $this->categoryModel->where('tenant_id', $this->tenantId)->find($id);
        if (!$category) {
            return redirect()->to('/admin/categories')->with('error', 'Category not found.');
        }

        $this->categoryModel->update($id, [
            'parent_id'  => $this->request->getPost('parent_id') ?: null,
            'name'       => $this->request->getPost('name'),
            'slug'       => $this->categoryModel->makeSlug($this->request->getPost('name'), $this->tenantId, $id),
            'description' => $this->request->getPost('description'),
            'sort_order' => $this->request->getPost('sort_order') ?? 0,
            'is_active'  => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Category updated.');
    }

    public function delete(int $id)
    {
        $category = $this->categoryModel->where('tenant_id', $this->tenantId)->find($id);
        if (!$category) {
            return redirect()->to('/admin/categories')->with('error', 'Category not found.');
        }

        $this->categoryModel->delete($id);
        return redirect()->to('/admin/categories')->with('success', 'Category deleted.');
    }
}
