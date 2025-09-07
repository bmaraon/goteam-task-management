<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        foreach (glob(app_path('Repositories/*.php')) as $file) {
            $class = 'App\\Repositories\\' . basename($file, '.php');
            $interface = 'App\\Repositories\\Contracts\\' . basename($file, '.php') . 'Interface';

            if (interface_exists($interface)) {
                $this->app->bind($interface, $class);
            }
        }
    }
}