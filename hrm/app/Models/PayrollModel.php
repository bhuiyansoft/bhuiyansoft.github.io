<?php

namespace App\Models;

use CodeIgniter\Model;

class PayrollModel extends Model
{
    protected $table      = 'payroll';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employee_id', 'month', 'year', 'basic_salary',
        'house_rent', 'medical_allowance', 'transport_allowance',
        'other_earnings', 'overtime_pay', 'gross_salary',
        'tax_deduction', 'provident_fund', 'other_deductions',
        'total_deductions', 'net_salary',
        'attendance_days', 'absent_days', 'leave_days', 'overtime_hours',
        'status', 'payment_date', 'payment_method', 'notes', 'generated_by'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getWithEmployee(?int $id = null): mixed
    {
        $builder = $this->select('payroll.*, CONCAT(e.first_name," ",e.last_name) as full_name, e.employee_id as emp_code, e.photo, d.name as department_name, des.title as designation_title')
            ->join('employees e', 'e.id = payroll.employee_id')
            ->join('departments d', 'd.id = e.department_id', 'left')
            ->join('designations des', 'des.id = e.designation_id', 'left')
            ->orderBy('payroll.year', 'DESC')
            ->orderBy('payroll.month', 'DESC');

        if ($id) {
            return $builder->find($id);
        }
        return $builder->findAll();
    }

    public function getMonthlyPayroll(int $month, int $year): array
    {
        return $this->select('payroll.*, CONCAT(e.first_name," ",e.last_name) as full_name, e.employee_id as emp_code, d.name as department_name')
            ->join('employees e', 'e.id = payroll.employee_id')
            ->join('departments d', 'd.id = e.department_id', 'left')
            ->where('payroll.month', $month)
            ->where('payroll.year', $year)
            ->findAll();
    }

    public function getSummary(int $month, int $year): array
    {
        $result = $this->selectSum('gross_salary')->selectSum('net_salary')->selectSum('total_deductions')
            ->selectCount('id', 'total_records')
            ->where('month', $month)
            ->where('year', $year)
            ->first();
        return $result ?? [];
    }

    public function alreadyGenerated(int $employeeId, int $month, int $year): bool
    {
        return $this->where('employee_id', $employeeId)->where('month', $month)->where('year', $year)->countAllResults() > 0;
    }
}
