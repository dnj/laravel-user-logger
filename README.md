# Log activity inside your Laravel app
The `dnj/laravel-user-logger` package provides easy way to log the activities of the users of your app.
The Package stores all activity in the `logs` table.

Here's a demo of how you can use it:

```php
$logger = app(dnj\UserLogger\Contracts\ILogger::class);
$log = $logger
	->performedOn($anEloquentModel)
	->causedBy($user)
	->withProperties(['customProperty' => 'customValue'])
	->log('edit');
```

## Installation

You can install the package via composer:

```bash
composer require dnj/laravel-user-logger
```

The package will automatically register itself.


After this you can create the `logs` table by running the migrations:

```bash
php artisan migrate
```

You can optionally publish the config file with:

```bash
php artisan vendor:publish --provider="dnj\UserLogger\ServiceProvider" --tag="config"
```


## Security

If you've found a bug regarding security please mail [security@dnj.co.ir](mailto:security@dnj.co.ir) instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

