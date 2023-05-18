<?php

namespace Database\Seeders;

use App\Elasticsearch\OrderIndex;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            Order::factory(10)->create()->each(
                fn($order) => app(OrderIndex::class)->index(
                    $order->toArray()
                )
            );
        });
    }
}
