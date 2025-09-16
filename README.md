# Laravel Activity Logger

A lightweight activity logging package for Laravel 10/11/12. Provides a reusable `LogsActivity` trait,
an `ActivityLog` model, and ready-to-run migrations.

## Installation

```bash
composer require kazitoha/laravel-activity-logger
php artisan vendor:publish --tag=activity-logger-config   # optional
php artisan migrate
```

## Usage

Add the trait to any Eloquent model:

```php
use Kazitoha\ActivityLogger\Traits\LogsActivity;

class Patient extends Model
{
    use LogsActivity;
}
```

Whenever the model is **created**, **updated**, or **deleted**, an `activity_logs` row is inserted.

### Configuration

You can change the table name and redaction keys in `config/activity-logger.php`.
Publish the config via:

```bash
php artisan vendor:publish --tag=activity-logger-config
```

## Testing (optional)

This package ships with a basic Pest setup. To run tests in this package repo:

```bash
composer install
./vendor/bin/pest
```

## License

MIT
