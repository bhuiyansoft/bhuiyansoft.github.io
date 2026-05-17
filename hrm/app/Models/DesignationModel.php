<?php

namespace App\Models;

use CodeIgniter\Model;

class DesignationModel extends Model
{
    protected $table      = 'designations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['department_id', 'title', 'level', 'min_salary', 'max_salary', 'description', 'status'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getWithDepartment(?int $deptId = null): mixed
    {
        $builder = $this->select('designations.*, d.name as department_name')
            ->join('departments d', 'd.id = designations.department_id', 'left');
        if ($deptId) {
            return $builder->where('designations.department_id', $deptId)->findAll();
        }
        return $builder->findAll();
    }

    public function getByDepartment(int $deptId): array
    {
        return $this->where('department_id', $deptId)->where('status', 1)->findAll();
    }
}
