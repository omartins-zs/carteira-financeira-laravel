<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Transaction;
use App\Observers\TransactionObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
        // Garante que todo URL gerado use esse root
        URL::forceRootUrl(config('app.url'));

        // Se você estiver usando HTTPS em dev (Valet):
        // URL::forceScheme('https');

        Carbon::setLocale('pt_BR');
        Transaction::observe(TransactionObserver::class);
    }
}
