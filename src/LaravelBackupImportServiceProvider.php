<?php
namespace Yaroslavpopovic\LaravelBackupImport;
use Illuminate\Support\ServiceProvider;

class LaravelBackupImportServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-backup-import.php', 'laravel-backup-import');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishResources();
        }
    }

    private function publishResources()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-backup-import.php' => config_path('laravel-backup-import.php'),
        ], 'config');
    }
}
