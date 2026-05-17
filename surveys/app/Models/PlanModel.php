<?php

namespace App\Models;

use CodeIgniter\Model;

class PlanModel extends Model
{
    protected $table = 'plans';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'name', 'slug', 'price_monthly', 'price_yearly',
        'max_surveys', 'max_responses_per_survey', 'max_team_members',
        'max_questions_per_survey', 'features', 'is_active', 'sort_order',
    ];
    protected $useTimestamps = true;

    public function getActivePlans(): array
    {
        return $this->where('is_active', 1)->orderBy('sort_order', 'ASC')->findAll();
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }
}
