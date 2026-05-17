<?php

namespace App\Controllers;

use App\Models\SurveyModel;
use App\Models\ResponseModel;
use App\Models\TenantModel;
use App\Models\PlanModel;
use CodeIgniter\Controller;

class DashboardController extends Controller
{
    protected int $tenantId;

    public function __construct()
    {
        $this->tenantId = (int)session()->get('tenant_id');
    }

    public function index(): string
    {
        $surveyModel = new SurveyModel();
        $tenantModel = new TenantModel();
        $planModel = new PlanModel();

        $tenant = $tenantModel->find($this->tenantId);
        $plan = $planModel->find($tenant['plan_id']);
        $stats = $surveyModel->getTenantStats($this->tenantId);
        $recentSurveys = $surveyModel->getByTenant($this->tenantId);
        $recentActivity = $surveyModel->getRecentActivity($this->tenantId, 5);

        // Build response trend (last 7 days)
        $trend = $this->getResponseTrend();

        return view('dashboard/index', [
            'stats'          => $stats,
            'recent_surveys' => array_slice($recentSurveys, 0, 5),
            'recent_activity' => $recentActivity,
            'tenant'         => $tenant,
            'plan'           => $plan,
            'plan_features'  => json_decode($plan['features'] ?? '[]', true),
            'response_trend' => $trend,
        ]);
    }

    private function getResponseTrend(): array
    {
        $days = 7;
        $dates = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $dates[date('Y-m-d', strtotime("-{$i} days"))] = 0;
        }

        $results = $this->db->table('survey_responses sr')
            ->select('DATE(sr.completed_at) as date, COUNT(*) as count')
            ->join('surveys s', 's.id = sr.survey_id')
            ->where('s.tenant_id', $this->tenantId)
            ->where('sr.status', 'complete')
            ->where('sr.completed_at >=', date('Y-m-d', strtotime("-{$days} days")))
            ->groupBy('DATE(sr.completed_at)')
            ->get()->getResultArray();

        foreach ($results as $row) {
            if (isset($dates[$row['date']])) {
                $dates[$row['date']] = (int)$row['count'];
            }
        }

        return $dates;
    }

    protected $db;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->db = \Config\Database::connect();
    }
}
