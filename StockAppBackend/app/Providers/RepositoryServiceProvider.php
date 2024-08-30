<?php

namespace App\Providers;

use App\Interfaces\ProductTypeInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repository\ProductTypeRepository;
use App\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProductTypeInterface::class, ProductTypeRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
