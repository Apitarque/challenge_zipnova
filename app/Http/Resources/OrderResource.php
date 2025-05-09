<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'total' => $this->total,
            'user' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'shipping_address' => new AddressResource($this->shippingAddress),
            'billing_info' => new BillingInfoResource($this->billingInfo),
            'items' => ItemResource::collection($this->items),
            'created_at' => $this->created_at,
        ];
    }
}
