<?php

namespace App\Providers;

use App\Models\Product;
use App\Policies\Api\V1\ProductPolicy;
use App\Repositories\Contracts\ImagesRepositoryContract;
use App\Repositories\Contracts\OrderRepositoryContract;
use App\Repositories\Contracts\ProductRepositoryContract;
use App\Repositories\ImagesRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Services\Contracts\InvoiceServiceContract;
use App\Services\Contracts\PaypalServiceContract;
use App\Services\FileService;
use App\Services\Contracts\FileServiceContract;
use App\Services\InvoiceService;
use App\Services\PaypalService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings=[
        ProductRepositoryContract::class=>ProductRepository::class,
        ImagesRepositoryContract::class=>ImagesRepository::class,
        FileServiceContract::class=>FileService::class,
        OrderRepositoryContract::class=>OrderRepository::class,
        PaypalServiceContract::class=>PaypalService::class,
        InvoiceServiceContract::class=>InvoiceService::class,

    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Paginator::useBootstrapFour();

        Gate::policy(Product::class, ProductPolicy::class);
    }
}
