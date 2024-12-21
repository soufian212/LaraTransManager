<?php

namespace Soufian212\LaraTransManager\Services;

use Illuminate\Support\Facades\Cache;
use Soufian212\LaraTransManager\Models\Translation;

class TranslationService
{

    /**
     * Retrieve translations from the database or cache.
     *
     * @param string $lang
     * @param string $filename
     * @return array
     */
    function get($lang, $filename)
    {

        // Check if caching is enabled in config
        if (config('translation.cache_translations', true)) {
            // Use cache key
            $cacheKey = "{$lang}.{$filename}.translations";

            // Try to get translations from the cache
            $translations = Cache::get($cacheKey);

            // If cache doesn't exist, fetch from DB and cache it
            if (!$translations) {
                $translations = Translation::where('lang', $lang)
                    ->where('file', $filename)
                    ->pluck('value', 'key')
                    ->toArray();

                // Cache the translations for 60 minutes
                Cache::put($cacheKey, $translations, config('translation.cache_lifetime', 3600));
            }

            return $translations;
        }

        // If caching is disabled, fetch translations directly from the database
        return Translation::where('lang', $lang)
            ->where('file', $filename)
            ->pluck('value', 'key')
            ->toArray();
    }

    function clearCache($lang, $filename)
    {
        // Clear the cache for the given language and filename
        $cacheKey = "{$lang}.{$filename}.translations";
        Cache::forget($cacheKey);
    }

    function clearAllCache()
    {
        // Clear all translations cache
        Cache::flush();
    }
}
