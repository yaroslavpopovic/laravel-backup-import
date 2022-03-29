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
