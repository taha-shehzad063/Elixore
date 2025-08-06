<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        OrderItem::truncate(); // Clear existing data

        $orderItems = [
            // Order 1 items
            [
                'order_id' => 1,
                'product_id' => 1,
                'quantity' => 1,
                'price' => 70.00,
                'created_at' => Carbon::parse('2024-01-15 10:00:00'),
            ],
            [
                'order_id' => 1,
                'product_id' => 2,
                'quantity' => 1,
                'price' => 80.00,
                'created_at' => Carbon::parse('2024-01-15 10:00:00'),
            ],
            
            // Order 2 items
            [
                'order_id' => 2,
                'product_id' => 3,
                'quantity' => 2,
                'price' => 90.00,
                'created_at' => Carbon::parse('2024-02-20 11:30:00'),
            ],
            [
                'order_id' => 2,
                'product_id' => 4,
                'quantity' => 1,
                'price' => 45.50,
                'created_at' => Carbon::parse('2024-02-20 11:30:00'),
            ],
            
            // Order 3 items
            [
                'order_id' => 3,
                'product_id' => 5,
                'quantity' => 1,
                'price' => 89.99,
                'created_at' => Carbon::parse('2024-03-05 09:15:00'),
            ],
            
            // Order 4 items (pending)
            [
                'order_id' => 4,
                'product_id' => 6,
                'quantity' => 2,
                'price' => 120.00,
                'created_at' => Carbon::parse('2024-03-15 14:45:00'),
            ],
            [
                'order_id' => 4,
                'product_id' => 7,
                'quantity' => 2,
                'price' => 80.75,
                'created_at' => Carbon::parse('2024-03-15 14:45:00'),
            ],
            
            // Order 5 items (cancelled)
            [
                'order_id' => 5,
                'product_id' => 8,
                'quantity' => 1,
                'price' => 90.00,
                'created_at' => Carbon::parse('2024-03-01 08:30:00'),
            ],
            [
                'order_id' => 5,
                'product_id' => 9,
                'quantity' => 1,
                'price' => 85.25,
                'created_at' => Carbon::parse('2024-03-01 08:30:00'),
            ],
        ];

        foreach ($orderItems as $item) {
            OrderItem::create($item);
        }
    }
}