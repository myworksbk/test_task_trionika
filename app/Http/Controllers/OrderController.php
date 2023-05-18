<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    private const CREATED_ORDER_MESSAGE = 'A order creation initiated';
    private const PRODUCTS_PER_PAGE = 50;
    private OrderService $orderService;

    /**
     *
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the orders
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $orders = $this->orderService->getPaginateOrdersByProduct(
            self::PRODUCTS_PER_PAGE, 
            request('page', 1)
        );
        
        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created order in storage
     *
     * @param StoreOrderRequest $request
     * @return JsonResponse
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $this->orderService->createOrder($request->all());
        
        return response()->json(['message' => self::CREATED_ORDER_MESSAGE]);
    }
}
