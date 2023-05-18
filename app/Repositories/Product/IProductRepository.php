<?php

namespace App\Repositories\Product;

use App\Models\Product;

interface IProductRepository
{
    /**
     * The create oa product function
     *
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product;

    /**
     * The update a product function
     *
     * @param integer $id
     * @param array $data
     * @return Product
     */
    public function update(int $id, array $data): Product;
    
    /**
     * The delete a product function
     *
     * @param integer $id
     * @return void
     */
    public function delete(int $id): void;
}