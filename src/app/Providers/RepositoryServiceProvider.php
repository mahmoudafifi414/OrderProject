<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Package\Domain\Model\Ingredient\IIngredientRepository;
use Package\Domain\Model\Order\IOrderProductRepository;
use Package\Domain\Model\Order\IOrderRepository;
use Package\Domain\Model\Product\IProductIngredientRepository;
use Package\Infrastructure\Mysql\Repository\Domain\Mysql\Ingredient\IngredientRepository;
use Package\Infrastructure\Mysql\Repository\Domain\Mysql\Order\OrderProductRepository;
use Package\Infrastructure\Mysql\Repository\Domain\Mysql\Order\OrderRepository;
use Package\Infrastructure\Mysql\Repository\Domain\Mysql\Product\ProductIngredientRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerOrderRepository();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * @return void
     */
    private function registerOrderRepository(): void
    {
        $this->app->bind(IOrderRepository::class, OrderRepository::class);
        $this->app->bind(IOrderProductRepository::class, OrderProductRepository::class);
        $this->app->bind(IProductIngredientRepository::class, ProductIngredientRepository::class);
        $this->app->bind(IIngredientRepository::class, IngredientRepository::class);
    }
}
