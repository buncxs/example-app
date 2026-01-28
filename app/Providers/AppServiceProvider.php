<?php

namespace App\Providers;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Esto quita el primer nivel de "data" en los Resources
        JsonResource::withoutWrapping();

        // "Intercepta" todas las comprobaciones de permisos
        Gate::before(function ($user, $ability){ 
            return $user->hasRole('Super Administrador') ? true : null;
        });


    }
}
