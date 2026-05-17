<?php

namespace App\Controllers\Admin;

use App\Models\EmployeeModel;
use App\Models\DepartmentModel;
use App\Models\DesignationModel;
use App\Models\UserModel;

class EmployeeController extends BaseAdminController
{
    protected EmployeeModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new EmployeeModel();
    }

    public function index(): string
    {
        $employees = $this->model->getWithDetails();
        return view('employees/index', [
            'title'     => 'Employees',
            'employees' => $employees,
        ]);
    }

    public function create(): string
    {
        $deptModel = new DepartmentModel();
        $desModel  = new DesignationModel();
        return view('employees/create', [
            'title'        => 'Add Employee',
            'departments'  => $deptModel->getActive(),
            'designations' => $desModel->findAll(),
            'employees'    => $this->model->getActiveEmployees(),
        ]);
    }

    public function store()
    {
        $rules = [
            'first_name'    => 'required|min_length[2]|max_length[60]',
            'last_name'     => 'required|min_length[2]|max_length[60]',
            'email'         => 'required|valid_email|is_unique[employees.email]',
            'department_id' => 'required|numeric',
            'join_date'     => 'required|valid_date',
            'basic_salary'  => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $photo = $this->uploadPhoto('photo', 'employees');

        $data = [
            'employee_id'     => $this->model->generateEmployeeId(),
            'first_name'      => $this->request->getPost('first_name'),
            'last_name'       => $this->request->getPost('last_name'),
            'email'           => $this->request->getPost('email'),
            'phone'           => $this->request->getPost('phone'),
            'gender'          => $this->request->getPost('gender'),
            'date_of_birth'   => $this->request->getPost('date_of_birth') ?: null,
            'national_id'     => $this->request->getPost('national_id'),
            'photo'           => $photo,
            'department_id'   => $this->request->getPost('department_id'),
            'designation_id'  => $this->request->getPost('designation_id') ?: null,
            'reporting_to'    => $this->request->getPost('reporting_to') ?: null,
            'employment_type' => $this->request->getPost('employment_type') ?? 'full_time',
            'join_date'       => $this->request->getPost('join_date'),
            'basic_salary'    => $this->request->getPost('basic_salary'),
            'bank_name'       => $this->request->getPost('bank_name'),
            'bank_account'    => $this->request->getPost('bank_account'),
            'address'         => $this->request->getPost('address'),
            'city'            => $this->request->getPost('city'),
            'state'           => $this->request->getPost('state'),
            'country'         => $this->request->getPost('country') ?: 'Bangladesh',
            'emergency_contact_name'  => $this->request->getPost('emergency_contact_name'),
            'emergency_contact_phone' => $this->request->getPost('emergency_contact_phone'),
            'status'          => 'active',
        ];

        $employeeId = $this->model->insert($data, true);

        // Create user account
        $createUser = $this->request->getPost('create_user');
        if ($createUser && $employeeId) {
            $userModel = new UserModel();
            $userModel->insert([
                'employee_id' => $employeeId,
                'username'    => strtolower($data['first_name'] . '.' . $data['last_name']),
                'email'       => $data['email'],
                'password'    => password_hash('Password@123', PASSWORD_BCRYPT),
                'role'        => $this->request->getPost('user_role') ?? 'employee',
                'is_active'   => 1,
            ]);
        }

        return redirect()->to(base_url('admin/employees'))->with('success', 'Employee added successfully.');
    }

    public function show(int $id): string
    {
        $employee = $this->model->getWithDetails($id);
        if (!$employee) {
            return redirect()->to(base_url('admin/employees'))->with('error', 'Employee not found.');
        }

        $db = \Config\Database::connect();
        $recentAttendance = $db->query("SELECT * FROM attendance WHERE employee_id = ? ORDER BY date DESC LIMIT 7", [$id])->getResultArray();
        $leaveBalance = $db->query("SELECT la.*, lt.name FROM leave_allocations la JOIN leave_types lt ON lt.id = la.leave_type_id WHERE la.employee_id = ? AND la.year = ?", [$id, date('Y')])->getResultArray();
        $documents = $db->query("SELECT * FROM employee_documents WHERE employee_id = ? ORDER BY created_at DESC", [$id])->getResultArray();

        return view('employees/show', [
            'title'            => 'Employee Profile',
            'employee'         => $employee,
            'recentAttendance' => $recentAttendance,
            'leaveBalance'     => $leaveBalance,
            'documents'        => $documents,
        ]);
    }

    public function edit(int $id): string
    {
        $employee  = $this->model->find($id);
        if (!$employee) {
            return redirect()->to(base_url('admin/employees'))->with('error', 'Employee not found.');
        }

        $deptModel = new DepartmentModel();
        $desModel  = new DesignationModel();

        return view('employees/edit', [
            'title'        => 'Edit Employee',
            'employee'     => $employee,
            'departments'  => $deptModel->getActive(),
            'designations' => $desModel->findAll(),
            'employees'    => $this->model->getActiveEmployees(),
        ]);
    }

    public function update(int $id)
    {
        $employee = $this->model->find($id);
        if (!$employee) {
            return redirect()->to(base_url('admin/employees'))->with('error', 'Employee not found.');
        }

        $rules = [
            'first_name'    => 'required|min_length[2]|max_length[60]',
            'last_name'     => 'required|min_length[2]|max_length[60]',
            'email'         => "required|valid_email|is_unique[employees.email,id,{$id}]",
            'department_id' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $photo = $this->uploadPhoto('photo', 'employees') ?? $employee['photo'];

        $data = [
            'first_name'      => $this->request->getPost('first_name'),
            'last_name'       => $this->request->getPost('last_name'),
            'email'           => $this->request->getPost('email'),
            'phone'           => $this->request->getPost('phone'),
            'gender'          => $this->request->getPost('gender'),
            'date_of_birth'   => $this->request->getPost('date_of_birth') ?: null,
            'national_id'     => $this->request->getPost('national_id'),
            'photo'           => $photo,
            'department_id'   => $this->request->getPost('department_id'),
            'designation_id'  => $this->request->getPost('designation_id') ?: null,
            'reporting_to'    => $this->request->getPost('reporting_to') ?: null,
            'employment_type' => $this->request->getPost('employment_type'),
            'join_date'       => $this->request->getPost('join_date'),
            'basic_salary'    => $this->request->getPost('basic_salary'),
            'bank_name'       => $this->request->getPost('bank_name'),
            'bank_account'    => $this->request->getPost('bank_account'),
            'address'         => $this->request->getPost('address'),
            'city'            => $this->request->getPost('city'),
            'state'           => $this->request->getPost('state'),
            'country'         => $this->request->getPost('country'),
            'emergency_contact_name'  => $this->request->getPost('emergency_contact_name'),
            'emergency_contact_phone' => $this->request->getPost('emergency_contact_phone'),
            'status'          => $this->request->getPost('status'),
        ];

        $this->model->update($id, $data);
        return redirect()->to(base_url('admin/employees'))->with('success', 'Employee updated successfully.');
    }

    public function delete(int $id)
    {
        if (!$this->hasPermission(['super_admin', 'admin', 'hr_manager'])) {
            return $this->denyAccess();
        }
        $this->model->delete($id);
        return redirect()->to(base_url('admin/employees'))->with('success', 'Employee removed successfully.');
    }

    public function export()
    {
        $employees = $this->model->getWithDetails();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="employees_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Employee ID', 'Name', 'Email', 'Phone', 'Department', 'Designation', 'Join Date', 'Status', 'Basic Salary']);

        foreach ($employees as $emp) {
            fputcsv($output, [
                $emp['id'],
                $emp['employee_id'],
                $emp['first_name'] . ' ' . $emp['last_name'],
                $emp['email'],
                $emp['phone'],
                $emp['department_name'] ?? '',
                $emp['designation_title'] ?? '',
                $emp['join_date'],
                $emp['status'],
                $emp['basic_salary'],
            ]);
        }
        fclose($output);
        exit;
    }
}
