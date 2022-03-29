# Laravel Backup Import
## Installation

```
composer require yaroslavpopovic/laravel-backup-import
```

You can publish config file to overwrite
```
php artisan vendor:publish --provider="Yaroslavpopovic\LaravelBackupImport\LaravelBackupImportServiceProvider" --tag="config"
```

You should set env var

Disk
```
LARAVEL_DB_IMPORT_SOURCE_DISK=
LARAVEL_DB_IMPORT_SOURCE_PATH=
LARAVEL_DB_IMPORT_NOTIFICATION_EMAIL_TO=
```
