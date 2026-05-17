<?php

namespace App\Controllers\Admin;

use App\Models\DesignationModel;
use App\Models\DepartmentModel;

class DesignationController extends BaseAdminController
{
    protected DesignationModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new DesignationModel();
    }

    public function index(): string
    {
        return view('designations/index', [
            'title'        => 'Designations',
            'designations' => $this->model->getWithDepartment(),
        ]);
    }

    public function create(): string
    {
        $deptModel = new DepartmentModel();
        return view('designations/create', [
            'title'       => 'Add Designation',
            'departments' => $deptModel->getActive(),
        ]);
    }

    public function store()
    {
        $rules = [
            'department_id' => 'required|numeric',
            'title'         => 'required|min_length[2]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->insert([
            'department_id' => $this->request->getPost('department_id'),
            'title'         => $this->request->getPost('title'),
            'level'         => $this->request->getPost('level'),
            'min_salary'    => $this->request->getPost('min_salary') ?: 0,
            'max_salary'    => $this->request->getPost('max_salary') ?: 0,
            'description'   => $this->request->getPost('description'),
            'status'        => 1,
        ]);

        return redirect()->to(base_url('admin/designations'))->with('success', 'Designation created successfully.');
    }

    public function edit(int $id): string
    {
        $designation = $this->model->find($id);
        if (!$designation) {
            return redirect()->to(base_url('admin/designations'))->with('error', 'Designation not found.');
        }
        $deptModel = new DepartmentModel();
        return view('designations/edit', [
            'title'        => 'Edit Designation',
            'designation'  => $designation,
            'departments'  => $deptModel->getActive(),
        ]);
    }

    public function update(int $id)
    {
        $rules = [
            'department_id' => 'required|numeric',
            'title'         => 'required|min_length[2]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->update($id, [
            'department_id' => $this->request->getPost('department_id'),
            'title'         => $this->request->getPost('title'),
            'level'         => $this->request->getPost('level'),
            'min_salary'    => $this->request->getPost('min_salary') ?: 0,
            'max_salary'    => $this->request->getPost('max_salary') ?: 0,
            'description'   => $this->request->getPost('description'),
            'status'        => $this->request->getPost('status') ?? 1,
        ]);

        return redirect()->to(base_url('admin/designations'))->with('success', 'Designation updated successfully.');
    }

    public function delete(int $id)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('admin/designations'))->with('success', 'Designation deleted.');
    }

    public function byDepartment(int $deptId)
    {
        $designations = $this->model->getByDepartment($deptId);
        return $this->response->setJSON($designations);
    }
}
