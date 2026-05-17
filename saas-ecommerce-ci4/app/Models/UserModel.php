<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'tenant_id', 'name', 'email', 'password', 'phone', 'avatar',
        'role', 'is_active', 'email_verified_at', 'remember_token',
        'last_login_at', 'last_login_ip'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    public function findByEmail(string $email, ?int $tenantId = null)
    {
        $query = $this->where('email', $email)->where('is_active', 1);
        if ($tenantId !== null) {
            $query->where('tenant_id', $tenantId);
        }
        return $query->first();
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function updateLastLogin(int $id, string $ip)
    {
        $this->update($id, [
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => $ip,
        ]);
    }

    public function getByTenant(int $tenantId)
    {
        return $this->where('tenant_id', $tenantId)->findAll();
    }
}
