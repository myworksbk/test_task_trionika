<?php

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $statuses = [
                OrderStatusEnum::PENDING, 
                OrderStatusEnum::COMPLETED, 
                OrderStatusEnum::CANCELED,
            ];

            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->string('product_name');
            $table->foreignId('user_id')->constrained();
            $table->decimal('price', 8, 2);
            $table->integer('quantity');
            $table->enum('status', $statuses)->default(OrderStatusEnum::PENDING);
            $table->timestamps();

            $table->index('product_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
