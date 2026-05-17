<?php

namespace App\Controllers\Admin;

use App\Models\EmployeeModel;
use App\Models\UserModel;

class ProfileController extends BaseAdminController
{
    public function index(): string
    {
        $empModel  = new EmployeeModel();
        $employee  = $empModel->getWithDetails(session()->get('employee_id'));
        return view('profile/index', ['title' => 'My Profile', 'employee' => $employee]);
    }

    public function update()
    {
        $empModel = new EmployeeModel();
        $empId    = session()->get('employee_id');
        $employee = $empModel->find($empId);

        $photo = $this->uploadPhoto('photo', 'employees') ?? $employee['photo'];

        $empModel->update($empId, [
            'phone'   => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'city'    => $this->request->getPost('city'),
            'state'   => $this->request->getPost('state'),
            'emergency_contact_name'  => $this->request->getPost('emergency_contact_name'),
            'emergency_contact_phone' => $this->request->getPost('emergency_contact_phone'),
            'photo'   => $photo,
        ]);

        session()->set('photo', $photo);
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword()
    {
        $userModel   = new UserModel();
        $userId      = session()->get('user_id');
        $user        = $userModel->find($userId);
        $current     = $this->request->getPost('current_password');
        $new         = $this->request->getPost('new_password');
        $confirm     = $this->request->getPost('confirm_password');

        if (!password_verify($current, $user['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        if ($new !== $confirm) {
            return redirect()->back()->with('error', 'New passwords do not match.');
        }

        if (strlen($new) < 8) {
            return redirect()->back()->with('error', 'Password must be at least 8 characters.');
        }

        $userModel->update($userId, ['password' => password_hash($new, PASSWORD_BCRYPT)]);
        return redirect()->back()->with('success', 'Password changed successfully.');
    }
}
