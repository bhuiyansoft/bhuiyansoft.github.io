<?php

namespace App\Controllers\Admin;

use App\Models\PerformanceModel;
use App\Models\EmployeeModel;

class PerformanceController extends BaseAdminController
{
    protected PerformanceModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new PerformanceModel();
    }

    public function index(): string
    {
        return view('performance/index', [
            'title'   => 'Performance Reviews',
            'reviews' => $this->model->getWithDetails()->findAll(),
        ]);
    }

    public function create(): string
    {
        $empModel = new EmployeeModel();
        return view('performance/create', [
            'title'     => 'New Performance Review',
            'employees' => $empModel->getActiveEmployees(),
        ]);
    }

    public function store()
    {
        $rules = [
            'employee_id'   => 'required|numeric',
            'review_period' => 'required',
            'review_date'   => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $scores = [
            $this->request->getPost('punctuality'),
            $this->request->getPost('quality_of_work'),
            $this->request->getPost('teamwork'),
            $this->request->getPost('communication'),
            $this->request->getPost('initiative'),
        ];
        $overall = array_sum($scores) / count($scores);

        $rating = 'satisfactory';
        if ($overall >= 4.5) $rating = 'excellent';
        elseif ($overall >= 3.5) $rating = 'good';
        elseif ($overall >= 2.5) $rating = 'satisfactory';
        elseif ($overall >= 1.5) $rating = 'needs_improvement';
        else $rating = 'unsatisfactory';

        $empId = session()->get('employee_id');

        $this->model->insert([
            'employee_id'           => $this->request->getPost('employee_id'),
            'reviewer_id'           => $empId,
            'review_period'         => $this->request->getPost('review_period'),
            'review_date'           => $this->request->getPost('review_date'),
            'punctuality'           => $this->request->getPost('punctuality'),
            'quality_of_work'       => $this->request->getPost('quality_of_work'),
            'teamwork'              => $this->request->getPost('teamwork'),
            'communication'         => $this->request->getPost('communication'),
            'initiative'            => $this->request->getPost('initiative'),
            'overall_score'         => $overall,
            'rating'                => $rating,
            'strengths'             => $this->request->getPost('strengths'),
            'areas_for_improvement' => $this->request->getPost('areas_for_improvement'),
            'goals_next_period'     => $this->request->getPost('goals_next_period'),
            'comments'              => $this->request->getPost('comments'),
            'status'                => 'submitted',
        ]);

        return redirect()->to(base_url('admin/performance'))->with('success', 'Performance review submitted.');
    }

    public function show(int $id): string
    {
        $review = $this->model->getWithDetails($id);
        if (!$review) {
            return redirect()->to(base_url('admin/performance'))->with('error', 'Review not found.');
        }
        return view('performance/show', ['title' => 'Review Details', 'review' => $review]);
    }

    public function edit(int $id): string
    {
        $review   = $this->model->find($id);
        $empModel = new EmployeeModel();
        return view('performance/edit', [
            'title'     => 'Edit Review',
            'review'    => $review,
            'employees' => $empModel->getActiveEmployees(),
        ]);
    }

    public function update(int $id)
    {
        $scores  = [$this->request->getPost('punctuality'), $this->request->getPost('quality_of_work'), $this->request->getPost('teamwork'), $this->request->getPost('communication'), $this->request->getPost('initiative')];
        $overall = array_sum($scores) / count($scores);
        $rating  = $overall >= 4.5 ? 'excellent' : ($overall >= 3.5 ? 'good' : ($overall >= 2.5 ? 'satisfactory' : ($overall >= 1.5 ? 'needs_improvement' : 'unsatisfactory')));

        $this->model->update($id, [
            'punctuality'           => $this->request->getPost('punctuality'),
            'quality_of_work'       => $this->request->getPost('quality_of_work'),
            'teamwork'              => $this->request->getPost('teamwork'),
            'communication'         => $this->request->getPost('communication'),
            'initiative'            => $this->request->getPost('initiative'),
            'overall_score'         => $overall,
            'rating'                => $rating,
            'strengths'             => $this->request->getPost('strengths'),
            'areas_for_improvement' => $this->request->getPost('areas_for_improvement'),
            'goals_next_period'     => $this->request->getPost('goals_next_period'),
            'comments'              => $this->request->getPost('comments'),
        ]);

        return redirect()->to(base_url('admin/performance'))->with('success', 'Review updated.');
    }
}
