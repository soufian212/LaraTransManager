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

