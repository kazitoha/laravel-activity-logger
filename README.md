# Laravel Activity Logger

Lightweight activity logging for Laravel models via a reusable `LogsActivity` trait.  
Logs **created**, **updated** (with old/new values), and **deleted** events.

## Installation

### A) Using GitHub (no Packagist needed)
In your Laravel app `composer.json` add a repository entry:

```json
{
  "repositories": [
    { "type": "vcs", "url": "https://github.com/kazitoha/laravel-activity-logger.git" }
  ]
}
```

Then require the dev branch:

```bash
composer require kazitoha/laravel-activity-logger:dev-main
```

### B) Local path (fast for development)
If the package folder is next to your app:

```bash
composer config repositories.activity-logger path ../laravel-activity-logger
composer require kazitoha/laravel-activity-logger:*@dev
```

> Once you tag a version and publish to Packagist, you can simply:  
> `composer require kazitoha/laravel-activity-logger`

## Migrate

Migrations are auto-loaded by the service provider:
```bash
php artisan migrate
```

## Usage

Add the trait to any Eloquent model you want to audit:

```php
use Kazitoha\ActivityLogger\Traits\LogsActivity;

class Patient extends Model
{
    use LogsActivity;
}
```

When you `create`, `update`, or `delete` a model, a row is inserted into the activity table.

## Configuration

Publish the config to customize the table name or redact keys:

```bash
php artisan vendor:publish --tag=activity-logger-config
```

Config file options (`config/activity-logger.php`):
- `table` — database table name (default `activity_logs`)
- `redact_keys` — array of keys to mask in the payload (`[REDACTED]`)

## What gets logged?

- `user_id` — `Auth::id()`
- `action` — `created` | `updated` | `deleted`
- `loggable_type` — morph class of the model
- `loggable_id` — primary key of the model
- `description` — JSON payload:
  - for **created**: `{ attributes: { ...newValues } }`
  - for **updated**: `{ attributes: { ...new }, old: { ...old } }`
  - for **deleted**: `{ old: { ...originalValues } }`
- `ip_address` — request IP
- timestamps

Sensitive keys in `description` are redacted per config.

## Testing (optional)

This package ships with a basic Pest + Orchestra Testbench setup. To run tests in the package root:

```bash
composer install
vendor/bin/pest
```

## License

MIT
