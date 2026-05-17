<?php

namespace App\Controllers;

use App\Models\SurveyModel;
use App\Models\QuestionModel;
use App\Models\TenantModel;
use App\Models\PlanModel;
use CodeIgniter\Controller;

class SurveysController extends Controller
{
    protected int $tenantId;
    protected int $userId;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->tenantId = (int)session()->get('tenant_id');
        $this->userId = (int)session()->get('user_id');
    }

    public function index(): string
    {
        $surveyModel = new SurveyModel();
        $status = $this->request->getGet('status');

        $surveys = $surveyModel->getByTenant($this->tenantId, $status ?: null);

        foreach ($surveys as &$survey) {
            $survey['response_count'] = \Config\Database::connect()
                ->table('survey_responses')
                ->where('survey_id', $survey['id'])
                ->where('status', 'complete')
                ->countAllResults();

            $survey['question_count'] = \Config\Database::connect()
                ->table('questions')
                ->where('survey_id', $survey['id'])
                ->countAllResults();
        }

        return view('surveys/index', [
            'surveys'        => $surveys,
            'current_status' => $status,
        ]);
    }

    public function create(): string
    {
        $this->checkSurveyLimit();
        return view('surveys/create');
    }

    public function store()
    {
        $this->checkSurveyLimit();

        $rules = [
            'title'       => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty|max_length[1000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $surveyModel = new SurveyModel();
        $surveyId = $surveyModel->insert([
            'tenant_id'          => $this->tenantId,
            'user_id'            => $this->userId,
            'title'              => $this->request->getPost('title'),
            'description'        => $this->request->getPost('description'),
            'slug'               => $surveyModel->generateSlug($this->request->getPost('title')),
            'status'             => 'draft',
            'primary_color'      => $this->request->getPost('primary_color') ?? '#4F46E5',
            'is_anonymous'       => $this->request->getPost('is_anonymous') ?? 1,
            'show_progress_bar'  => 1,
            'show_question_numbers' => 1,
            'thank_you_message'  => 'Thank you for completing this survey! Your response has been recorded.',
        ]);

        return redirect()->to("/surveys/{$surveyId}/edit")->with('success', 'Survey created! Now add your questions.');
    }

    public function edit(int $id): string
    {
        $survey = $this->getSurveyOrFail($id);
        $questionModel = new QuestionModel();
        $questions = $questionModel->getSurveyQuestions($id);

        return view('surveys/edit', [
            'survey'    => $survey,
            'questions' => $questions,
        ]);
    }

    public function update(int $id)
    {
        $survey = $this->getSurveyOrFail($id);

        $rules = [
            'title'       => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $surveyModel = new SurveyModel();
        $surveyModel->update($id, [
            'title'                  => $this->request->getPost('title'),
            'description'            => $this->request->getPost('description'),
            'thank_you_message'      => $this->request->getPost('thank_you_message'),
            'redirect_url'           => $this->request->getPost('redirect_url'),
            'primary_color'          => $this->request->getPost('primary_color') ?? '#4F46E5',
            'is_anonymous'           => $this->request->getPost('is_anonymous') ? 1 : 0,
            'allow_multiple_responses' => $this->request->getPost('allow_multiple_responses') ? 1 : 0,
            'show_progress_bar'      => $this->request->getPost('show_progress_bar') ? 1 : 0,
            'show_question_numbers'  => $this->request->getPost('show_question_numbers') ? 1 : 0,
            'start_date'             => $this->request->getPost('start_date') ?: null,
            'end_date'               => $this->request->getPost('end_date') ?: null,
        ]);

        return redirect()->back()->with('success', 'Survey settings saved.');
    }

    public function delete(int $id)
    {
        $survey = $this->getSurveyOrFail($id);
        $surveyModel = new SurveyModel();
        $surveyModel->delete($id);

        return redirect()->to('/surveys')->with('success', 'Survey deleted.');
    }

    public function publish(int $id)
    {
        $survey = $this->getSurveyOrFail($id);
        $questionModel = new QuestionModel();

        if (count($questionModel->getSurveyQuestions($id)) === 0) {
            return redirect()->back()->with('error', 'Add at least one question before publishing.');
        }

        $surveyModel = new SurveyModel();
        $surveyModel->update($id, [
            'status'       => 'published',
            'published_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Survey is now live!');
    }

    public function close(int $id)
    {
        $this->getSurveyOrFail($id);
        $surveyModel = new SurveyModel();
        $surveyModel->update($id, ['status' => 'closed']);

        return redirect()->back()->with('success', 'Survey closed. No more responses will be accepted.');
    }

    public function share(int $id): string
    {
        $survey = $this->getSurveyOrFail($id);
        return view('surveys/share', ['survey' => $survey]);
    }

    public function preview(int $id): string
    {
        $survey = $this->getSurveyOrFail($id);
        $questionModel = new QuestionModel();
        return view('public/survey', [
            'survey'    => $survey,
            'questions' => $questionModel->getSurveyQuestions($id),
            'is_preview' => true,
        ]);
    }

    private function getSurveyOrFail(int $id): array
    {
        $surveyModel = new SurveyModel();
        $survey = $surveyModel->find($id);

        if (!$survey || $survey['tenant_id'] !== $this->tenantId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $survey;
    }

    private function checkSurveyLimit(): void
    {
        $tenantModel = new TenantModel();
        $plan = $tenantModel->getPlan($this->tenantId);

        if ($plan && $plan['max_surveys'] > 0) {
            $surveyModel = new SurveyModel();
            $count = count($surveyModel->getByTenant($this->tenantId));
            if ($count >= $plan['max_surveys']) {
                redirect()->to('/billing')->with('error', "You've reached your plan limit of {$plan['max_surveys']} surveys. Please upgrade.")->send();
                exit;
            }
        }
    }
}
