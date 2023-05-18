<?php

namespace App\Elasticsearch;

use Elastic\Elasticsearch\Client;

class OrderIndex implements IElasticsearchIndex
{
    private const INDEX = 'orders';
    protected Client $elasticsearch;

    /**
     *
     * @param Client $elasticsearch
     */
    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * Indexing data in the elasticsearch
     *
     * @param array $orderData
     * @return void
     */
    public function index(array $orderData): void
    {
        $this->elasticsearch->index([
            'index' => self::INDEX,
            'body' => $orderData,
        ]);
    }

    /**
     * Get the name of elasticsearch index data
     *
     * @return string
     */
    public static function getIndexName(): string
    {
        return self::INDEX;
    }
}
