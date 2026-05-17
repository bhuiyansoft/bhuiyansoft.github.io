<?php

namespace App\Controllers\Admin;

use App\Models\EmployeeModel;
use App\Models\AttendanceModel;
use App\Models\PayrollModel;
use App\Models\LeaveModel;
use App\Models\DepartmentModel;

class ReportsController extends BaseAdminController
{
    public function index(): string
    {
        return view('reports/index', ['title' => 'Reports']);
    }

    public function employees(): string
    {
        $deptId = $this->request->getGet('dept_id');
        $status = $this->request->getGet('status');
        $empModel  = new EmployeeModel();
        $deptModel = new DepartmentModel();

        $query = $empModel->select('employees.*, d.name as department_name, des.title as designation_title')
            ->join('departments d', 'd.id = employees.department_id', 'left')
            ->join('designations des', 'des.id = employees.designation_id', 'left');

        if ($deptId) $query->where('employees.department_id', $deptId);
        if ($status) $query->where('employees.status', $status);

        return view('reports/employees', [
            'title'       => 'Employee Report',
            'employees'   => $query->findAll(),
            'departments' => $deptModel->getActive(),
            'deptId'      => $deptId,
            'status'      => $status,
        ]);
    }

    public function attendance(): string
    {
        $month   = $this->request->getGet('month') ?? date('n');
        $year    = $this->request->getGet('year') ?? date('Y');
        $deptId  = $this->request->getGet('dept_id');
        $attModel  = new AttendanceModel();
        $deptModel = new DepartmentModel();

        return view('reports/attendance', [
            'title'       => 'Attendance Report',
            'report'      => $attModel->getMonthlyReport($month, $year, $deptId),
            'departments' => $deptModel->getActive(),
            'month'       => $month,
            'year'        => $year,
            'deptId'      => $deptId,
        ]);
    }

    public function payroll(): string
    {
        $month   = $this->request->getGet('month') ?? date('n');
        $year    = $this->request->getGet('year') ?? date('Y');
        $deptId  = $this->request->getGet('dept_id');
        $payrollModel = new PayrollModel();
        $deptModel    = new DepartmentModel();

        $query = $payrollModel->select('payroll.*, CONCAT(e.first_name," ",e.last_name) as full_name, e.employee_id as emp_code, d.name as department_name')
            ->join('employees e', 'e.id = payroll.employee_id')
            ->join('departments d', 'd.id = e.department_id', 'left')
            ->where('payroll.month', $month)
            ->where('payroll.year', $year);

        if ($deptId) $query->where('e.department_id', $deptId);

        return view('reports/payroll', [
            'title'       => 'Payroll Report',
            'payroll'     => $query->findAll(),
            'summary'     => $payrollModel->getSummary($month, $year),
            'departments' => $deptModel->getActive(),
            'month'       => $month,
            'year'        => $year,
            'deptId'      => $deptId,
        ]);
    }

    public function leave(): string
    {
        $year      = $this->request->getGet('year') ?? date('Y');
        $deptId    = $this->request->getGet('dept_id');
        $leaveModel = new LeaveModel();
        $deptModel  = new DepartmentModel();

        $query = $leaveModel->select('leave_requests.*, CONCAT(e.first_name," ",e.last_name) as employee_name, d.name as department_name, lt.name as leave_type_name')
            ->join('employees e', 'e.id = leave_requests.employee_id')
            ->join('departments d', 'd.id = e.department_id', 'left')
            ->join('leave_types lt', 'lt.id = leave_requests.leave_type_id')
            ->where('YEAR(leave_requests.created_at)', $year)
            ->where('leave_requests.status', 'approved');

        if ($deptId) $query->where('e.department_id', $deptId);

        return view('reports/leave', [
            'title'       => 'Leave Report',
            'leaves'      => $query->findAll(),
            'departments' => $deptModel->getActive(),
            'year'        => $year,
            'deptId'      => $deptId,
        ]);
    }

    public function headcount(): string
    {
        $db        = \Config\Database::connect();
        $deptModel = new DepartmentModel();

        $deptCount    = $db->query("SELECT d.name, COUNT(e.id) as count FROM departments d LEFT JOIN employees e ON e.department_id = d.id AND e.status='active' AND e.deleted_at IS NULL GROUP BY d.id")->getResultArray();
        $typeCount    = $db->query("SELECT employment_type, COUNT(*) as count FROM employees WHERE status='active' AND deleted_at IS NULL GROUP BY employment_type")->getResultArray();
        $genderCount  = $db->query("SELECT gender, COUNT(*) as count FROM employees WHERE status='active' AND deleted_at IS NULL GROUP BY gender")->getResultArray();
        $monthlyJoin  = $db->query("SELECT MONTH(join_date) as month, YEAR(join_date) as year, COUNT(*) as count FROM employees WHERE YEAR(join_date) = ? GROUP BY MONTH(join_date)", [date('Y')])->getResultArray();

        return view('reports/headcount', [
            'title'       => 'Headcount Report',
            'deptCount'   => $deptCount,
            'typeCount'   => $typeCount,
            'genderCount' => $genderCount,
            'monthlyJoin' => $monthlyJoin,
        ]);
    }
}
