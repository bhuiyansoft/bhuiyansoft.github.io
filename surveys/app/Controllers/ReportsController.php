<?php

namespace App\Controllers;

use App\Models\SurveyModel;
use App\Models\ResponseModel;
use App\Models\QuestionModel;
use CodeIgniter\Controller;

class ReportsController extends Controller
{
    protected int $tenantId;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->tenantId = (int)session()->get('tenant_id');
    }

    public function show(int $surveyId): string
    {
        $survey = $this->getSurveyOrFail($surveyId);
        $responseModel = new ResponseModel();
        $questionModel = new QuestionModel();

        $analytics = $responseModel->getSurveyAnalytics($surveyId);
        $questionStats = $responseModel->getQuestionStats($surveyId);
        $questions = $questionModel->getSurveyQuestions($surveyId);

        return view('reports/show', [
            'survey'         => $survey,
            'analytics'      => $analytics,
            'question_stats' => $questionStats,
            'questions'      => $questions,
        ]);
    }

    public function export(int $surveyId, string $format)
    {
        $survey = $this->getSurveyOrFail($surveyId);
        $responseModel = new ResponseModel();

        if ($format === 'csv') {
            return $this->exportCsv($survey, $responseModel);
        }

        return redirect()->back()->with('error', 'Unsupported export format.');
    }

    private function exportCsv(array $survey, ResponseModel $responseModel)
    {
        $db = \Config\Database::connect();

        $questions = $db->table('questions')
            ->where('survey_id', $survey['id'])
            ->orderBy('sort_order')
            ->get()->getResultArray();

        $responses = $db->table('survey_responses')
            ->where('survey_id', $survey['id'])
            ->where('status', 'complete')
            ->orderBy('completed_at', 'DESC')
            ->get()->getResultArray();

        $filename = 'survey-' . $survey['id'] . '-responses-' . date('Ymd') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        $headers = ['Response ID', 'Submitted At', 'IP Address', 'Time Taken (s)'];
        foreach ($questions as $q) {
            $headers[] = $q['title'];
        }
        fputcsv($output, $headers);

        foreach ($responses as $response) {
            $row = [
                $response['id'],
                $response['completed_at'],
                $response['respondent_ip'],
                $response['time_taken'],
            ];

            foreach ($questions as $question) {
                $answer = $db->table('survey_answers')
                    ->where('response_id', $response['id'])
                    ->where('question_id', $question['id'])
                    ->get()->getRowArray();

                if ($answer) {
                    $value = $answer['answer_values']
                        ? implode(', ', json_decode($answer['answer_values'], true))
                        : $answer['answer_value'];
                    $row[] = $value;
                } else {
                    $row[] = '';
                }
            }

            fputcsv($output, $row);
        }

        fclose($output);
        exit;
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
