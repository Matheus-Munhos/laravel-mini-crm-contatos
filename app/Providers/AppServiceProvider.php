<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Contacts\Repositories\ContactRepositoryInterface;
use App\Infrastructure\Repositories\ContactEloquentRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ContactRepositoryInterface::class,
            ContactEloquentRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}