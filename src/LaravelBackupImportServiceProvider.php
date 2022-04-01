<?php
namespace Yaroslavpopovic\LaravelBackupImport;
use Illuminate\Support\ServiceProvider;
use Yaroslavpopovic\LaravelBackupImport\Console\Commands\BackupImport;

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
            $this->commands([
                BackupImport::class
            ]);
        }
    }

    private function publishResources()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-backup-import.php' => config_path('laravel-backup-import.php'),
        ], 'config');
    }
}
