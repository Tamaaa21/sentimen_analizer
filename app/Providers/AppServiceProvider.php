<?php

namespace App\Providers;

use App\Services\SentimentAnalyzer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SentimentAnalyzer::class, function ($app) {
            return new SentimentAnalyzer();
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