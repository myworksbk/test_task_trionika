<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository implements IOrderRepository
{
    private const ERROR_CREATE_MESSAGE = "Error processing create a order";

    /**
     * Get all orders
     *
     * @return Collection
     */
    public function get(): Collection
    {
        return Order::auth()->get();
    }

    /**
     * Create a order
     *
     * @param array $data
     * @return Order
     * @throws \Exception
     */
    public function create(array $data): Order
    {
        if(!$data) throw new Exception(self::ERROR_CREATE_MESSAGE);

        $product = Product::find($data['product_id']);
        
        $data['product_name'] = $product->name;
        
        return Order::create($data);
    }

    /**
     * The update a order function
     *
     * @param integer $id
     * @param array $data
     * @return Order
     */
    public function update(int $id, array $data): Order
    {
        //
    }

    /**
     * The delete a order function
     *
     * @param integer $id
     * @return void
     */
    public function delete(int $id): void
    {
        //
    }
}