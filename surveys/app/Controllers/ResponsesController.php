<?php

namespace App\Controllers;

use App\Models\SurveyModel;
use App\Models\ResponseModel;
use CodeIgniter\Controller;

class ResponsesController extends Controller
{
    protected int $tenantId;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->tenantId = (int)session()->get('tenant_id');
    }

    public function index(int $surveyId): string
    {
        $survey = $this->getSurveyOrFail($surveyId);
        $responseModel = new ResponseModel();

        $responses = $responseModel->getResponsesForSurvey($surveyId);
        $analytics = $responseModel->getSurveyAnalytics($surveyId);
        $pager = $responseModel->pager;

        return view('responses/index', [
            'survey'    => $survey,
            'responses' => $responses,
            'analytics' => $analytics,
            'pager'     => $pager,
        ]);
    }

    public function show(int $surveyId, int $responseId): string
    {
        $survey = $this->getSurveyOrFail($surveyId);
        $responseModel = new ResponseModel();
        $response = $responseModel->getResponseWithAnswers($responseId);

        if (!$response || $response['survey_id'] !== $surveyId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('responses/show', [
            'survey'   => $survey,
            'response' => $response,
        ]);
    }

    private function getSurveyOrFail(int $surveyId): array
    {
        $surveyModel = new SurveyModel();
        $survey = $surveyModel->find($surveyId);

        if (!$survey || $survey['tenant_id'] !== $this->tenantId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $survey;
    }
}
