<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements IProductRepository
{
    private const ERROR_CREATE_MESSAGE = "Error processing create a product";

    /**
     * Get all products
     *
     * @return Collection
     */
    public function get(): Collection
    {
        return Product::get();
    }

    /**
     * Create a product
     *
     * @param array $data
     * @return Product
     * @throws \Exception
     */
    public function create(array $data): Product
    {
        if(!$data) throw new \Exception(self::ERROR_CREATE_MESSAGE);
        
        return Product::create($data);
    }

    /**
     * The update a product function
     *
     * @param integer $id
     * @param array $data
     * @return Product
     */
    public function update(int $id, array $data): Product
    {
        //
    }

    /**
     * The delete a product function
     *
     * @param integer $id
     * @return void
     */
    public function delete(int $id): void
    {
        //
    }
}