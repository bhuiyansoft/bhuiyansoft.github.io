<?php

namespace App\Controllers;

use App\Models\QuestionModel;
use App\Models\OptionModel;
use App\Models\SurveyModel;
use CodeIgniter\Controller;

class QuestionsController extends Controller
{
    protected int $tenantId;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->tenantId = (int)session()->get('tenant_id');
    }

    public function store(int $surveyId)
    {
        $this->verifySurveyAccess($surveyId);
        $json = $this->request->getJSON(true);

        if (!$json || empty($json['type']) || empty($json['title'])) {
            return $this->response->setStatusCode(422)->setJSON(['error' => 'Type and title are required.']);
        }

        $questionModel = new QuestionModel();
        $optionModel = new OptionModel();

        $questionId = $questionModel->insert([
            'survey_id'   => $surveyId,
            'type'        => $json['type'],
            'title'       => $json['title'],
            'description' => $json['description'] ?? null,
            'placeholder' => $json['placeholder'] ?? null,
            'is_required' => $json['is_required'] ?? 0,
            'sort_order'  => $questionModel->getNextOrder($surveyId),
            'settings'    => json_encode($json['settings'] ?? []),
        ]);

        if (!empty($json['options'])) {
            $optionModel->syncOptions($questionId, $json['options']);
        }

        $question = $questionModel->find($questionId);
        $question['options'] = $optionModel->getByQuestion($questionId);

        return $this->response->setJSON(['success' => true, 'question' => $question]);
    }

    public function update(int $questionId)
    {
        $question = $this->getQuestionOrFail($questionId);
        $json = $this->request->getJSON(true);

        $questionModel = new QuestionModel();
        $optionModel = new OptionModel();

        $questionModel->update($questionId, [
            'title'       => $json['title'] ?? $question['title'],
            'description' => $json['description'] ?? null,
            'placeholder' => $json['placeholder'] ?? null,
            'is_required' => $json['is_required'] ?? 0,
            'settings'    => json_encode($json['settings'] ?? []),
        ]);

        if (isset($json['options'])) {
            $optionModel->syncOptions($questionId, $json['options']);
        }

        $updated = $questionModel->find($questionId);
        $updated['options'] = $optionModel->getByQuestion($questionId);

        return $this->response->setJSON(['success' => true, 'question' => $updated]);
    }

    public function delete(int $questionId)
    {
        $question = $this->getQuestionOrFail($questionId);
        $questionModel = new QuestionModel();
        $optionModel = new OptionModel();

        $optionModel->where('question_id', $questionId)->delete();
        $questionModel->delete($questionId);

        return $this->response->setJSON(['success' => true]);
    }

    public function reorder(int $surveyId)
    {
        $this->verifySurveyAccess($surveyId);
        $json = $this->request->getJSON(true);

        if (empty($json['order'])) {
            return $this->response->setStatusCode(422)->setJSON(['error' => 'Order is required.']);
        }

        $questionModel = new QuestionModel();
        $questionModel->reorderQuestions($surveyId, $json['order']);

        return $this->response->setJSON(['success' => true]);
    }

    private function getQuestionOrFail(int $questionId): array
    {
        $questionModel = new QuestionModel();
        $question = $questionModel->find($questionId);

        if (!$question) {
            $this->response->setStatusCode(404)->setJSON(['error' => 'Question not found.'])->send();
            exit;
        }

        $this->verifySurveyAccess($question['survey_id']);
        return $question;
    }

    private function verifySurveyAccess(int $surveyId): void
    {
        $surveyModel = new SurveyModel();
        $survey = $surveyModel->find($surveyId);

        if (!$survey || $survey['tenant_id'] !== $this->tenantId) {
            $this->response->setStatusCode(403)->setJSON(['error' => 'Access denied.'])->send();
            exit;
        }
    }
}
