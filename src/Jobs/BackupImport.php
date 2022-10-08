<?php
namespace Yaroslavpopovic\LaravelBackupImport\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;

class BackupImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Indicate if the job should be marked as failed on timeout.
     *
     * @var bool
     */
    public $failOnTimeout = true;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600;

    public function __construct()
    {
    }

    public function handle()
    {
        $backupImport = app()->make(\Yaroslavpopovic\LaravelBackupImport\BackupImport::class);
        $backupImport->import();
        Notification::route('mail', Config::get('laravel-backup-import.notifications.mail.to'))->notify(new \Yaroslavpopovic\LaravelBackupImport\Notifications\BackupImport());
    }
}
