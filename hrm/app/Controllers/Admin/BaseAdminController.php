<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;

class BaseAdminController extends Controller
{
    protected $helpers = ['form', 'url', 'text', 'filesystem'];

    public function __construct()
    {
        helper($this->helpers);
    }

    protected function hasPermission(array $roles): bool
    {
        return in_array(session()->get('role'), $roles);
    }

    protected function denyAccess()
    {
        return redirect()->to(base_url('admin/dashboard'))->with('error', 'You do not have permission to perform this action.');
    }

    protected function uploadPhoto(string $inputName, string $directory = 'employees'): ?string
    {
        $file = $this->request->getFile($inputName);
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return null;
        }

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array(strtolower($file->getExtension()), $allowed)) {
            return null;
        }

        $newName  = $file->getRandomName();
        $savePath = WRITEPATH . 'uploads/' . $directory . '/';

        if (!is_dir($savePath)) {
            mkdir($savePath, 0755, true);
        }

        $file->move($savePath, $newName);
        return $directory . '/' . $newName;
    }
}
