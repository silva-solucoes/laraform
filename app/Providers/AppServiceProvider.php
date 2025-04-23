<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

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
        // Definir o idioma padrão como português do Brasil
        App::setLocale('pt-BR');
        
        // Configurar o tamanho padrão de string para MySQL
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        // Adicionar validador personalizado para contagem mínima de palavras
        \Illuminate\Support\Facades\Validator::extend('min_words', function ($attribute, $value, $parameters, $validator) {
            return min_words($value, $parameters[0] ?? 1);
        });
    }
}
