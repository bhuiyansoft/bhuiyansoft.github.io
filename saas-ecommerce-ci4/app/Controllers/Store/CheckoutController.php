<?php

namespace App\Controllers\Store;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\TenantModel;
use App\Models\CouponModel;

class CheckoutController extends BaseController
{
    protected function getTenantId(): int
    {
        return (int)(session()->get('store_tenant_id') ?? 1);
    }

    public function index()
    {
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to('/shop/cart')->with('error', 'Your cart is empty.');
        }

        $tenantId = $this->getTenantId();
        $tenant   = (new TenantModel())->find($tenantId);
        $subtotal = array_reduce($cart, fn($c, $i) => $c + ($i['price'] * $i['qty']), 0);
        $tax      = round($subtotal * ($tenant['tax_rate'] / 100), 2);
        $shipping = $tenant['shipping_rate'] ?? 0;
        $discount = session()->get('cart_discount') ?? 0;
        $total    = $subtotal + $tax + $shipping - $discount;

        return view('store/checkout', [
            'title'    => 'Checkout',
            'cart'     => $cart,
            'subtotal' => $subtotal,
            'tax'      => $tax,
            'shipping' => $shipping,
            'discount' => $discount,
            'total'    => $total,
            'tenant'   => $tenant,
        ]);
    }

    public function placeOrder()
    {
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to('/shop/cart')->with('error', 'Your cart is empty.');
        }

        $rules = [
            'customer_name'    => 'required|min_length[2]',
            'customer_email'   => 'required|valid_email',
            'customer_phone'   => 'required',
            'shipping_address' => 'required',
            'shipping_city'    => 'required',
            'payment_method'   => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tenantId      = $this->getTenantId();
        $tenantModel   = new TenantModel();
        $tenant        = $tenantModel->find($tenantId);
        $customerModel = new CustomerModel();
        $orderModel    = new OrderModel();
        $itemModel     = new OrderItemModel();
        $productModel  = new ProductModel();

        $db = db_connect();
        $db->transStart();

        // Upsert customer
        $email    = $this->request->getPost('customer_email');
        $customer = $customerModel->findByEmail($tenantId, $email);
        if (!$customer) {
            $customerId = $customerModel->insert([
                'tenant_id' => $tenantId,
                'name'      => $this->request->getPost('customer_name'),
                'email'     => $email,
                'phone'     => $this->request->getPost('customer_phone'),
                'city'      => $this->request->getPost('shipping_city'),
                'country'   => $this->request->getPost('shipping_country') ?? 'Bangladesh',
                'is_active' => 1,
            ]);
        } else {
            $customerId = $customer['id'];
        }

        $subtotal = array_reduce($cart, fn($c, $i) => $c + ($i['price'] * $i['qty']), 0);
        $tax      = round($subtotal * ($tenant['tax_rate'] / 100), 2);
        $shipping = $tenant['shipping_rate'] ?? 0;
        $discount = session()->get('cart_discount') ?? 0;
        $total    = $subtotal + $tax + $shipping - $discount;

        $orderId = $orderModel->insert([
            'tenant_id'        => $tenantId,
            'customer_id'      => $customerId,
            'order_number'     => $orderModel->generateOrderNumber($tenantId),
            'status'           => 'pending',
            'payment_status'   => 'unpaid',
            'payment_method'   => $this->request->getPost('payment_method'),
            'subtotal'         => $subtotal,
            'tax_amount'       => $tax,
            'shipping_amount'  => $shipping,
            'discount_amount'  => $discount,
            'total'            => $total,
            'shipping_name'    => $this->request->getPost('customer_name'),
            'shipping_email'   => $email,
            'shipping_phone'   => $this->request->getPost('customer_phone'),
            'shipping_address' => $this->request->getPost('shipping_address'),
            'shipping_city'    => $this->request->getPost('shipping_city'),
            'shipping_state'   => $this->request->getPost('shipping_state'),
            'shipping_country' => $this->request->getPost('shipping_country') ?? 'Bangladesh',
            'shipping_postal'  => $this->request->getPost('shipping_postal'),
            'billing_name'     => $this->request->getPost('customer_name'),
            'notes'            => $this->request->getPost('notes'),
        ]);

        $itemModel->insertItems($orderId, $cart);

        // Deduct stock
        foreach ($cart as $item) {
            $db->query("UPDATE products SET stock = stock - ?, total_sold = total_sold + ? WHERE id = ? AND tenant_id = ?",
                [$item['qty'], $item['qty'], $item['product_id'], $tenantId]);
        }

        // Update customer stats
        $customerModel->updateOrderStats($customerId, $total);

        // Mark coupon used
        if ($couponId = session()->get('cart_coupon_id')) {
            (new CouponModel())->incrementUsage($couponId);
        }

        $db->transComplete();

        if ($db->transStatus()) {
            $orderNumber = $orderModel->find($orderId)['order_number'];
            session()->remove('cart');
            session()->remove('cart_coupon');
            session()->remove('cart_coupon_id');
            session()->remove('cart_discount');

            return redirect()->to("/shop/order/success/{$orderNumber}");
        }

        return redirect()->back()->with('error', 'Order placement failed. Please try again.');
    }

    public function success(string $orderNumber)
    {
        $tenantId   = $this->getTenantId();
        $orderModel = new OrderModel();
        $order      = $orderModel->where('tenant_id', $tenantId)
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            return redirect()->to('/shop');
        }

        $itemModel = new OrderItemModel();
        return view('store/order_success', [
            'title' => 'Order Confirmed!',
            'order' => $order,
            'items' => $itemModel->getByOrder($order['id']),
        ]);
    }
}
