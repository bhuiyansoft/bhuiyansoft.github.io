<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'employee_id', 'username', 'email', 'password',
        'role', 'is_active', 'last_login', 'remember_token',
        'reset_token', 'reset_expires'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[60]|is_unique[users.username,id,{id}]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
    ];

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    public function findByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }

    public function findByCredential(string $credential): ?array
    {
        return $this->groupStart()
            ->where('email', $credential)
            ->orWhere('username', $credential)
            ->groupEnd()
            ->first();
    }

    public function getUsersWithEmployee(): array
    {
        return $this->select('users.*, CONCAT(e.first_name," ",e.last_name) as full_name, e.photo')
            ->join('employees e', 'e.id = users.employee_id', 'left')
            ->findAll();
    }
}
