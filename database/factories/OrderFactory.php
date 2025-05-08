<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Address;
use App\Models\BillingInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'shipping_address_id' => Address::factory(),
            'billing_info_id' => BillingInfo::factory(),
            'total' => $this->faker->randomFloat(2, 1000, 10000),
        ];
    }
}
