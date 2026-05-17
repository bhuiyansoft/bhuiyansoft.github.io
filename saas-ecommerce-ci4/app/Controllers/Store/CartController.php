<?php

namespace App\Controllers\Store;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CouponModel;

class CartController extends BaseController
{
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    protected function getTenantId(): int
    {
        // In production: resolve from subdomain or session
        return (int)(session()->get('store_tenant_id') ?? 1);
    }

    public function index()
    {
        $cart     = session()->get('cart') ?? [];
        $subtotal = $this->calculateSubtotal($cart);
        $tenantId = $this->getTenantId();

        $tenant = (new \App\Models\TenantModel())->find($tenantId);

        $tax      = round($subtotal * ($tenant['tax_rate'] / 100), 2);
        $shipping = $subtotal > 0 ? ($tenant['shipping_rate'] ?? 0) : 0;
        $discount = session()->get('cart_discount') ?? 0;
        $total    = $subtotal + $tax + $shipping - $discount;

        return view('store/cart', [
            'title'    => 'Shopping Cart',
            'cart'     => $cart,
            'subtotal' => $subtotal,
            'tax'      => $tax,
            'shipping' => $shipping,
            'discount' => $discount,
            'total'    => $total,
            'coupon'   => session()->get('cart_coupon'),
            'tenant'   => $tenant,
        ]);
    }

    public function add()
    {
        $tenantId  = $this->getTenantId();
        $productId = (int)$this->request->getPost('product_id');
        $qty       = max(1, (int)$this->request->getPost('qty'));

        $product = $this->productModel->where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->find($productId);

        if (!$product) {
            return $this->jsonResponse(['success' => false, 'message' => 'Product not found.']);
        }

        if ($product['stock'] < $qty) {
            return $this->jsonResponse(['success' => false, 'message' => 'Insufficient stock.']);
        }

        $cart = session()->get('cart') ?? [];
        $price = $product['sale_price'] ?? $product['price'];

        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] = min($cart[$productId]['qty'] + $qty, $product['stock']);
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'name'       => $product['name'],
                'sku'        => $product['sku'],
                'price'      => $price,
                'image'      => $product['image'],
                'slug'       => $product['slug'],
                'qty'        => $qty,
                'stock'      => $product['stock'],
            ];
        }

        session()->set('cart', $cart);

        return $this->jsonResponse([
            'success'    => true,
            'message'    => 'Added to cart!',
            'cart_count' => array_sum(array_column($cart, 'qty')),
        ]);
    }

    public function update()
    {
        $cart  = session()->get('cart') ?? [];
        $items = $this->request->getPost('qty') ?? [];

        foreach ($items as $productId => $qty) {
            $qty = (int)$qty;
            if ($qty <= 0) {
                unset($cart[$productId]);
            } elseif (isset($cart[$productId])) {
                $cart[$productId]['qty'] = min($qty, $cart[$productId]['stock']);
            }
        }

        session()->set('cart', $cart);

        // Re-validate coupon
        if ($couponCode = session()->get('cart_coupon')) {
            $tenantId    = $this->getTenantId();
            $couponModel = new CouponModel();
            $subtotal    = $this->calculateSubtotal($cart);
            $result      = $couponModel->validate($tenantId, $couponCode, $subtotal);

            if (!$result['valid']) {
                session()->remove('cart_coupon');
                session()->remove('cart_discount');
            } else {
                session()->set('cart_discount', $result['discount']);
            }
        }

        return redirect()->to('/shop/cart')->with('success', 'Cart updated.');
    }

    public function remove(int $productId)
    {
        $cart = session()->get('cart') ?? [];
        unset($cart[$productId]);
        session()->set('cart', $cart);
        return redirect()->to('/shop/cart')->with('success', 'Item removed.');
    }

    private function calculateSubtotal(array $cart): float
    {
        return array_reduce($cart, fn($carry, $item) => $carry + ($item['price'] * $item['qty']), 0);
    }
}
