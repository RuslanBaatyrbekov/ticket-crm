<?php

namespace App\Providers;

use App\Contracts\TicketRepositoryInterface;
use App\Repositories\TicketRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('ticket_creation', function (Request $request) {
            return Limit::perDay(1)->by($request->input('phone', $request->ip()));
        });
    }
}
