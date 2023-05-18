<?php

namespace App\Services;

use App\Repositories\Product\IProductRepository;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public const MIN_PRODUCT_PRICE = 200;
    private const MIN_PRODUCT_PRICE_ERROR_MESSAGE = "Price must be equal or more then 200";
    private const MAX_PRODUCT_DISCOUNT_ERROR_MESSAGE = "Price with discount must be equal or more then 100";

    private IProductRepository $productRepository;
    
    /**
     *
     * @param IProductRepository $productRepository
     */
    public function __construct(IProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    
    /**
     * Get functionality for all products
     *
     * @param array $data
     * @return Collection
     */
    public function getAllProducts(): Collection
    {
        return $this->productRepository->get();
    }
    
    /**
     * Create functionality for a product
     *
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        $this->validatePriceAndDiscount($data['price'], $data['discount']);
        
        return $this->productRepository->create($data);
    }

    /**
     * Validate min price and max discount values
     *
     * @param float $price
     * @param float $discount
     * @return void
     * @throws Exception
     */
    private function validatePriceAndDiscount(float $price, float $discount): void
    {
        $this->checkMinPriceThroughException($price);
        $this->checkMaxDiscountThroughException($price, $discount);
    }

    /**
     * Check min value of price constraint
     *
     * @param float $price
     * @return void
     * @throws Exception
     */
    private function checkMinPriceThroughException(float $price): void
    {
        if ($price < self::MIN_PRODUCT_PRICE) {
            throw new Exception(self::MIN_PRODUCT_PRICE_ERROR_MESSAGE);
        }
    }

    /**
     * Check max value of discount constraint
     *
     * @param float $price
     * @param float $discount
     * @return void
     * @throws Exception
     */
    private function checkMaxDiscountThroughException(float $price, float $discount): void
    {
        $newPrice = $price - $discount;

        if ($newPrice < OrderService::MIN_ORDER_PRICE) {
            throw new Exception(self::MAX_PRODUCT_DISCOUNT_ERROR_MESSAGE);
        }
    }
}