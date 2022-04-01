<?php

namespace Yaroslavpopovic\LaravelBackupImport\Console\Commands;
use Illuminate\Console\Command;

class BackupImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import backup from storage';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $backupImport = app()->make(\Yaroslavpopovic\LaravelBackupImport\BackupImport::class);
        $backupImport->import();
    }
}
