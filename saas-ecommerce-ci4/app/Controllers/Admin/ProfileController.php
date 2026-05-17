<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return view('admin/profile/index', [
            'title'         => 'My Profile',
            'user'          => $this->userModel->find($this->currentUser['id']),
            'currentUser'   => $this->currentUser,
            'currentTenant' => $this->currentTenant,
        ]);
    }

    public function update()
    {
        $rules = [
            'name'  => 'required|min_length[2]|max_length[200]',
            'phone' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'  => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
        ];

        $avatar = $this->request->getFile('avatar');
        if ($avatar && $avatar->isValid()) {
            $newName    = $avatar->getRandomName();
            $uploadPath = WRITEPATH . 'uploads/avatars/';
            if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
            $avatar->move($uploadPath, $newName);
            $data['avatar'] = 'uploads/avatars/' . $newName;
        }

        $this->userModel->update($this->currentUser['id'], $data);
        session()->set('user_name', $data['name']);

        return redirect()->back()->with('success', 'Profile updated.');
    }

    public function changePassword()
    {
        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $user = $this->userModel->find($this->currentUser['id']);

        if (!$this->userModel->verifyPassword($this->request->getPost('current_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $this->userModel->update($this->currentUser['id'], [
            'password' => $this->request->getPost('new_password'),
        ]);

        return redirect()->back()->with('success', 'Password changed successfully.');
    }
}
