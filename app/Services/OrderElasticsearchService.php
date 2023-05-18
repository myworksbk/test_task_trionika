<?php

namespace App\Services;

use App\Elasticsearch\OrderIndex;
use Elastic\Elasticsearch\Client;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;

class OrderElasticsearchService
{
    protected $client;

    /**
     *
     * @param Client $elasticsearch
     */
    public function __construct(Client $elasticsearch)
    {
        $this->client = $elasticsearch;
    }

    /**
     * Get aggregated orders by product
     *
     * @param integer $perPage
     * @param integer $page
     * @return LengthAwarePaginator
     */
    public function aggregateOrdersByProduct(int $perPage, int $currentPage): LengthAwarePaginator
    {
        try {
            $params = $this->getRequestParams($currentPage, $perPage);

            $response = $this->client->search($params);

            $totalCount = $response['hits']['total']['value'];
            $products = $response['aggregations']['products']['buckets'];
            
            $results = $this->format($products);

            return $this->paginate($results, $totalCount, $perPage, $currentPage);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        
        return $this->paginate([], $totalCount = 0, $perPage, $currentPage);
    }

    /**
     * Format the response data
     *
     * @param array $data
     * @return array
     */
    public function format(array $data): array
    {
        $results = [];

        foreach ($data as $productsById) {
            $product['product_id'] = $productsById['key'];

            foreach ($productsById['product_names']['buckets'] as $productByName) {
                $product['product_name'] = $productByName['key'];
                $product['total_price'] = $productByName['total_price']['value'];
                $product['total_quantity'] = $productByName['total_quantity']['value'];

                $results[] = $product;
            }
        }

        return $results;
    }

    /**
     * Create a new LengthAwarePaginator instance
     *
     * @param array $data
     * @param integer $total
     * @param integer $perPage
     * @param integer $page
     * @return LengthAwarePaginator
     */
    private function paginate(array $data, int $total, int $perPage, int $page): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $data,
            $total,
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );
    }

    /**
     * Get params for elasticsearch request
     *
     * @param integer $currentPage
     * @param integer $perPage
     * @return array
     */
    private function getRequestParams(int $currentPage, int $perPage): array
    {
        $offset = ($currentPage - 1) * $perPage;
        
        return [
            'index' => OrderIndex::getIndexName(),
            'body' => [
                'size' => 0,
                'from' => $offset,
                '_source' => ['name'],
                'aggs' => [
                    'products' => [
                        'terms' => [
                            'field' => 'product_id',
                            'size' => $perPage
                        ],
                        'aggs' => [
                            'product_names' => [
                                'terms' => [
                                    'field' => 'product_name.keyword',
                                    'size' => $perPage,
                                ],
                                'aggs' => [
                                    'total_quantity' => [
                                        'sum' => [
                                            'field' => 'quantity'
                                        ]
                                    ],
                                    'total_price' => [
                                        'sum' => [
                                            'field' => 'price'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
