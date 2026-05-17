<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TenantModel;
use CodeIgniter\Controller;

class TeamController extends Controller
{
    protected int $tenantId;
    protected int $userId;
    protected string $userRole;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->tenantId = (int)session()->get('tenant_id');
        $this->userId = (int)session()->get('user_id');
        $this->userRole = session()->get('user_role') ?? 'member';
    }

    public function index(): string
    {
        $userModel = new UserModel();
        $tenantModel = new TenantModel();

        return view('team/index', [
            'members' => $userModel->getTeamMembers($this->tenantId),
            'plan'    => $tenantModel->getPlan($this->tenantId),
        ]);
    }

    public function invite()
    {
        if (!in_array($this->userRole, ['owner', 'admin'])) {
            return redirect()->back()->with('error', 'You do not have permission to invite team members.');
        }

        $rules = [
            'name'  => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email',
            'role'  => 'required|in_list[admin,member,viewer]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $email = $this->request->getPost('email');
        $existing = $userModel->findByEmail($email, $this->tenantId);

        if ($existing) {
            return redirect()->back()->with('error', 'A team member with that email already exists.');
        }

        $tempPassword = bin2hex(random_bytes(8));
        $userModel->insert([
            'tenant_id' => $this->tenantId,
            'name'      => $this->request->getPost('name'),
            'email'     => $email,
            'password'  => $userModel->hashPassword($tempPassword),
            'role'      => $this->request->getPost('role'),
            'status'    => 'pending',
        ]);

        // In production: send invitation email with $tempPassword
        log_message('info', "Team invitation sent to {$email}. Temp password: {$tempPassword}");

        return redirect()->back()->with('success', "Invitation sent to {$email}.");
    }

    public function remove(int $memberId)
    {
        if (!in_array($this->userRole, ['owner', 'admin'])) {
            return redirect()->back()->with('error', 'Permission denied.');
        }

        if ($memberId === $this->userId) {
            return redirect()->back()->with('error', 'You cannot remove yourself.');
        }

        $userModel = new UserModel();
        $member = $userModel->find($memberId);

        if (!$member || $member['tenant_id'] !== $this->tenantId) {
            return redirect()->back()->with('error', 'Team member not found.');
        }

        if ($member['role'] === 'owner') {
            return redirect()->back()->with('error', 'Cannot remove the organization owner.');
        }

        $userModel->delete($memberId);
        return redirect()->back()->with('success', 'Team member removed.');
    }

    public function updateRole(int $memberId)
    {
        if ($this->userRole !== 'owner') {
            return redirect()->back()->with('error', 'Only the owner can change roles.');
        }

        $role = $this->request->getPost('role');
        $validRoles = ['admin', 'member', 'viewer'];

        if (!in_array($role, $validRoles)) {
            return redirect()->back()->with('error', 'Invalid role.');
        }

        $userModel = new UserModel();
        $member = $userModel->find($memberId);

        if (!$member || $member['tenant_id'] !== $this->tenantId) {
            return redirect()->back()->with('error', 'Team member not found.');
        }

        $userModel->update($memberId, ['role' => $role]);
        return redirect()->back()->with('success', 'Role updated.');
    }
}
