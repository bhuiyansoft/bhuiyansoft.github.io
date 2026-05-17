<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table      = 'departments';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['name', 'code', 'head_id', 'description', 'status'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getWithHead(): array
    {
        return $this->select('departments.*, CONCAT(e.first_name," ",e.last_name) as head_name, COUNT(emp.id) as employee_count')
            ->join('employees e', 'e.id = departments.head_id', 'left')
            ->join('employees emp', 'emp.department_id = departments.id AND emp.deleted_at IS NULL', 'left')
            ->groupBy('departments.id')
            ->findAll();
    }

    public function getActive(): array
    {
        return $this->where('status', 1)->findAll();
    }
}
