<?php
namespace Soufian212\LaraTransManager\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array get(string $lang, string $filename)
 */
class LaraTranslations extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'laratransmanager.translation'; // The service container binding
    }
}
