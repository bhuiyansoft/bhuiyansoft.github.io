<?php

namespace App\Models;

use CodeIgniter\Model;

class ResponseModel extends Model
{
    protected $table = 'survey_responses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'survey_id', 'respondent_ip', 'respondent_email', 'respondent_name',
        'session_id', 'status', 'time_taken', 'user_agent', 'referrer',
        'metadata', 'completed_at',
    ];
    protected $useTimestamps = true;

    public function getResponsesForSurvey(int $surveyId, int $perPage = 25): array
    {
        return $this->where('survey_id', $surveyId)
            ->where('status', 'complete')
            ->orderBy('completed_at', 'DESC')
            ->paginate($perPage);
    }

    public function getResponseWithAnswers(int $responseId): ?array
    {
        $response = $this->find($responseId);
        if (!$response) return null;

        $answers = $this->db->table('survey_answers sa')
            ->select('sa.*, q.title as question_title, q.type as question_type')
            ->join('questions q', 'q.id = sa.question_id')
            ->where('sa.response_id', $responseId)
            ->orderBy('q.sort_order', 'ASC')
            ->get()->getResultArray();

        $response['answers'] = $answers;
        return $response;
    }

    public function getSurveyAnalytics(int $surveyId): array
    {
        $total = $this->where('survey_id', $surveyId)->where('status', 'complete')->countAllResults();
        $partial = $this->where('survey_id', $surveyId)->where('status', 'partial')->countAllResults();

        $avgTime = $this->db->table('survey_responses')
            ->selectAvg('time_taken')
            ->where('survey_id', $surveyId)
            ->where('status', 'complete')
            ->where('time_taken IS NOT NULL')
            ->get()->getRowArray();

        $daily = $this->db->table('survey_responses')
            ->select('DATE(completed_at) as date, COUNT(*) as count')
            ->where('survey_id', $surveyId)
            ->where('status', 'complete')
            ->where('completed_at >=', date('Y-m-d', strtotime('-30 days')))
            ->groupBy('DATE(completed_at)')
            ->orderBy('date', 'ASC')
            ->get()->getResultArray();

        return [
            'total_responses'   => $total,
            'partial_responses' => $partial,
            'completion_rate'   => ($total + $partial) > 0 ? round($total / ($total + $partial) * 100, 1) : 0,
            'avg_time_seconds'  => (int)($avgTime['time_taken'] ?? 0),
            'daily_responses'   => $daily,
        ];
    }

    public function getQuestionStats(int $surveyId): array
    {
        $questions = $this->db->table('questions')->where('survey_id', $surveyId)->orderBy('sort_order')->get()->getResultArray();
        $stats = [];

        foreach ($questions as $question) {
            $stat = [
                'question_id'   => $question['id'],
                'question_title' => $question['title'],
                'question_type' => $question['type'],
                'answer_count'  => 0,
                'data'          => [],
            ];

            $answers = $this->db->table('survey_answers sa')
                ->select('sa.*')
                ->join('survey_responses sr', 'sr.id = sa.response_id')
                ->where('sa.question_id', $question['id'])
                ->where('sr.status', 'complete')
                ->get()->getResultArray();

            $stat['answer_count'] = count($answers);

            if (in_array($question['type'], ['single_choice', 'multiple_choice', 'dropdown', 'yes_no'])) {
                $choices = [];
                foreach ($answers as $answer) {
                    $values = $answer['answer_values'] ? json_decode($answer['answer_values'], true) : [$answer['answer_value']];
                    foreach ((array)$values as $v) {
                        $choices[$v] = ($choices[$v] ?? 0) + 1;
                    }
                }
                $stat['data'] = $choices;
            } elseif ($question['type'] === 'rating' || $question['type'] === 'nps') {
                $ratings = [];
                foreach ($answers as $answer) {
                    $v = (int)$answer['answer_value'];
                    $ratings[$v] = ($ratings[$v] ?? 0) + 1;
                }
                ksort($ratings);
                $stat['data'] = $ratings;
                $stat['average'] = count($answers) > 0 ? round(array_sum(array_map(fn($a) => (int)$a['answer_value'], $answers)) / count($answers), 1) : 0;
            } else {
                $stat['data'] = array_filter(array_column($answers, 'answer_value'));
            }

            $stats[] = $stat;
        }

        return $stats;
    }
}
