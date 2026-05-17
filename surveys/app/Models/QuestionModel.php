<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionModel extends Model
{
    protected $table = 'questions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'survey_id', 'type', 'title', 'description', 'placeholder',
        'is_required', 'sort_order', 'settings',
    ];
    protected $useTimestamps = true;

    public function getSurveyQuestions(int $surveyId): array
    {
        $questions = $this->where('survey_id', $surveyId)
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        $optionModel = new OptionModel();
        foreach ($questions as &$question) {
            $question['options'] = $optionModel->getByQuestion($question['id']);
            $question['settings'] = json_decode($question['settings'] ?? '{}', true) ?? [];
        }

        return $questions;
    }

    public function getNextOrder(int $surveyId): int
    {
        $result = $this->selectMax('sort_order')
            ->where('survey_id', $surveyId)
            ->first();
        return ($result['sort_order'] ?? 0) + 1;
    }

    public function reorderQuestions(int $surveyId, array $orderMap): void
    {
        foreach ($orderMap as $questionId => $order) {
            $this->where('id', $questionId)
                ->where('survey_id', $surveyId)
                ->set(['sort_order' => $order])
                ->update();
        }
    }
}
