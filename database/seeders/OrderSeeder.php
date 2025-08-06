<?php

namespace Database\Seeders;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        Order::truncate(); // Clear existing data

        $orders = [
            // Delivered orders (will show in revenue chart)
            [
                'user_id' => 1,
                'shipping_address_id' => 1,
                'billing_address_id' => 1,
                'shipping_method' => 'standard',
                'payment_method' => 'credit_card',
                'total' => 150.00,
                'total_quantity' => 2,
                'shipping_cost' => 10.00,
                'status' => 'delivered',
                'created_at' => Carbon::parse('2024-01-15 10:00:00'),
                'updated_at' => Carbon::parse('2024-01-20 12:00:00'),
            ],
            [
                'user_id' => 1,
                'shipping_address_id' => 1,
                'billing_address_id' => 1,
                'shipping_method' => 'express',
                'payment_method' => 'paypal',
                'total' => 225.50,
                'total_quantity' => 3,
                'shipping_cost' => 15.00,
                'status' => 'delivered',
                'created_at' => Carbon::parse('2024-02-20 11:30:00'),
                'updated_at' => Carbon::parse('2024-02-25 14:00:00'),
            ],
            [
                'user_id' => 2,
                'shipping_address_id' => 2,
                'billing_address_id' => 2,
                'shipping_method' => 'standard',
                'payment_method' => 'credit_card',
                'total' => 89.99,
                'total_quantity' => 1,
                'shipping_cost' => 5.00,
                'status' => 'delivered',
                'created_at' => Carbon::parse('2024-03-05 09:15:00'),
                'updated_at' => Carbon::parse('2024-03-10 10:30:00'),
            ],
            // Pending order
            [
                'user_id' => 3,
                'shipping_address_id' => 3,
                'billing_address_id' => 3,
                'shipping_method' => 'express',
                'payment_method' => 'paypal',
                'total' => 320.75,
                'total_quantity' => 4,
                'shipping_cost' => 20.00,
                'status' => 'pending',
                'created_at' => Carbon::parse('2024-03-15 14:45:00'),
                'updated_at' => Carbon::parse('2024-03-15 14:45:00'),
            ],
            // Cancelled order
            [
                'user_id' => 1,
                'shipping_address_id' => 1,
                'billing_address_id' => 1,
                'shipping_method' => 'standard',
                'payment_method' => 'credit_card',
                'total' => 175.25,
                'total_quantity' => 2,
                'shipping_cost' => 10.00,
                'status' => 'cancelled',
                'created_at' => Carbon::parse('2024-03-01 08:30:00'),
                'updated_at' => Carbon::parse('2024-03-02 09:00:00'),
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }
    }
}