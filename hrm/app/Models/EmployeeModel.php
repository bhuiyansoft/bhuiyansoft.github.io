<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table      = 'employees';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'employee_id', 'first_name', 'last_name', 'email', 'phone',
        'gender', 'date_of_birth', 'national_id', 'photo',
        'department_id', 'designation_id', 'reporting_to',
        'employment_type', 'join_date', 'termination_date',
        'basic_salary', 'bank_name', 'bank_account',
        'address', 'city', 'state', 'country',
        'emergency_contact_name', 'emergency_contact_phone', 'status'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getWithDetails(?int $id = null): mixed
    {
        $builder = $this->select('employees.*,
            d.name as department_name,
            des.title as designation_title,
            CONCAT(m.first_name," ",m.last_name) as manager_name')
            ->join('departments d', 'd.id = employees.department_id', 'left')
            ->join('designations des', 'des.id = employees.designation_id', 'left')
            ->join('employees m', 'm.id = employees.reporting_to', 'left');

        if ($id) {
            return $builder->find($id);
        }
        return $builder->findAll();
    }

    public function getActiveEmployees(): array
    {
        return $this->where('status', 'active')->findAll();
    }

    public function generateEmployeeId(): string
    {
        $last = $this->select('employee_id')->orderBy('id', 'DESC')->first();
        if (!$last) return 'EMP-001';
        $num = intval(substr($last['employee_id'], 4)) + 1;
        return 'EMP-' . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    public function getStats(): array
    {
        $total     = $this->countAll();
        $active    = $this->where('status', 'active')->countAllResults();
        $onLeave   = $this->where('status', 'on_leave')->countAllResults();
        $newMonth  = $this->where('MONTH(join_date)', date('m'))->where('YEAR(join_date)', date('Y'))->countAllResults();
        return compact('total', 'active', 'onLeave', 'newMonth');
    }

    public function searchEmployees(string $term): array
    {
        return $this->select('employees.id, employee_id, first_name, last_name, email, photo, d.name as department_name, des.title as designation_title')
            ->join('departments d', 'd.id = employees.department_id', 'left')
            ->join('designations des', 'des.id = employees.designation_id', 'left')
            ->groupStart()
                ->like('first_name', $term)
                ->orLike('last_name', $term)
                ->orLike('email', $term)
                ->orLike('employee_id', $term)
            ->groupEnd()
            ->where('employees.status', 'active')
            ->findAll();
    }
}
