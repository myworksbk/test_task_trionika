<?php

namespace App\Repositories\Order;

use App\Models\Order;

interface IOrderRepository
{
    /**
     * The create a order function
     *
     * @param array $data
     * @return Order
     */
    public function create(array $data): Order;

    /**
     * The update a order function
     *
     * @param integer $id
     * @param array $data
     * @return Order
     */
    public function update(int $id, array $data): Order;

    /**
     * The delete a order function
     *
     * @param integer $id
     * @return void
     */
    public function delete(int $id): void;
}