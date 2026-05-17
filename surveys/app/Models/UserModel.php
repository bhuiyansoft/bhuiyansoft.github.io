<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'tenant_id', 'name', 'email', 'password', 'role', 'avatar',
        'status', 'email_verified_at', 'reset_token', 'reset_token_expires',
        'last_login_at', 'last_login_ip', 'preferences',
    ];
    protected $useTimestamps = true;
    protected $hiddenFields = ['password', 'reset_token'];

    protected $validationRules = [
        'name'  => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|max_length[150]',
    ];

    public function findByEmail(string $email, int $tenantId): ?array
    {
        return $this->where('email', $email)
            ->where('tenant_id', $tenantId)
            ->first();
    }

    public function findByEmailAnyTenant(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    public function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    public function hashPassword(string $plain): string
    {
        return password_hash($plain, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public function getTeamMembers(int $tenantId): array
    {
        return $this->where('tenant_id', $tenantId)
            ->orderBy('role', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    public function updateLastLogin(int $userId, string $ip): void
    {
        $this->update($userId, [
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => $ip,
        ]);
    }
}
