<?php

namespace App\Jobs;

use App\Elasticsearch\OrderIndex;
use App\Repositories\Order\IOrderRepository;
use App\Repositories\Order\OrderRepository;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;
    private IOrderRepository $orderRepository;

    /**
     * Create a new job instance.
     *
     * @param IOrderRepository $orderRepository
     * @param array $data
     */
    public function __construct(IOrderRepository $orderRepository, array $data)
    {
        $this->data = $data;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Execute creating a order job.
     *
     * @param OrderIndex|null $orderIndex
     * @return void
     */
    public function handle(?OrderIndex $orderIndex): void
    {
        try {
            DB::beginTransaction();

            $this->createOrderAndIndex($orderIndex);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            Log::error($e->getMessage());
        } 
    }

    /**
     * Create a order and add to elasticsearch order index
     *
     * @param OrderIndex|null $orderIndex
     * @return void
     */
    private function createOrderAndIndex(?OrderIndex $orderIndex): void
    {
        $order = $this->orderRepository->create($this->data);

        if ($orderIndex) {
            $orderIndex->index($order->toArray());
        }
    }
}
