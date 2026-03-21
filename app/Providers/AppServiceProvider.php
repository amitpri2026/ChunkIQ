<?php

namespace App\Providers;

use App\Models\PipelineJob;
use App\Observers\PipelineJobObserver;
use App\Support\TenantManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TenantManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PipelineJob::observe(PipelineJobObserver::class);
    }
}
