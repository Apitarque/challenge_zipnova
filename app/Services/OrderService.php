<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Address;
use App\Models\BillingInfo;
use App\Models\Product;

class OrderService{
    public function store($data): Order {
        // 1. Crear direcciones
        $shippingAddress = Address::create($data['shipping_address']);
        $billingAddress = Address::create($data['billing_info']['address']);

        // 2. Crear billing info
        $billingInfo = BillingInfo::create([
            'tax_id' => $data['billing_info']['tax_id'],
            'company_name' => $data['billing_info']['company_name'],
            'address_id' => $billingAddress->id,
        ]);

        // 3. Calcular total
        $total = 0;
        foreach ($data['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $total += $product->price * $item['quantity'];
        }

        // 4. Crear orden
        $order = Order::create([
            'user_id' => $data['user_id'],
            'shipping_address_id' => $shippingAddress->id,
            'billing_info_id' => $billingInfo->id,
            'total' => $total,
        ]);

        // 5. Crear ítems
        foreach ($data['items'] as $item) {
            $product = Product::find($item['product_id']);
            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'unit_price' => $product->price,
            ]);
        }

        return $order;
    }
}