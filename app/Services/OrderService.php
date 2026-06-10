<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class OrderService
{
    public function checkAndReduceStock($product, $quantity)
    {
        if ($product->stock < $quantity) {
            throw new Exception("Stock tidak cukup");
        }
        $product->decrement('stock', $quantity);
    }

    public function createOrder(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $order = Order::create([
                    'no_order' => $this->generateOrderNumber(),
                ]);
                $itemsData = [];

                foreach ($data['items'] as $item) {
                    $product = Product::where('uuid', $item['product_uuid'])->lockForUpdate()->first();

                    if (!$product) {
                        throw new Exception("Product tidak ditemukan");
                    }
                    
                    $this->checkAndReduceStock($product, $item['quantity']);
                    
                    $itemsData[] = [
                        'uuid' => Str::uuid(),
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                OrderItem::insert($itemsData);

                return response()->json([
                    'success' => true,
                    'message' => "Order berhasil dibuat",
                    'data' => $order
                ], 200);
            });
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    private function generateOrderNumber()
    {
        return 'ORD-' . now()->format('YmdHis') . '-' . Str::random(5);
    }
}
