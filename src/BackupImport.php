<?php

namespace Yaroslavpopovic\LaravelBackupImport;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class BackupImport
{
    private Filesystem $sourceDisk;
    private string $sourcePath;
    private Filesystem $destinationDisk;
    private string $destinationPath;
    private string $destinationFileName;
    private string $downloadedBackupFileName;
    private string $destinationDBDumpPath;

    public function __construct()
    {
        $this->sourceDisk = Storage::disk(config('laravel-backup-import.source.disk'));
        $this->sourcePath = config('laravel-backup-import.source.path');
        $this->destinationDisk = Storage::disk(config('laravel-backup-import.destination.disk'));
        $this->destinationPath = config('laravel-backup-import.destination.path');
        $this->destinationFileName = config('laravel-backup-import.destination.file_name');
        $this->destinationDBDumpPath = config('laravel-backup-import.destination.db_dumps_path');
        $this->downloadedBackupFileName = '';
    }

    /**
     * @throws \Exception
     */
    public function import()
    {
        $this->downloadBackupFile();
        $this->unzipBackupFile();
        $this->importDBDump();
    }

    public function getSourceDisk()
    {
        return $this->sourceDisk;
    }

    public function getSourcePath()
    {
        return $this->sourcePath;
    }

    public function getDestinationDisk()
    {
        return $this->destinationDisk;
    }

    public function getDestinationPath()
    {
        return $this->destinationPath;
    }

    public function getDestinationFileName()
    {
        return $this->destinationFileName;
    }

    public function getDestinationDBDumpPath()
    {
        return $this->destinationDBDumpPath;
    }

    public function getDownloadedBackupFileName()
    {
        return $this->downloadedBackupFileName;
    }

    public function downloadBackupFile()
    {
        $lastBackupFile = $this->getLastBackupFile();
        if ($lastBackupFile)
        {
            $this->downloadedBackupFileName = $this->destinationPath.'/'.$this->destinationFileName . '.' . Str::afterLast($lastBackupFile, '.');
            $this->destinationDisk->put($this->downloadedBackupFileName, $this->getSourceDisk()->get($lastBackupFile));
        }
        else
        {
            throw new \Exception(__("Non è stato possibile scaricare il file di backup"));
        }
    }

    public function unzipBackupFile()
    {
        exec('unzip -o '.$this->destinationDisk->path($this->getDownloadedBackupFileName()).' -d '.$this->destinationDisk->path($this->destinationPath.'/'.$this->destinationFileName));
        $this->destinationDisk->delete($this->getDownloadedBackupFileName());
    }

    private function getLastBackupFile():?string
    {
        $remoteFiles = $this->getSourceDisk()->allFiles($this->sourcePath);
        if (count($remoteFiles)) {
            return $remoteFiles[count($remoteFiles) - 1];
        }
        return null;
    }

    public function importDBDump()
    {
        $sqlFileName = $this->destinationDisk->allFiles($this->destinationDBDumpPath)[0] ?? null;

        if(is_null($sqlFileName))
        {
            throw new \Exception(__("Non è stato possibile trovare il backup del database scaricato"));
        }

        Artisan::call('down');

        Model::withoutEvents(function() use ($sqlFileName) {
            Artisan::call('db:wipe', ['--force' => true]);

            $host = config('database.connections.mysql.host');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $db = config('database.connections.mysql.database');
            Process::fromShellCommandline(sprintf(
                'mysql -h%s -u%s -p%s %s < %s',
                $host,
                $username,
                $password,
                $db,
                $this->destinationDisk->path($sqlFileName)
            ))->mustRun();

            Artisan::call('migrate', ['--force' => true]);
        });


        Artisan::call('up');
    }
}
