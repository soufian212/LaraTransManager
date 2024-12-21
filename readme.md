# LaraTransManager

LaraTransManager is a Laravel package designed to manage translations efficiently. It provides a user-friendly interface to handle translations for multiple languages within your Laravel application.

## Features

- Manage translations for multiple languages
- Add, edit, and delete translation keys and values
- Using Cache to improve performance
- A friendly user interface for easy translation management

## Installation

To install the package, use Composer:
s
```bash
composer require soufian212/laratransmanager
```
Publish the migration file:
```bash
php artisan vendor:publish --tag=laratransmanager-migrations
```
And then we need to publish assets
```bash	
php artisan vendor:publish --tag=public --force
```
And finally publish the config file:
```bash
php artisan vendor:publish --tag=laratransmanager-config
```
Running the migrations
```bash
php artisan migrate
```
## Usage

Visit `/translations` to see the translations dashboard.

You will see and empty dashboard, so first of all wen need to initialize and export the translations to database.

So run 
```bash
php artisan translation:init
```

## Configuration

LaraTransManager provides a configuration file to customize its behavior. After publishing, you can find it at config/laratransmanager.php. Below is an example of the configuration file:

```php
    'cache_translations' => true,
    'cache_lifetime' => 3600,
```
If cache_translations is set to true in the configuration file, LaraTransManager will cache the translations to enhance performance. Cached translations are stored for the duration specified by cache_lifetime (in seconds). This reduces database queries and improves the responsiveness of your application.

To clear the translations cache, use the following command:

```bash
php artisan cache:clear
```

Or simply click on the clear cache button in the dashboard. 

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvements, please open an issue or submit a pull request.

## License

This package is open-source and available under the MIT License.
