<?php

namespace App\Models;

use CodeIgniter\Model;

class CouponModel extends Model
{
    protected $table      = 'coupons';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'tenant_id', 'code', 'type', 'value', 'min_order',
        'max_uses', 'used_count', 'expires_at', 'is_active'
    ];

    protected $useTimestamps = false;

    public function getByCode(int $tenantId, string $code)
    {
        return $this->where('tenant_id', $tenantId)
            ->where('code', strtoupper($code))
            ->where('is_active', 1)
            ->first();
    }

    public function validate(int $tenantId, string $code, float $orderTotal): array
    {
        $coupon = $this->getByCode($tenantId, $code);

        if (!$coupon) {
            return ['valid' => false, 'message' => 'Invalid coupon code.'];
        }
        if ($coupon['expires_at'] && strtotime($coupon['expires_at']) < time()) {
            return ['valid' => false, 'message' => 'This coupon has expired.'];
        }
        if ($coupon['max_uses'] && $coupon['used_count'] >= $coupon['max_uses']) {
            return ['valid' => false, 'message' => 'This coupon has reached its usage limit.'];
        }
        if ($orderTotal < $coupon['min_order']) {
            return ['valid' => false, 'message' => "Minimum order amount is {$coupon['min_order']} to use this coupon."];
        }

        $discount = $coupon['type'] === 'percentage'
            ? ($orderTotal * $coupon['value'] / 100)
            : $coupon['value'];

        return ['valid' => true, 'coupon' => $coupon, 'discount' => round($discount, 2)];
    }

    public function incrementUsage(int $id): void
    {
        $this->db->query("UPDATE coupons SET used_count = used_count + 1 WHERE id = ?", [$id]);
    }

    public function getByTenant(int $tenantId)
    {
        return $this->where('tenant_id', $tenantId)->orderBy('created_at', 'DESC')->findAll();
    }
}
