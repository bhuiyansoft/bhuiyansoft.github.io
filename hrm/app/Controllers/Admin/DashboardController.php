<?php

namespace App\Controllers\Admin;

use App\Models\EmployeeModel;
use App\Models\AttendanceModel;
use App\Models\LeaveModel;
use App\Models\PayrollModel;
use App\Models\RecruitmentModel;

class DashboardController extends BaseAdminController
{
    public function index(): string
    {
        $employeeModel  = new EmployeeModel();
        $attendanceModel = new AttendanceModel();
        $leaveModel     = new LeaveModel();
        $payrollModel   = new PayrollModel();

        $employeeStats  = $employeeModel->getStats();
        $attendanceStats = $attendanceModel->getTodayStats();
        $leaveStats     = $leaveModel->getStats();

        // Monthly payroll summary
        $payrollSummary = $payrollModel->getSummary(date('n'), date('Y'));

        // Recent activities
        $recentEmployees = $employeeModel->select('id, employee_id, first_name, last_name, photo, join_date')
            ->orderBy('created_at', 'DESC')->limit(5)->findAll();

        $pendingLeaves = $leaveModel->getWithDetails()
            ->where('leave_requests.status', 'pending')
            ->limit(5)
            ->findAll();

        // Dept headcount chart data
        $db = \Config\Database::connect();
        $deptChart = $db->query("SELECT d.name, COUNT(e.id) as count FROM departments d LEFT JOIN employees e ON e.department_id = d.id AND e.status='active' AND e.deleted_at IS NULL GROUP BY d.id")->getResultArray();

        // Monthly attendance trend (last 7 days)
        $attendanceTrend = $db->query("SELECT date,
            SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) as present,
            SUM(CASE WHEN status='absent' THEN 1 ELSE 0 END) as absent
            FROM attendance WHERE date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY date ORDER BY date")->getResultArray();

        return view('dashboard/index', [
            'title'           => 'Dashboard',
            'employeeStats'   => $employeeStats,
            'attendanceStats' => $attendanceStats,
            'leaveStats'      => $leaveStats,
            'payrollSummary'  => $payrollSummary,
            'recentEmployees' => $recentEmployees,
            'pendingLeaves'   => $pendingLeaves,
            'deptChart'       => $deptChart,
            'attendanceTrend' => $attendanceTrend,
        ]);
    }

    public function notifications(): string
    {
        $notifications = db_connect()->table('notifications')
            ->where('user_id', session()->get('user_id'))
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        return $this->response->setJSON(['notifications' => $notifications]);
    }

    public function markNotificationsRead()
    {
        db_connect()->table('notifications')
            ->where('user_id', session()->get('user_id'))
            ->update(['is_read' => 1]);
        return $this->response->setJSON(['success' => true]);
    }
}
