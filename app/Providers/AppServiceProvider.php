<?php

namespace App\Providers;


use App\Events\ContactScoreProcessed;
use App\Listeners\LogContactScoreProcessed;
use Illuminate\Support\Facades\Event;
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
    \App\Models\Contact::observe(\App\Observers\ContactObserver::class);
    Event::listen(
    ContactScoreProcessed::class,
    LogContactScoreProcessed::class
);
}
}