<?php

namespace App\Providers;

use App\Elasticsearch\OrderIndex;
use App\Repositories\Order\IOrderRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Product\IProductRepository;
use App\Repositories\Product\ProductRepository;
use App\Services\OrderElasticsearchService;
use App\Services\OrderService;
use App\Services\ProductService;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use Elastic\Elasticsearch\Client as ElasticsearchClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ElasticsearchClient::class, function ($app) {
            return ClientBuilder::create()->setHosts(config('database.connections.elasticsearch.hosts'))->build();
        });

        $this->app->bind(IOrderRepository::class, OrderRepository::class);
        $this->app->bind(OrderService::class, function ($app) {
            return new OrderService(
                $app->make(OrderRepository::class),
                $app->make(OrderElasticsearchService::class),
                $app->make(OrderIndex::class)
            );
        });

        $this->app->bind(IProductRepository::class, ProductRepository::class);
        $this->app->bind(ProductService::class, function ($app) {
            return new ProductService($app->make(ProductRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
