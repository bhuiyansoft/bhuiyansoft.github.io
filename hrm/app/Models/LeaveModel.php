<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaveModel extends Model
{
    protected $table      = 'leave_requests';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employee_id', 'leave_type_id', 'from_date', 'to_date',
        'total_days', 'reason', 'status', 'approved_by',
        'approval_date', 'rejection_reason'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getWithDetails(?int $id = null): mixed
    {
        $builder = $this->select('leave_requests.*,
            CONCAT(e.first_name," ",e.last_name) as employee_name,
            e.employee_id as emp_code, e.photo,
            d.name as department_name,
            lt.name as leave_type_name, lt.color as leave_color,
            CONCAT(a.first_name," ",a.last_name) as approved_by_name')
            ->join('employees e', 'e.id = leave_requests.employee_id')
            ->join('departments d', 'd.id = e.department_id', 'left')
            ->join('leave_types lt', 'lt.id = leave_requests.leave_type_id')
            ->join('employees a', 'a.id = leave_requests.approved_by', 'left')
            ->orderBy('leave_requests.created_at', 'DESC');

        if ($id) {
            return $builder->find($id);
        }
        return $builder->findAll();
    }

    public function getPending(): array
    {
        return $this->getWithDetails()->where('leave_requests.status', 'pending')->findAll();
    }

    public function getEmployeeLeaves(int $employeeId): array
    {
        return $this->select('leave_requests.*, lt.name as leave_type_name, lt.color as leave_color')
            ->join('leave_types lt', 'lt.id = leave_requests.leave_type_id')
            ->where('employee_id', $employeeId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function getStats(): array
    {
        $pending  = $this->where('status', 'pending')->countAllResults();
        $approved = $this->where('status', 'approved')->where('YEAR(created_at)', date('Y'))->countAllResults();
        $rejected = $this->where('status', 'rejected')->where('YEAR(created_at)', date('Y'))->countAllResults();
        return compact('pending', 'approved', 'rejected');
    }
}

class LeaveTypeModel extends Model
{
    protected $table      = 'leave_types';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'days_allowed', 'carry_forward', 'is_paid', 'requires_approval', 'color', 'status'];

    public function getActive(): array
    {
        return $this->where('status', 1)->findAll();
    }
}

class LeaveAllocationModel extends Model
{
    protected $table      = 'leave_allocations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['employee_id', 'leave_type_id', 'year', 'allocated_days', 'used_days', 'remaining_days'];

    public function getEmployeeBalance(int $employeeId, int $year): array
    {
        return $this->select('leave_allocations.*, lt.name as leave_type_name, lt.color')
            ->join('leave_types lt', 'lt.id = leave_allocations.leave_type_id')
            ->where('employee_id', $employeeId)
            ->where('year', $year)
            ->findAll();
    }

    public function getAllocationsWithDetails(): array
    {
        return $this->select('leave_allocations.*, CONCAT(e.first_name," ",e.last_name) as employee_name, e.employee_id as emp_code, lt.name as leave_type_name')
            ->join('employees e', 'e.id = leave_allocations.employee_id')
            ->join('leave_types lt', 'lt.id = leave_allocations.leave_type_id')
            ->orderBy('leave_allocations.year', 'DESC')
            ->findAll();
    }
}
