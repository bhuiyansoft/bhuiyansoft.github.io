<?php

namespace App\Controllers\Admin;

use App\Models\LeaveModel;
use App\Models\LeaveTypeModel;
use App\Models\LeaveAllocationModel;
use App\Models\EmployeeModel;

class LeaveController extends BaseAdminController
{
    protected LeaveModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new LeaveModel();
    }

    public function index(): string
    {
        $status   = $this->request->getGet('status');
        $builder  = $this->model->getWithDetails();
        if ($status) {
            $builder->where('leave_requests.status', $status);
        }

        return view('leaves/index', [
            'title'      => 'Leave Requests',
            'leaves'     => $builder->findAll(),
            'stats'      => $this->model->getStats(),
            'filter'     => $status,
        ]);
    }

    public function create(): string
    {
        $leaveTypeModel = new LeaveTypeModel();
        $empModel       = new EmployeeModel();
        return view('leaves/create', [
            'title'       => 'Apply Leave',
            'leave_types' => $leaveTypeModel->getActive(),
            'employees'   => $empModel->getActiveEmployees(),
        ]);
    }

    public function store()
    {
        $rules = [
            'employee_id'   => 'required|numeric',
            'leave_type_id' => 'required|numeric',
            'from_date'     => 'required|valid_date',
            'to_date'       => 'required|valid_date',
            'reason'        => 'required|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $from      = $this->request->getPost('from_date');
        $to        = $this->request->getPost('to_date');
        $totalDays = (strtotime($to) - strtotime($from)) / 86400 + 1;

        $this->model->insert([
            'employee_id'   => $this->request->getPost('employee_id'),
            'leave_type_id' => $this->request->getPost('leave_type_id'),
            'from_date'     => $from,
            'to_date'       => $to,
            'total_days'    => $totalDays,
            'reason'        => $this->request->getPost('reason'),
            'status'        => 'pending',
        ]);

        return redirect()->to(base_url('admin/leaves'))->with('success', 'Leave request submitted successfully.');
    }

    public function show(int $id): string
    {
        $leave = $this->model->getWithDetails($id);
        if (!$leave) {
            return redirect()->to(base_url('admin/leaves'))->with('error', 'Leave request not found.');
        }

        $allocationModel = new LeaveAllocationModel();
        $balance         = $allocationModel->getEmployeeBalance($leave['employee_id'], date('Y'));

        return view('leaves/show', [
            'title'   => 'Leave Request Details',
            'leave'   => $leave,
            'balance' => $balance,
        ]);
    }

    public function approve(int $id)
    {
        if (!$this->hasPermission(['super_admin', 'admin', 'hr_manager', 'manager'])) {
            return $this->denyAccess();
        }

        $leave = $this->model->find($id);
        if (!$leave || $leave['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Invalid request.');
        }

        $empId    = $this->model->db->query("SELECT employee_id FROM users WHERE id = ?", [session()->get('user_id')])->getRow()->employee_id ?? null;
        $this->model->update($id, [
            'status'         => 'approved',
            'approved_by'    => $empId,
            'approval_date'  => date('Y-m-d H:i:s'),
        ]);

        // Update leave allocation
        $allocationModel = new LeaveAllocationModel();
        $alloc = $allocationModel->where('employee_id', $leave['employee_id'])
            ->where('leave_type_id', $leave['leave_type_id'])
            ->where('year', date('Y'))
            ->first();

        if ($alloc) {
            $newUsed      = $alloc['used_days'] + $leave['total_days'];
            $newRemaining = $alloc['remaining_days'] - $leave['total_days'];
            $allocationModel->update($alloc['id'], [
                'used_days'      => $newUsed,
                'remaining_days' => max(0, $newRemaining),
            ]);
        }

        return redirect()->back()->with('success', 'Leave request approved.');
    }

    public function reject(int $id)
    {
        $leave = $this->model->find($id);
        if (!$leave) {
            return redirect()->back()->with('error', 'Request not found.');
        }

        $this->model->update($id, [
            'status'           => 'rejected',
            'rejection_reason' => $this->request->getPost('rejection_reason'),
        ]);

        return redirect()->back()->with('success', 'Leave request rejected.');
    }

    public function cancel(int $id)
    {
        $this->model->update($id, ['status' => 'cancelled']);
        return redirect()->back()->with('success', 'Leave request cancelled.');
    }

    public function types(): string
    {
        $leaveTypeModel = new LeaveTypeModel();
        return view('leaves/types', [
            'title'       => 'Leave Types',
            'leave_types' => $leaveTypeModel->findAll(),
        ]);
    }

    public function storeType()
    {
        $leaveTypeModel = new LeaveTypeModel();
        $leaveTypeModel->insert([
            'name'              => $this->request->getPost('name'),
            'days_allowed'      => $this->request->getPost('days_allowed'),
            'carry_forward'     => $this->request->getPost('carry_forward') ? 1 : 0,
            'is_paid'           => $this->request->getPost('is_paid') ? 1 : 0,
            'requires_approval' => $this->request->getPost('requires_approval') ? 1 : 0,
            'color'             => $this->request->getPost('color') ?? '#007bff',
            'status'            => 1,
        ]);
        return redirect()->back()->with('success', 'Leave type added successfully.');
    }

    public function allocations(): string
    {
        $allocationModel = new LeaveAllocationModel();
        $leaveTypeModel  = new LeaveTypeModel();
        $empModel        = new EmployeeModel();
        return view('leaves/allocations', [
            'title'       => 'Leave Allocations',
            'allocations' => $allocationModel->getAllocationsWithDetails(),
            'employees'   => $empModel->getActiveEmployees(),
            'leave_types' => $leaveTypeModel->getActive(),
        ]);
    }

    public function storeAllocation()
    {
        $allocationModel = new LeaveAllocationModel();
        $days = $this->request->getPost('allocated_days');
        $allocationModel->insert([
            'employee_id'   => $this->request->getPost('employee_id'),
            'leave_type_id' => $this->request->getPost('leave_type_id'),
            'year'          => $this->request->getPost('year') ?? date('Y'),
            'allocated_days' => $days,
            'used_days'      => 0,
            'remaining_days' => $days,
        ]);
        return redirect()->back()->with('success', 'Leave allocated successfully.');
    }

    public function balance(int $empId)
    {
        $allocationModel = new LeaveAllocationModel();
        return $this->response->setJSON($allocationModel->getEmployeeBalance($empId, date('Y')));
    }
}
