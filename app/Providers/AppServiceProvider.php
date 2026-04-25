<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

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
        $this->configureCors();
        $this->configureSanctumStatefulDomains();
    }

    protected function configureCors(): void
    {
        $allowedOrigins = env('CORS_ALLOWED_ORIGINS', '*');
        
        if ($allowedOrigins === '*') {
            config(['cors.supports_credentials' => false]);
        } else {
            config([
                'cors.supports_credentials' => true,
                'cors.allowed_origins' => array_map('trim', explode(',', $allowedOrigins)),
                'cors.allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'cors.allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With', 'Accept'],
                'cors.exposed_headers' => [],
                'cors.max_age' => 86400,
            ]);
        }
    }

    protected function configureSanctumStatefulDomains(): void
    {
        $statefulDomains = config('sanctum.stateful', []);
        $additionalDomains = env('SANCTUM_STATEFUL_DOMAINS', '');
        
        if (!empty($additionalDomains)) {
            $domains = array_merge($statefulDomains, array_map('trim', explode(',', $additionalDomains)));
            config(['sanctum.stateful' => array_unique($domains)]);
        }
    }
}
