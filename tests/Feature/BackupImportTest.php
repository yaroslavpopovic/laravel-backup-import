<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Yaroslavpopovic\LaravelBackupImport\BackupImport;
use Yaroslavpopovic\LaravelBackupImport\Tests\TestCase;

class BackupImportTest extends TestCase
{
    /**
     * @test
     * @covers \Yaroslavpopovic\LaravelBackupImport\BackupImport::downloadBackupFile
     */
     public function it_can_download_backup_file_zip_from_storage()
     {
         Storage::fake();
         $backupImport = app()->make(BackupImport::class);
         $backupImport->getSourceDisk()->putFileAs($backupImport->getSourcePath(), UploadedFile::fake()->create('backup1.txt'), 'backup1.txt');
         $backupImport->getSourceDisk()->putFileAs($backupImport->getSourcePath(), UploadedFile::fake()->create('backup2.zip'), 'backup2.zip');

         $backupImport->downloadBackupFile();

         $backupImport->getDestinationDisk()->assertMissing($backupImport->getDestinationPath().'/'.$backupImport->getDestinationFileName().'.txt');
         $backupImport->getDestinationDisk()->assertExists($backupImport->getDestinationPath().'/'.$backupImport->getDestinationFileName().'.zip');
     }

     /**
      * @test
      * @covers \Yaroslavpopovic\LaravelBackupImport\BackupImport::unzipBackupFile
      */
      public function it_can_unzip_backup_file()
      {
          Storage::fake();
          $mock = Mockery::mock(BackupImport::class, [])->makePartial();
          $zipFile = $mock->getDestinationPath().'/'.$mock->getDestinationFileName().'.zip';
          $mock->shouldReceive('getDownloadedBackupFileName')->andReturn($zipFile);
          $this->instance(BackupImport::class, $mock);

          $zip = new \ZipArchive();

          if ($zip->open($mock->getDestinationFileName().'.zip', ZipArchive::CREATE | ZIPARCHIVE::OVERWRITE)!==TRUE) {
              exit("cannot open <$zipFile>\n");
          }

          $zip->addFromString("testfilephp.txt", "#1 This is a test string added as testfilephp.txt.\n");

          $zip->close();

          $mock->getDestinationDisk()->put($zipFile, $zip);

          $mock->getDestinationDisk()->assertExists($zipFile);

          $mock->unzipBackupFile();

          $mock->getDestinationDisk()->assertMissing($zipFile);
      }
}
