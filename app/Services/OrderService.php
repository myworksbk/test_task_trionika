<?php

namespace App\Services;

use App\Elasticsearch\IElasticsearchIndex;
use App\Jobs\CreateOrderJob;
use App\Repositories\Order\IOrderRepository;
use App\Services\OrderElasticsearchService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{
    public const MIN_ORDER_PRICE = 100;
    private const MIN_ORDER_PRICE_ERROR_MESSAGE = "The price must be equal or more then 100";

    private IOrderRepository $orderRepository;
    private OrderElasticsearchService $orderElasticsearchService;
    private IElasticsearchIndex $orderIndex;
    
    /**
     *
     * @param IOrderRepository $orderRepository
     */
    public function __construct(
        IOrderRepository $orderRepository, 
        OrderElasticsearchService $orderElasticsearchService, 
        IElasticsearchIndex $orderIndex
    )
    {
        $this->orderRepository = $orderRepository;
        $this->orderElasticsearchService = $orderElasticsearchService;
        $this->orderIndex = $orderIndex;
    }
    
    /**
     * Get functionality for all orders as pagination
     *
     * @param integer $perPage
     * @param integer $currentPage
     * @return LengthAwarePaginator
     */
    public function getPaginateOrdersByProduct(int $perPage, int $currentPage): LengthAwarePaginator
    {
        return $this->orderElasticsearchService->aggregateOrdersByProduct($perPage, $currentPage);
    }

    /**
     * Create functionality for a order
     *
     * @param array $data
     * @return void
     */
    public function createOrder(array $data): void
    {
        $this->checkMinPriceThroughException($data['price']);

        $data['user_id'] = auth()->id();

        CreateOrderJob::dispatch($this->orderRepository, $data)->withChain([$this->orderIndex]);
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
        if ($price < self::MIN_ORDER_PRICE) {
            throw new Exception(self::MIN_ORDER_PRICE_ERROR_MESSAGE);
        }
    }
}