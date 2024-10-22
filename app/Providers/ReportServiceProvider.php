<?php

namespace App\Providers;

use App\Interfaces\ReportGenerator;
use App\Services\ReportGenerator\DomPDFReportGenerator;
use Illuminate\Support\ServiceProvider;

class ReportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ReportGenerator::class, DomPDFReportGenerator::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
