<?php

namespace App\Http\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\AddressResource;
use App\Http\Resources\BillingInfoResource;
use App\Http\Resources\ItemResource;

class OrderCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection->transform(function ($order) {
                return [
                    'id' => $order->id,
                    'total' => $order->total,
                    'user' => [
                        'name' => $order->user->name,
                        'email' => $order->user->email,
                    ],
                    'shipping_address' => new AddressResource($order->shippingAddress),
                    'billing_info' => new BillingInfoResource($order->billingInfo),
                    'items' => ItemResource::collection($order->items),
                    'created_at' => $order->created_at->toDateTimeString(),
                ];
            }),
            'meta' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
            ],
        ];
    }
}
