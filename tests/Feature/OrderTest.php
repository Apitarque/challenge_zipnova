<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use App\Models\User;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_orders()
    {
        $user = User::factory()->create();

        // Autenticación con Sanctum
        $this->actingAs($user, 'sanctum');

        Order::factory()->count(5)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/v1/orders');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data']);
    }
}
