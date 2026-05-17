<?php

namespace App\Controllers\Admin;

use App\Models\AttendanceModel;
use App\Models\EmployeeModel;
use App\Models\DepartmentModel;

class AttendanceController extends BaseAdminController
{
    protected AttendanceModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new AttendanceModel();
    }

    public function index(): string
    {
        $date        = $this->request->getGet('date') ?? date('Y-m-d');
        $deptId      = $this->request->getGet('dept_id');
        $deptModel   = new DepartmentModel();
        $empModel    = new EmployeeModel();

        $query = $this->model->select('attendance.*, CONCAT(e.first_name," ",e.last_name) as full_name, e.employee_id as emp_code, e.photo, d.name as department_name')
            ->join('employees e', 'e.id = attendance.employee_id')
            ->join('departments d', 'd.id = e.department_id', 'left')
            ->where('attendance.date', $date);

        if ($deptId) {
            $query->where('e.department_id', $deptId);
        }

        $attendance  = $query->findAll();
        $departments = $deptModel->getActive();

        return view('attendance/index', [
            'title'       => 'Attendance',
            'attendance'  => $attendance,
            'departments' => $departments,
            'date'        => $date,
            'deptId'      => $deptId,
            'stats'       => $this->model->getTodayStats(),
        ]);
    }

    public function mark(): string
    {
        $empModel  = new EmployeeModel();
        $deptModel = new DepartmentModel();
        $date      = $this->request->getGet('date') ?? date('Y-m-d');
        $deptId    = $this->request->getGet('dept_id');

        $query = $empModel->select('employees.*, d.name as department_name, a.check_in, a.check_out, a.status as att_status')
            ->join('departments d', 'd.id = employees.department_id', 'left')
            ->join('attendance a', "a.employee_id = employees.id AND a.date = '{$date}'", 'left')
            ->where('employees.status', 'active');

        if ($deptId) {
            $query->where('employees.department_id', $deptId);
        }

        return view('attendance/mark', [
            'title'       => 'Mark Attendance',
            'employees'   => $query->findAll(),
            'departments' => $deptModel->getActive(),
            'date'        => $date,
            'deptId'      => $deptId,
        ]);
    }

    public function store()
    {
        $employeeId = $this->request->getPost('employee_id');
        $date       = $this->request->getPost('date') ?? date('Y-m-d');
        $checkIn    = $this->request->getPost('check_in');
        $checkOut   = $this->request->getPost('check_out');
        $status     = $this->request->getPost('status') ?? 'present';
        $notes      = $this->request->getPost('notes');

        $totalHours = 0;
        if ($checkIn && $checkOut) {
            $in  = strtotime($checkIn);
            $out = strtotime($checkOut);
            $totalHours = round(($out - $in) / 3600, 2);
        }

        if ($this->model->alreadyMarked($employeeId, $date)) {
            $this->model->where('employee_id', $employeeId)->where('date', $date)
                ->set(['check_in' => $checkIn, 'check_out' => $checkOut, 'total_hours' => $totalHours, 'status' => $status, 'notes' => $notes])
                ->update();
        } else {
            $this->model->insert([
                'employee_id' => $employeeId,
                'date'        => $date,
                'check_in'    => $checkIn,
                'check_out'   => $checkOut,
                'total_hours' => $totalHours,
                'status'      => $status,
                'notes'       => $notes,
            ]);
        }

        return redirect()->back()->with('success', 'Attendance marked successfully.');
    }

    public function bulkStore()
    {
        $date      = $this->request->getPost('date') ?? date('Y-m-d');
        $employees = $this->request->getPost('employees');

        if (!$employees) {
            return redirect()->back()->with('error', 'No attendance data submitted.');
        }

        foreach ($employees as $empId => $data) {
            $checkIn    = $data['check_in'] ?? null;
            $checkOut   = $data['check_out'] ?? null;
            $status     = $data['status'] ?? 'present';
            $totalHours = 0;

            if ($checkIn && $checkOut) {
                $totalHours = round((strtotime($checkOut) - strtotime($checkIn)) / 3600, 2);
            }

            if ($this->model->alreadyMarked($empId, $date)) {
                $this->model->where('employee_id', $empId)->where('date', $date)
                    ->set(['check_in' => $checkIn, 'check_out' => $checkOut, 'total_hours' => $totalHours, 'status' => $status])
                    ->update();
            } else {
                $this->model->insert([
                    'employee_id' => $empId,
                    'date'        => $date,
                    'check_in'    => $checkIn,
                    'check_out'   => $checkOut,
                    'total_hours' => $totalHours,
                    'status'      => $status,
                ]);
            }
        }

        return redirect()->to(base_url('admin/attendance'))->with('success', 'Bulk attendance saved successfully.');
    }

    public function edit(int $id): string
    {
        $record = $this->model->find($id);
        if (!$record) {
            return redirect()->to(base_url('admin/attendance'))->with('error', 'Record not found.');
        }
        $empModel = new EmployeeModel();
        return view('attendance/edit', [
            'title'      => 'Edit Attendance',
            'record'     => $record,
            'employee'   => $empModel->find($record['employee_id']),
        ]);
    }

    public function update(int $id)
    {
        $checkIn  = $this->request->getPost('check_in');
        $checkOut = $this->request->getPost('check_out');
        $totalHours = 0;
        if ($checkIn && $checkOut) {
            $totalHours = round((strtotime($checkOut) - strtotime($checkIn)) / 3600, 2);
        }

        $this->model->update($id, [
            'check_in'    => $checkIn,
            'check_out'   => $checkOut,
            'total_hours' => $totalHours,
            'status'      => $this->request->getPost('status'),
            'notes'       => $this->request->getPost('notes'),
        ]);

        return redirect()->to(base_url('admin/attendance'))->with('success', 'Attendance updated successfully.');
    }

    public function report(): string
    {
        $month   = $this->request->getGet('month') ?? date('n');
        $year    = $this->request->getGet('year') ?? date('Y');
        $deptId  = $this->request->getGet('dept_id');
        $deptModel = new DepartmentModel();

        return view('attendance/report', [
            'title'       => 'Attendance Report',
            'report'      => $this->model->getMonthlyReport($month, $year, $deptId),
            'departments' => $deptModel->getActive(),
            'month'       => $month,
            'year'        => $year,
            'deptId'      => $deptId,
        ]);
    }

    public function employeeAttendance(int $empId): string
    {
        $month    = $this->request->getGet('month') ?? date('n');
        $year     = $this->request->getGet('year') ?? date('Y');
        $empModel = new EmployeeModel();

        return view('attendance/employee', [
            'title'      => 'My Attendance',
            'attendance' => $this->model->getEmployeeAttendance($empId, $month, $year),
            'employee'   => $empModel->find($empId),
            'month'      => $month,
            'year'       => $year,
        ]);
    }
}
