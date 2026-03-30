<?php

namespace Zain\BillForge;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Zain\BillForge\Models\SubscriptionSetting;

class BillForgeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load Routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Load Views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'subscriptions');

        // Load Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publishes config
        $this->publishes([
            __DIR__.'/../config/subscriptions.php' => config_path('subscriptions.php'),
        ], 'subscriptions-config');

        // Publishes views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/subscriptions'),
        ], 'subscriptions-views');

        // Publishes migrations
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'subscriptions-migrations');

        // Register middleware
        $router = $this->app['router'];
        $router->aliasMiddleware('subscription.access', \Zain\BillForge\Http\Middleware\CheckSubscriptionAccess::class);

        // Share subscription settings with the frontend layout view
        View::composer(['subscriptions::layouts.frontend', 'subscriptions::layouts.admin'], function ($view) {
            try {
                $subSettings = SubscriptionSetting::getMap();
            } catch (\Exception $e) {
                // Table may not exist yet (before migrations run)
                $subSettings = [];
            }
            $view->with('subSettings', $subSettings);
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/subscriptions.php', 'subscriptions'
        );

        $this->app->singleton(\Zain\BillForge\Contracts\GatewayManagerInterface::class, function ($app) {
            return new \Zain\BillForge\Services\GatewayManager();
        });
    }
}
