<?php

namespace App\Models;

use CodeIgniter\Model;

class JobPostingModel extends Model
{
    protected $table      = 'job_postings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 'department_id', 'designation_id', 'employment_type',
        'vacancies', 'experience_required', 'education_required',
        'salary_from', 'salary_to', 'location', 'description',
        'requirements', 'benefits', 'deadline', 'status', 'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getWithDetails(?int $id = null): mixed
    {
        $builder = $this->select('job_postings.*, d.name as department_name, des.title as designation_title,
            (SELECT COUNT(*) FROM job_applications WHERE job_id = job_postings.id) as application_count')
            ->join('departments d', 'd.id = job_postings.department_id', 'left')
            ->join('designations des', 'des.id = job_postings.designation_id', 'left')
            ->orderBy('job_postings.created_at', 'DESC');

        if ($id) {
            return $builder->find($id);
        }
        return $builder->findAll();
    }
}

class JobApplicationModel extends Model
{
    protected $table      = 'job_applications';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'job_id', 'applicant_name', 'email', 'phone', 'resume',
        'cover_letter', 'experience_years', 'expected_salary',
        'stage', 'interview_date', 'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getWithJob(?int $id = null): mixed
    {
        $builder = $this->select('job_applications.*, jp.title as job_title, d.name as department_name')
            ->join('job_postings jp', 'jp.id = job_applications.job_id')
            ->join('departments d', 'd.id = jp.department_id', 'left')
            ->orderBy('job_applications.created_at', 'DESC');

        if ($id) {
            return $builder->find($id);
        }
        return $builder->findAll();
    }
}
