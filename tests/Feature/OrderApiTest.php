<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Models\BillingInfo;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_fetch_own_order(): void
    {
        $user = User::factory()->create();
        $billingInfo = BillingInfo::factory()->create();
        $address = Address::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'shipping_address_id' => $address->id,
            'billing_info_id' => $billingInfo->id
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/v1/orders/{$order->id}");

        $response->assertOk()
                 ->assertJsonFragment(['id' => $order->id]);
    }

    public function test_user_cannot_fetch_other_users_order(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $billingInfo = BillingInfo::factory()->create();
        $address = Address::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $otherUser->id,
            'shipping_address_id' => $address->id,
            'billing_info_id' => $billingInfo->id
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/v1/orders/{$order->id}");

        $response->assertForbidden()
                 ->assertJson(['message' => 'No estás autorizado para realizar esta acción.']);
    }
}