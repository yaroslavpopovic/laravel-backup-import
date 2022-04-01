<?php

class BackupImportCommandTest extends \Yaroslavpopovic\LaravelBackupImport\Tests\TestCase
{
    /**
     * @test
     * @covers \Yaroslavpopovic\LaravelBackupImport\Console\Commands\BackupImport::handle
     */
     public function it_can_import_backup()
     {
         $this->mock(\Yaroslavpopovic\LaravelBackupImport\BackupImport::class, function (\Mockery\MockInterface $mock) {
             $mock->shouldReceive('import')->once();
         });
         $this->artisan('backup:import');
     }
}
