<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table      = 'attendance';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employee_id', 'date', 'check_in', 'check_out',
        'total_hours', 'overtime_hours', 'status', 'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getTodayAttendance(): array
    {
        return $this->select('attendance.*, CONCAT(e.first_name," ",e.last_name) as full_name, e.employee_id as emp_code, e.photo, d.name as department_name')
            ->join('employees e', 'e.id = attendance.employee_id')
            ->join('departments d', 'd.id = e.department_id', 'left')
            ->where('attendance.date', date('Y-m-d'))
            ->findAll();
    }

    public function getEmployeeAttendance(int $employeeId, string $month, string $year): array
    {
        return $this->where('employee_id', $employeeId)
            ->where('MONTH(date)', $month)
            ->where('YEAR(date)', $year)
            ->orderBy('date', 'ASC')
            ->findAll();
    }

    public function getMonthlyReport(string $month, string $year, ?int $deptId = null): array
    {
        $builder = $this->select('
            e.id, e.employee_id as emp_code, CONCAT(e.first_name," ",e.last_name) as full_name,
            d.name as department_name,
            SUM(CASE WHEN a.status="present" THEN 1 ELSE 0 END) as present_days,
            SUM(CASE WHEN a.status="absent" THEN 1 ELSE 0 END) as absent_days,
            SUM(CASE WHEN a.status="late" THEN 1 ELSE 0 END) as late_days,
            SUM(CASE WHEN a.status="half_day" THEN 1 ELSE 0 END) as half_days,
            SUM(a.total_hours) as total_hours,
            SUM(a.overtime_hours) as overtime_hours')
            ->join('employees e', 'e.id = attendance.employee_id')
            ->join('departments d', 'd.id = e.department_id', 'left')
            ->where('MONTH(attendance.date)', $month)
            ->where('YEAR(attendance.date)', $year)
            ->where('e.status', 'active')
            ->groupBy('e.id');

        if ($deptId) {
            $builder->where('e.department_id', $deptId);
        }
        return $builder->findAll();
    }

    public function getTodayStats(): array
    {
        $today = date('Y-m-d');
        $present = $this->where('date', $today)->where('status', 'present')->countAllResults();
        $late    = $this->where('date', $today)->where('status', 'late')->countAllResults();
        $absent  = $this->where('date', $today)->where('status', 'absent')->countAllResults();
        return compact('present', 'late', 'absent');
    }

    public function alreadyMarked(int $employeeId, string $date): bool
    {
        return $this->where('employee_id', $employeeId)->where('date', $date)->countAllResults() > 0;
    }
}
