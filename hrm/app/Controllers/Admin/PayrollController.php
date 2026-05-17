<?php

namespace App\Controllers\Admin;

use App\Models\PayrollModel;
use App\Models\EmployeeModel;
use App\Models\AttendanceModel;
use App\Models\DepartmentModel;

class PayrollController extends BaseAdminController
{
    protected PayrollModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new PayrollModel();
    }

    public function index(): string
    {
        $month = $this->request->getGet('month') ?? date('n');
        $year  = $this->request->getGet('year') ?? date('Y');

        return view('payroll/index', [
            'title'   => 'Payroll',
            'payroll' => $this->model->getMonthlyPayroll($month, $year),
            'summary' => $this->model->getSummary($month, $year),
            'month'   => $month,
            'year'    => $year,
        ]);
    }

    public function generate(): string
    {
        $empModel  = new EmployeeModel();
        $deptModel = new DepartmentModel();
        $month     = $this->request->getGet('month') ?? date('n');
        $year      = $this->request->getGet('year') ?? date('Y');

        $employees = $empModel->select('employees.*, d.name as department_name')
            ->join('departments d', 'd.id = employees.department_id', 'left')
            ->where('employees.status', 'active')
            ->findAll();

        // Mark which ones are already processed
        foreach ($employees as &$emp) {
            $emp['already_generated'] = $this->model->alreadyGenerated($emp['id'], $month, $year);
        }

        return view('payroll/generate', [
            'title'       => 'Generate Payroll',
            'employees'   => $employees,
            'departments' => $deptModel->getActive(),
            'month'       => $month,
            'year'        => $year,
        ]);
    }

    public function process()
    {
        if (!$this->hasPermission(['super_admin', 'admin', 'hr_manager'])) {
            return $this->denyAccess();
        }

        $month   = $this->request->getPost('month');
        $year    = $this->request->getPost('year');
        $empIds  = $this->request->getPost('employee_ids');

        if (!$empIds) {
            return redirect()->back()->with('error', 'Select at least one employee.');
        }

        $empModel  = new EmployeeModel();
        $attModel  = new AttendanceModel();
        $processed = 0;

        foreach ($empIds as $empId) {
            if ($this->model->alreadyGenerated($empId, $month, $year)) continue;

            $employee = $empModel->find($empId);
            if (!$employee) continue;

            $basic     = $employee['basic_salary'];
            $houseRent = $basic * 0.40;
            $medical   = 5000;
            $transport = 3000;
            $gross     = $basic + $houseRent + $medical + $transport;

            $tax          = $gross * 0.10;
            $providentFund = $basic * 0.10;
            $totalDed     = $tax + $providentFund;
            $net          = $gross - $totalDed;

            // Attendance data
            $attData = $attModel->where('employee_id', $empId)
                ->where('MONTH(date)', $month)->where('YEAR(date)', $year)->findAll();

            $presentDays  = count(array_filter($attData, fn($a) => in_array($a['status'], ['present', 'late', 'half_day'])));
            $absentDays   = count(array_filter($attData, fn($a) => $a['status'] === 'absent'));
            $overtime     = array_sum(array_column($attData, 'overtime_hours'));

            $this->model->insert([
                'employee_id'         => $empId,
                'month'               => $month,
                'year'                => $year,
                'basic_salary'        => $basic,
                'house_rent'          => $houseRent,
                'medical_allowance'   => $medical,
                'transport_allowance' => $transport,
                'other_earnings'      => 0,
                'overtime_pay'        => $overtime * ($basic / (26 * 8)),
                'gross_salary'        => $gross,
                'tax_deduction'       => $tax,
                'provident_fund'      => $providentFund,
                'other_deductions'    => 0,
                'total_deductions'    => $totalDed,
                'net_salary'          => $net,
                'attendance_days'     => $presentDays,
                'absent_days'         => $absentDays,
                'overtime_hours'      => $overtime,
                'status'              => 'processed',
                'generated_by'        => session()->get('user_id'),
            ]);
            $processed++;
        }

        return redirect()->to(base_url("admin/payroll?month={$month}&year={$year}"))->with('success', "{$processed} payroll(s) generated successfully.");
    }

    public function show(int $id): string
    {
        $payroll = $this->model->getWithEmployee($id);
        if (!$payroll) {
            return redirect()->to(base_url('admin/payroll'))->with('error', 'Payroll not found.');
        }
        return view('payroll/show', ['title' => 'Payroll Details', 'payroll' => $payroll]);
    }

    public function payslip(int $id): string
    {
        $payroll = $this->model->getWithEmployee($id);
        if (!$payroll) {
            return redirect()->to(base_url('admin/payroll'))->with('error', 'Payroll not found.');
        }
        return view('payroll/payslip', ['title' => 'Pay Slip', 'payroll' => $payroll]);
    }

    public function markPaid(int $id)
    {
        $this->model->update($id, [
            'status'         => 'paid',
            'payment_date'   => date('Y-m-d'),
            'payment_method' => $this->request->getPost('payment_method') ?? 'bank_transfer',
        ]);
        return redirect()->back()->with('success', 'Payroll marked as paid.');
    }

    public function delete(int $id)
    {
        if (!$this->hasPermission(['super_admin', 'admin'])) {
            return $this->denyAccess();
        }
        $this->model->delete($id);
        return redirect()->to(base_url('admin/payroll'))->with('success', 'Payroll record deleted.');
    }

    public function report(): string
    {
        $year      = $this->request->getGet('year') ?? date('Y');
        $db        = \Config\Database::connect();
        $monthly   = $db->query("SELECT month, SUM(gross_salary) as gross, SUM(net_salary) as net, SUM(total_deductions) as deductions, COUNT(*) as employees FROM payroll WHERE year = ? GROUP BY month ORDER BY month", [$year])->getResultArray();

        return view('payroll/report', [
            'title'   => 'Payroll Report',
            'monthly' => $monthly,
            'year'    => $year,
        ]);
    }
}
