<?php

namespace App\Providers;

use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Illuminate\Support\ServiceProvider;
use Dedoc\Scramble\Scramble;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // santigarcor/laratrust registers Laratrust\Console\MakeSeederCommand
        // without overriding the inherited `make:seeder` signature it gets from
        // SeederMakeCommand, so it silently hijacks Laravel's real seeder
        // generator. Binding it to resolve as the real command class means
        // Laratrust's own registration ends up wiring up Laravel's generator.
        $this->app->bind(
            \Laratrust\Console\MakeSeederCommand::class,
            SeederMakeCommand::class,
        );

        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::registerApi('central', [
            'info' => [
                'title' => 'Central API',
                'version' => '1.0.0',
            ],
            'api_path' => 'localhost/api/v1',
        ]);

        Scramble::registerApi('tenant', [
            'info' => [
                'title' => 'Tenant API',
                'version' => '1.0.0',
            ],
            'api_path' => 'api/v1',
        ]);
    }
}
