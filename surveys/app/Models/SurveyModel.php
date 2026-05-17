<?php

namespace App\Models;

use CodeIgniter\Model;

class SurveyModel extends Model
{
    protected $table = 'surveys';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'tenant_id', 'user_id', 'title', 'description', 'slug', 'status',
        'start_date', 'end_date', 'is_anonymous', 'allow_multiple_responses',
        'show_progress_bar', 'show_question_numbers', 'redirect_url',
        'thank_you_message', 'cover_image', 'primary_color', 'password',
        'response_limit', 'total_responses', 'settings', 'published_at',
    ];
    protected $useTimestamps = true;

    public function getByTenant(int $tenantId, string $status = null): array
    {
        $builder = $this->where('tenant_id', $tenantId);
        if ($status) {
            $builder->where('status', $status);
        }
        return $builder->orderBy('created_at', 'DESC')->findAll();
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }

    public function getSurveyWithStats(int $surveyId): ?array
    {
        $survey = $this->find($surveyId);
        if (!$survey) return null;

        $survey['total_responses'] = $this->db->table('survey_responses')
            ->where('survey_id', $surveyId)
            ->where('status', 'complete')
            ->countAllResults();

        $survey['question_count'] = $this->db->table('questions')
            ->where('survey_id', $surveyId)
            ->countAllResults();

        return $survey;
    }

    public function generateSlug(string $title): string
    {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
        $slug = trim($slug, '-');
        $slug = substr($slug, 0, 80) . '-' . substr(md5(uniqid()), 0, 8);
        return $slug;
    }

    public function getTenantStats(int $tenantId): array
    {
        $total = $this->where('tenant_id', $tenantId)->countAllResults();
        $published = $this->where('tenant_id', $tenantId)->where('status', 'published')->countAllResults();
        $draft = $this->where('tenant_id', $tenantId)->where('status', 'draft')->countAllResults();

        $totalResponses = $this->db->table('survey_responses sr')
            ->join('surveys s', 's.id = sr.survey_id')
            ->where('s.tenant_id', $tenantId)
            ->where('sr.status', 'complete')
            ->countAllResults();

        return [
            'total_surveys'    => $total,
            'published'        => $published,
            'draft'            => $draft,
            'total_responses'  => $totalResponses,
        ];
    }

    public function getRecentActivity(int $tenantId, int $limit = 10): array
    {
        return $this->db->table('survey_responses sr')
            ->select('sr.*, s.title as survey_title, s.slug as survey_slug')
            ->join('surveys s', 's.id = sr.survey_id')
            ->where('s.tenant_id', $tenantId)
            ->where('sr.status', 'complete')
            ->orderBy('sr.completed_at', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
    }
}
