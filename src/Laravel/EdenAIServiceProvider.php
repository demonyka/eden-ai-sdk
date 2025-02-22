<?php

namespace Demonyka\EdenAiSdk\Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;

class EdenAIServiceProvider extends ServiceProvider
{
    /**
     * Регистрация пакета.
     *
     * @return void
     */
    public function register()
    {
        // Публикация конфигурации
        $this->offerPublishing();
    }

    /**
     * Загрузка ресурсов пакета.
     *
     * @return void
     */
    public function boot()
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
        // Публикация конфигурации для Laravel
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/edenai.php' => config_path('edenai.php'),
            ], 'edenai-config');
        }
    }
}
