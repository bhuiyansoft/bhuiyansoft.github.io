<?php

namespace App\Controllers\Admin;

use App\Models\DepartmentModel;
use App\Models\EmployeeModel;

class DepartmentController extends BaseAdminController
{
    protected DepartmentModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new DepartmentModel();
    }

    public function index(): string
    {
        return view('departments/index', [
            'title'       => 'Departments',
            'departments' => $this->model->getWithHead(),
        ]);
    }

    public function create(): string
    {
        $empModel = new EmployeeModel();
        return view('departments/create', [
            'title'     => 'Add Department',
            'employees' => $empModel->getActiveEmployees(),
        ]);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]|is_unique[departments.name]',
            'code' => 'permit_empty|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->insert([
            'name'        => $this->request->getPost('name'),
            'code'        => $this->request->getPost('code'),
            'head_id'     => $this->request->getPost('head_id') ?: null,
            'description' => $this->request->getPost('description'),
            'status'      => 1,
        ]);

        return redirect()->to(base_url('admin/departments'))->with('success', 'Department created successfully.');
    }

    public function edit(int $id): string
    {
        $dept = $this->model->find($id);
        if (!$dept) {
            return redirect()->to(base_url('admin/departments'))->with('error', 'Department not found.');
        }
        $empModel = new EmployeeModel();
        return view('departments/edit', [
            'title'      => 'Edit Department',
            'department' => $dept,
            'employees'  => $empModel->getActiveEmployees(),
        ]);
    }

    public function update(int $id)
    {
        $rules = [
            'name' => "required|min_length[2]|max_length[100]|is_unique[departments.name,id,{$id}]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->update($id, [
            'name'        => $this->request->getPost('name'),
            'code'        => $this->request->getPost('code'),
            'head_id'     => $this->request->getPost('head_id') ?: null,
            'description' => $this->request->getPost('description'),
            'status'      => $this->request->getPost('status') ?? 1,
        ]);

        return redirect()->to(base_url('admin/departments'))->with('success', 'Department updated successfully.');
    }

    public function delete(int $id)
    {
        if (!$this->hasPermission(['super_admin', 'admin'])) {
            return $this->denyAccess();
        }

        $empModel = new EmployeeModel();
        $count    = $empModel->where('department_id', $id)->countAllResults();

        if ($count > 0) {
            return redirect()->to(base_url('admin/departments'))->with('error', 'Cannot delete department with existing employees.');
        }

        $this->model->delete($id);
        return redirect()->to(base_url('admin/departments'))->with('success', 'Department deleted successfully.');
    }
}
