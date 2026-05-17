<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CouponModel;

class CartController extends BaseController
{
    protected ProductModel $productModel;
    protected CouponModel  $couponModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request,
                                   \CodeIgniter\HTTP\ResponseInterface $response,
                                   \Psr\Log\LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);
        $this->productModel = new ProductModel();
        $this->couponModel  = new CouponModel();
    }

    private function getTenantId(): int
    {
        return (int) ($this->request->getHeaderLine('X-Tenant-ID') ?: session('store_tenant_id') ?: 1);
    }

    private function getCart(): array
    {
        return session('cart') ?? [];
    }

    private function saveCart(array $cart): void
    {
        session()->set('cart', $cart);
    }

    public function index(): \CodeIgniter\HTTP\ResponseInterface
    {
        $tenantId = $this->getTenantId();
        $cart     = $this->getCart();
        $items    = $this->buildCartItems($cart, $tenantId);

        return $this->jsonResponse([
            'data'       => $items,
            'cart_count' => array_sum(array_column($items, 'qty')),
            'subtotal'   => array_sum(array_map(fn($i) => $i['line_total'], $items)),
        ]);
    }

    public function add(): \CodeIgniter\HTTP\ResponseInterface
    {
        $tenantId  = $this->getTenantId();
        $input     = $this->request->getJSON(true) ?? $this->request->getPost();
        $productId = (int) ($input['product_id'] ?? 0);
        $qty       = max(1, (int) ($input['qty'] ?? 1));

        $product = $this->productModel
            ->where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->find($productId);

        if (!$product) {
            return $this->jsonResponse(['message' => 'Product not found'], 404);
        }
        if ($product['stock'] < 1) {
            return $this->jsonResponse(['message' => 'Out of stock'], 422);
        }

        $cart = $this->getCart();
        $current = (int) ($cart[$productId]['qty'] ?? 0);
        $newQty  = min($current + $qty, $product['stock']);
        $cart[$productId] = ['qty' => $newQty, 'product_id' => $productId];
        $this->saveCart($cart);

        return $this->jsonResponse([
            'message'    => 'Added to cart',
            'cart_count' => array_sum(array_column($cart, 'qty')),
        ]);
    }

    public function update(): \CodeIgniter\HTTP\ResponseInterface
    {
        $tenantId  = $this->getTenantId();
        $input     = $this->request->getJSON(true) ?? $this->request->getPost();
        $productId = (int) ($input['product_id'] ?? 0);
        $qty       = (int) ($input['qty'] ?? 0);

        $cart = $this->getCart();

        if ($qty <= 0) {
            unset($cart[$productId]);
        } else {
            $product = $this->productModel
                ->where('tenant_id', $tenantId)
                ->find($productId);
            if ($product) {
                $cart[$productId] = ['qty' => min($qty, $product['stock']), 'product_id' => $productId];
            }
        }

        $this->saveCart($cart);
        $items = $this->buildCartItems($cart, $tenantId);

        return $this->jsonResponse([
            'data'       => $items,
            'cart_count' => array_sum(array_column($cart, 'qty')),
            'subtotal'   => array_sum(array_map(fn($i) => $i['line_total'], $items)),
        ]);
    }

    public function remove(): \CodeIgniter\HTTP\ResponseInterface
    {
        $input     = $this->request->getJSON(true) ?? $this->request->getPost();
        $productId = (int) ($input['product_id'] ?? 0);

        $cart = $this->getCart();
        unset($cart[$productId]);
        $this->saveCart($cart);

        return $this->jsonResponse([
            'message'    => 'Item removed',
            'cart_count' => array_sum(array_column($cart, 'qty')),
        ]);
    }

    public function clear(): \CodeIgniter\HTTP\ResponseInterface
    {
        $this->saveCart([]);
        return $this->jsonResponse(['message' => 'Cart cleared', 'cart_count' => 0]);
    }

    public function validateCoupon(): \CodeIgniter\HTTP\ResponseInterface
    {
        $tenantId = $this->getTenantId();
        $input    = $this->request->getJSON(true) ?? $this->request->getPost();
        $code     = strtoupper(trim($input['code'] ?? ''));

        if (!$code) {
            return $this->jsonResponse(['valid' => false, 'message' => 'Coupon code required'], 422);
        }

        $cart     = $this->getCart();
        $items    = $this->buildCartItems($cart, $tenantId);
        $subtotal = array_sum(array_map(fn($i) => $i['line_total'], $items));

        $coupon = $this->couponModel
            ->where('tenant_id', $tenantId)
            ->where('code', $code)
            ->where('is_active', 1)
            ->first();

        if (!$coupon) {
            return $this->jsonResponse(['valid' => false, 'message' => 'Invalid coupon code']);
        }

        $result = $this->couponModel->validate($coupon, $subtotal);

        if (!$result['valid']) {
            return $this->jsonResponse(['valid' => false, 'message' => $result['message']]);
        }

        return $this->jsonResponse([
            'valid'         => true,
            'discount'      => $result['discount'],
            'type'          => $coupon['discount_type'],
            'value'         => $coupon['discount_value'],
            'message'       => 'Coupon applied successfully',
        ]);
    }

    private function buildCartItems(array $cart, int $tenantId): array
    {
        if (empty($cart)) {
            return [];
        }

        $productIds = array_keys($cart);
        $products   = $this->productModel
            ->where('tenant_id', $tenantId)
            ->whereIn('id', $productIds)
            ->findAll();

        $indexed = [];
        foreach ($products as $p) {
            $indexed[$p['id']] = $p;
        }

        $items = [];
        foreach ($cart as $productId => $row) {
            if (!isset($indexed[$productId])) {
                continue;
            }
            $p     = $indexed[$productId];
            $price = (float) ($p['sale_price'] ?: $p['price']);
            $qty   = (int) $row['qty'];
            $items[] = [
                'product_id' => $productId,
                'name'       => $p['name'],
                'slug'       => $p['slug'],
                'image_url'  => $p['image'] ? base_url($p['image']) : null,
                'price'      => $price,
                'qty'        => $qty,
                'line_total' => $price * $qty,
                'stock'      => (int) $p['stock'],
            ];
        }

        return $items;
    }
}
