<?php

namespace App\Elasticsearch;

use Elastic\Elasticsearch\Client;

interface IElasticsearchIndex
{
    /**
     *
     * @param Client $elasticsearch
     */
    public function __construct(Client $elasticsearch);

    /**
     * Indexing data in the elasticsearch
     *
     * @param array $data
     * @return void
     */
    public function index(array $data);

    /**
     * Get the name of elasticsearch index data
     *
     * @return void
     */
    public static function getIndexName();
}