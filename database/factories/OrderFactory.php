<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = [
            OrderStatusEnum::PENDING, 
            OrderStatusEnum::COMPLETED, 
            OrderStatusEnum::CANCELED,
        ];

        $product = Product::factory()->create();

        return [
            'price' => $product->price - $product->discount,
            'quantity' => $this->faker->randomNumber(2),
            'status' => $this->faker->randomElement($statuses),
            'product_id' => $product->id,
            'product_name' => $product->name,
            'user_id' => function() {
                return User::factory()->create()->id;
            },
        ];
    }
}
