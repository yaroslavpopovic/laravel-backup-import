<?php

class BackupImportJobTest extends \Yaroslavpopovic\LaravelBackupImport\Tests\TestCase
{
    /**
     * @test
     * @covers \Yaroslavpopovic\LaravelBackupImport\Jobs\BackupImport::handle
     */
     public function it_can_dispatch_job_and_send_notifiaction()
     {
         $this->mock(\Yaroslavpopovic\LaravelBackupImport\BackupImport::class, function (\Mockery\MockInterface $mock) {
             $mock->shouldReceive('import')->once();
         });
         \Illuminate\Support\Facades\Notification::fake();
         (new \Yaroslavpopovic\LaravelBackupImport\Jobs\BackupImport())->handle();
         \Illuminate\Support\Facades\Notification::assertSentOnDemand(\Yaroslavpopovic\LaravelBackupImport\Notifications\BackupImport::class);
     }
}
