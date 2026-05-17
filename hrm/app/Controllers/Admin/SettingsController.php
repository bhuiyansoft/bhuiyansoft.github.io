<?php

namespace App\Controllers\Admin;

use App\Models\UserModel;
use App\Models\EmployeeModel;

class SettingsController extends BaseAdminController
{
    public function index(): string
    {
        if (!$this->hasPermission(['super_admin', 'admin'])) {
            return $this->denyAccess();
        }
        return view('settings/index', ['title' => 'System Settings']);
    }

    public function update()
    {
        // Save settings to a config file or database
        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function users(): string
    {
        if (!$this->hasPermission(['super_admin', 'admin'])) {
            return $this->denyAccess();
        }
        $userModel = new UserModel();
        $empModel  = new EmployeeModel();
        return view('settings/users', [
            'title'     => 'User Management',
            'users'     => $userModel->getUsersWithEmployee(),
            'employees' => $empModel->getActiveEmployees(),
        ]);
    }

    public function storeUser()
    {
        $userModel = new UserModel();
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel->insert([
            'employee_id' => $this->request->getPost('employee_id') ?: null,
            'username'    => $this->request->getPost('username'),
            'email'       => $this->request->getPost('email'),
            'password'    => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'        => $this->request->getPost('role') ?? 'employee',
            'is_active'   => 1,
        ]);

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function updateUser(int $id)
    {
        $userModel = new UserModel();
        $data = [
            'role'      => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $newPassword = $this->request->getPost('password');
        if ($newPassword) {
            $data['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        $userModel->update($id, $data);
        return redirect()->back()->with('success', 'User updated.');
    }

    public function deleteUser(int $id)
    {
        if ($id === session()->get('user_id')) {
            return redirect()->back()->with('error', 'Cannot delete your own account.');
        }
        $userModel = new UserModel();
        $userModel->delete($id);
        return redirect()->back()->with('success', 'User deleted.');
    }
}
