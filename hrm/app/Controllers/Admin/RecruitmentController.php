<?php

namespace App\Controllers\Admin;

use App\Models\JobPostingModel;
use App\Models\JobApplicationModel;
use App\Models\DepartmentModel;
use App\Models\DesignationModel;

class RecruitmentController extends BaseAdminController
{
    protected JobPostingModel $jobModel;
    protected JobApplicationModel $appModel;

    public function __construct()
    {
        parent::__construct();
        $this->jobModel = new JobPostingModel();
        $this->appModel = new JobApplicationModel();
    }

    public function index(): string
    {
        $openJobs   = $this->jobModel->where('status', 'open')->countAllResults();
        $totalApps  = $this->appModel->countAll();
        $shortlisted = $this->appModel->where('stage', 'interview')->countAllResults();
        $hired      = $this->appModel->where('stage', 'hired')->countAllResults();

        return view('recruitment/index', [
            'title'       => 'Recruitment',
            'openJobs'    => $openJobs,
            'totalApps'   => $totalApps,
            'shortlisted' => $shortlisted,
            'hired'       => $hired,
            'recentJobs'  => $this->jobModel->getWithDetails()->limit(5)->findAll(),
            'recentApps'  => $this->appModel->getWithJob()->limit(5)->findAll(),
        ]);
    }

    public function jobs(): string
    {
        return view('recruitment/jobs', [
            'title' => 'Job Postings',
            'jobs'  => $this->jobModel->getWithDetails()->findAll(),
        ]);
    }

    public function createJob(): string
    {
        $deptModel = new DepartmentModel();
        $desModel  = new DesignationModel();
        return view('recruitment/create_job', [
            'title'        => 'Post New Job',
            'departments'  => $deptModel->getActive(),
            'designations' => $desModel->findAll(),
        ]);
    }

    public function storeJob()
    {
        $rules = [
            'title'         => 'required|min_length[3]|max_length[150]',
            'department_id' => 'required|numeric',
            'vacancies'     => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->jobModel->insert([
            'title'               => $this->request->getPost('title'),
            'department_id'       => $this->request->getPost('department_id'),
            'designation_id'      => $this->request->getPost('designation_id') ?: null,
            'employment_type'     => $this->request->getPost('employment_type'),
            'vacancies'           => $this->request->getPost('vacancies'),
            'experience_required' => $this->request->getPost('experience_required'),
            'education_required'  => $this->request->getPost('education_required'),
            'salary_from'         => $this->request->getPost('salary_from') ?: null,
            'salary_to'           => $this->request->getPost('salary_to') ?: null,
            'location'            => $this->request->getPost('location'),
            'description'         => $this->request->getPost('description'),
            'requirements'        => $this->request->getPost('requirements'),
            'benefits'            => $this->request->getPost('benefits'),
            'deadline'            => $this->request->getPost('deadline') ?: null,
            'status'              => 'open',
            'created_by'          => session()->get('user_id'),
        ]);

        return redirect()->to(base_url('admin/recruitment/jobs'))->with('success', 'Job posted successfully.');
    }

    public function editJob(int $id): string
    {
        $job = $this->jobModel->find($id);
        if (!$job) {
            return redirect()->to(base_url('admin/recruitment/jobs'))->with('error', 'Job not found.');
        }
        $deptModel = new DepartmentModel();
        $desModel  = new DesignationModel();
        return view('recruitment/edit_job', [
            'title'        => 'Edit Job',
            'job'          => $job,
            'departments'  => $deptModel->getActive(),
            'designations' => $desModel->findAll(),
        ]);
    }

    public function updateJob(int $id)
    {
        $this->jobModel->update($id, [
            'title'               => $this->request->getPost('title'),
            'department_id'       => $this->request->getPost('department_id'),
            'designation_id'      => $this->request->getPost('designation_id') ?: null,
            'employment_type'     => $this->request->getPost('employment_type'),
            'vacancies'           => $this->request->getPost('vacancies'),
            'experience_required' => $this->request->getPost('experience_required'),
            'salary_from'         => $this->request->getPost('salary_from') ?: null,
            'salary_to'           => $this->request->getPost('salary_to') ?: null,
            'description'         => $this->request->getPost('description'),
            'requirements'        => $this->request->getPost('requirements'),
            'deadline'            => $this->request->getPost('deadline') ?: null,
            'status'              => $this->request->getPost('status'),
        ]);
        return redirect()->to(base_url('admin/recruitment/jobs'))->with('success', 'Job updated successfully.');
    }

    public function deleteJob(int $id)
    {
        $this->jobModel->delete($id);
        return redirect()->to(base_url('admin/recruitment/jobs'))->with('success', 'Job deleted.');
    }

    public function applications(): string
    {
        $stage = $this->request->getGet('stage');
        $query = $this->appModel->getWithJob();
        if ($stage) {
            $query->where('job_applications.stage', $stage);
        }
        return view('recruitment/applications', [
            'title'        => 'Job Applications',
            'applications' => $query->findAll(),
            'stage'        => $stage,
        ]);
    }

    public function showApplication(int $id): string
    {
        $app = $this->appModel->getWithJob($id);
        if (!$app) {
            return redirect()->to(base_url('admin/recruitment/applications'))->with('error', 'Application not found.');
        }
        return view('recruitment/show_application', [
            'title' => 'Application Details',
            'application' => $app,
        ]);
    }

    public function updateStage(int $id)
    {
        $this->appModel->update($id, [
            'stage' => $this->request->getPost('stage'),
            'notes' => $this->request->getPost('notes'),
            'interview_date' => $this->request->getPost('interview_date') ?: null,
        ]);
        return redirect()->back()->with('success', 'Application stage updated.');
    }
}
