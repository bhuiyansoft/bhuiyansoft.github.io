<?php

namespace App\Controllers\Public;

use App\Models\SurveyModel;
use App\Models\QuestionModel;
use App\Models\ResponseModel;
use App\Models\OptionModel;
use CodeIgniter\Controller;

class SurveyPublicController extends Controller
{
    public function show(string $slug): string
    {
        $survey = $this->getSurveyOrFail($slug);
        $questionModel = new QuestionModel();

        if ($survey['status'] !== 'published') {
            return view('public/survey-unavailable', ['survey' => $survey, 'reason' => 'not_published']);
        }

        if ($survey['end_date'] && strtotime($survey['end_date']) < time()) {
            return view('public/survey-unavailable', ['survey' => $survey, 'reason' => 'expired']);
        }

        if ($survey['response_limit']) {
            $responseModel = new ResponseModel();
            $count = \Config\Database::connect()->table('survey_responses')
                ->where('survey_id', $survey['id'])->where('status', 'complete')->countAllResults();
            if ($count >= $survey['response_limit']) {
                return view('public/survey-unavailable', ['survey' => $survey, 'reason' => 'limit_reached']);
            }
        }

        if (!$survey['allow_multiple_responses']) {
            $ip = $this->request->getIPAddress();
            $hasResponded = \Config\Database::connect()->table('survey_responses')
                ->where('survey_id', $survey['id'])
                ->where('respondent_ip', $ip)
                ->where('status', 'complete')
                ->countAllResults();

            if ($hasResponded) {
                return view('public/survey-unavailable', ['survey' => $survey, 'reason' => 'already_responded']);
            }
        }

        $questions = $questionModel->getSurveyQuestions($survey['id']);

        session()->set('survey_start_' . $survey['id'], time());

        return view('public/survey', [
            'survey'     => $survey,
            'questions'  => $questions,
            'is_preview' => false,
        ]);
    }

    public function submit(string $slug)
    {
        $survey = $this->getSurveyOrFail($slug);

        if ($survey['status'] !== 'published') {
            return redirect()->to('/s/' . $slug)->with('error', 'This survey is no longer accepting responses.');
        }

        $questionModel = new QuestionModel();
        $questions = $questionModel->getSurveyQuestions($survey['id']);

        // Validate required questions
        $errors = [];
        foreach ($questions as $question) {
            if ($question['is_required']) {
                $key = 'q_' . $question['id'];
                $value = $this->request->getPost($key);
                if (empty($value)) {
                    $errors[$question['id']] = 'This question requires an answer.';
                }
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('validation_errors', $errors);
        }

        $db = \Config\Database::connect();
        $startTime = session()->get('survey_start_' . $survey['id']);
        $timeTaken = $startTime ? (time() - $startTime) : null;

        $responseId = $db->table('survey_responses')->insert([
            'survey_id'      => $survey['id'],
            'respondent_ip'  => $this->request->getIPAddress(),
            'session_id'     => session_id(),
            'status'         => 'complete',
            'time_taken'     => $timeTaken,
            'user_agent'     => $this->request->getUserAgent()->getAgentString(),
            'referrer'       => $this->request->getServer('HTTP_REFERER'),
            'completed_at'   => date('Y-m-d H:i:s'),
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);

        $responseId = $db->insertID();

        foreach ($questions as $question) {
            $key = 'q_' . $question['id'];
            $value = $this->request->getPost($key);

            if ($value !== null) {
                $isMultiple = is_array($value);
                $db->table('survey_answers')->insert([
                    'response_id'   => $responseId,
                    'question_id'   => $question['id'],
                    'answer_value'  => $isMultiple ? null : $value,
                    'answer_values' => $isMultiple ? json_encode($value) : null,
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // Increment response counter
        $db->table('surveys')->where('id', $survey['id'])->set('total_responses', 'total_responses + 1', false)->update();

        session()->remove('survey_start_' . $survey['id']);

        if ($survey['redirect_url']) {
            return redirect()->to($survey['redirect_url']);
        }

        return redirect()->to('/s/' . $slug . '/thank-you');
    }

    public function thankYou(string $slug): string
    {
        $survey = $this->getSurveyOrFail($slug);
        return view('public/thank-you', ['survey' => $survey]);
    }

    private function getSurveyOrFail(string $slug): array
    {
        $surveyModel = new SurveyModel();
        $survey = $surveyModel->findBySlug($slug);

        if (!$survey) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $survey;
    }
}
