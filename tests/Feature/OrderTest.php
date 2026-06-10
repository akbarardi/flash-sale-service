<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_order_success()
    {
        $product1 = Product::factory()->create(['stock' => 10]);
        $product2 = Product::factory()->create(['stock' => 5]);

        $payload = [
            'items' => [
                [
                    'product_uuid' => $product1->uuid,
                    'quantity' => 2
                ],
                [
                    'product_uuid' => $product2->uuid,
                    'quantity' => 1
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/orders', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Order berhasil dibuat'
            ])
            ->assertJsonStructure([
                'data' => [
                    'uuid',
                    'no_order',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseCount('orders', 1);

        $this->assertDatabaseCount('order_items', 2);

        $this->assertEquals(8, $product1->fresh()->stock);
        $this->assertEquals(4, $product2->fresh()->stock);
    }

    public function test_race_condition_prevent_overselling()
    {
        $product = Product::factory()->create([
            'stock' => 1
        ]);

        $payload = [
            'items' => [
                [
                    'product_uuid' => $product->uuid,
                    'quantity' => 1
                ]
            ]
        ];

        $response1 = $this->postJson('/api/v1/orders', $payload);

        $response2 = $this->postJson('/api/v1/orders', $payload);

        $successCount = collect([$response1, $response2])
            ->filter(fn($res) => $res->json('success') == true)
            ->count();

        $this->assertEquals(1, $successCount);

        $this->assertEquals(0, $product->fresh()->stock);

        $this->assertDatabaseCount('orders', 1);
    }
}
