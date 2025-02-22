<?php

namespace EdenAI\Laravel;

use EdenAI\Managers\EdenAIManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;

class EdenAIServiceProvider extends ServiceProvider
{
    /**
     * Регистрация пакета.
     *
     * @return void
     */
    public function register(): void
    {
        // Публикация конфигурации
        $this->offerPublishing();
        $this->registerBindings();
    }

    private function registerBindings(): void
    {
        $this->app->singleton('edenai', function ($app) {
            return new EdenAIManager();
        });
    }

    /**
     * Загрузка ресурсов пакета.
     *
     * @return void
     */
    public function boot(): void
    {
        // Если это Laravel, сливаем конфиг
        if ($this->app instanceof LaravelApplication) {
            $this->mergeConfigFrom(__DIR__.'/config/edenai.php', 'edenai');
        }
    }

    /**
     * Настройка групп публикации ресурсов.
     */
    private function offerPublishing(): void
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/edenai.php' => config_path('edenai.php'),
            ], 'edenai-config');
        }
    }
}
