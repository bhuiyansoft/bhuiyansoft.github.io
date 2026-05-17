<?php

namespace App\Models;

use CodeIgniter\Model;

class PerformanceModel extends Model
{
    protected $table      = 'performance_reviews';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employee_id', 'reviewer_id', 'review_period', 'review_date',
        'punctuality', 'quality_of_work', 'teamwork', 'communication', 'initiative',
        'overall_score', 'rating', 'strengths', 'areas_for_improvement',
        'goals_next_period', 'comments', 'status'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getWithDetails(?int $id = null): mixed
    {
        $builder = $this->select('performance_reviews.*,
            CONCAT(e.first_name," ",e.last_name) as employee_name, e.employee_id as emp_code, e.photo,
            d.name as department_name, des.title as designation_title,
            CONCAT(r.first_name," ",r.last_name) as reviewer_name')
            ->join('employees e', 'e.id = performance_reviews.employee_id')
            ->join('departments d', 'd.id = e.department_id', 'left')
            ->join('designations des', 'des.id = e.designation_id', 'left')
            ->join('employees r', 'r.id = performance_reviews.reviewer_id', 'left')
            ->orderBy('performance_reviews.review_date', 'DESC');

        if ($id) {
            return $builder->find($id);
        }
        return $builder->findAll();
    }
}
