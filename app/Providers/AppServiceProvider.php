<?php

namespace App\Providers;

use App\Services\Otp\EmailOtpSender;
use App\Services\Otp\OtpSenderInterface;
use App\Services\Otp\OtpService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\ViewErrorBag;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OtpSenderInterface::class, EmailOtpSender::class);

        $this->app->singleton(OtpService::class, function ($app) {
            return new OtpService($app->make(OtpSenderInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('errors', new ViewErrorBag);
    }
}
