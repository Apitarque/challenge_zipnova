<?php

namespace Tests\Unit\Policies;

use App\Models\Order;
use App\Models\User;
use App\Models\BillingInfo;
use App\Models\Address;
use App\Policies\OrderPolicy;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_own_order(): void
    {
        $user = User::factory()->create();
        $billingInfo = BillingInfo::factory()->create();
        $address = Address::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'shipping_address_id' => $address->id,
            'billing_info_id' => $billingInfo->id
        ]);

        $policy = new OrderPolicy();

        $this->assertTrue($policy->show($user, $order));
    }

    public function test_user_cannot_view_other_users_order(): void
    {
        $user = User::factory()->create(['id' => 1]);
        $billingInfo = BillingInfo::factory()->create();
        $address = Address::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id++, //le agrego un valor para simular otro id
            'shipping_address_id' => $address->id,
            'billing_info_id' => $billingInfo->id
        ]);

        $policy = new OrderPolicy();

        $this->assertFalse($policy->show($user, $order));
    }
}