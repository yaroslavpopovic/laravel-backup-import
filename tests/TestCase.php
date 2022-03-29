<?php
namespace Yaroslavpopovic\LaravelBackupImport\Tests;

use Yaroslavpopovic\LaravelBackupImport\LaravelBackupImportServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelBackupImportServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
