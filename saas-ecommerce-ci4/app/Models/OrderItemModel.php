<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table      = 'order_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'order_id', 'product_id', 'variant_id', 'product_name', 'product_sku',
        'quantity', 'unit_price', 'total_price', 'product_snapshot'
    ];

    protected $useTimestamps = false;

    public function getByOrder(int $orderId): array
    {
        return $this->select('order_items.*, products.image as product_image, products.slug as product_slug')
            ->join('products', 'products.id = order_items.product_id', 'left')
            ->where('order_items.order_id', $orderId)
            ->findAll();
    }

    public function insertItems(int $orderId, array $cartItems): void
    {
        foreach ($cartItems as $item) {
            $this->insert([
                'order_id'         => $orderId,
                'product_id'       => $item['product_id'],
                'product_name'     => $item['name'],
                'product_sku'      => $item['sku'] ?? null,
                'quantity'         => $item['qty'],
                'unit_price'       => $item['price'],
                'total_price'      => $item['qty'] * $item['price'],
                'product_snapshot' => json_encode($item),
            ]);
        }
    }
}
