<?php

namespace Soufian212\LaraTransManager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Soufian212\LaraTransManager\Models\Translation;

class InitCommand extends Command
{
    protected $signature = 'translation:init';
    protected $description = 'Initialize translations by exporting lang files to the database';

    public function handle()
    {
        $langPath = base_path('lang');

    
        //check if lang folder is exist
        if (!File::exists($langPath)) {
            $this->error('Lang folder not found.');
            $this->info('running `php artisan lang:publish`');
            $this->call('lang:publish');  
        }

        // Get all language directories
        $languages = File::directories($langPath);

        foreach ($languages as $languageDir) {
            $lang = basename($languageDir);  // e.g., 'en'
            $files = File::allFiles($languageDir);

            // Loop through each language file in the directory
            foreach ($files as $file) {
                $filename = $file->getFilenameWithoutExtension();  // e.g., 'validation'
                $translations = File::getRequire($file->getPathname());  // Get the translations in the file

                // Loop through each translation and store it in the database
                foreach ($translations as $key => $value) {
                    // If value is an array, convert it to a JSON string
                    if (is_array($value)) {
                        foreach ($value as $subKey => $subValue) {
                            if (is_array($subValue)) {
                                $subValue = json_encode($subValue);
                            }
                            Translation::updateOrCreate([
                                'lang' => $lang,
                                'file' => $filename,
                                'key' => $filename . '.' . $key . '.' . $subKey,
                            ], [
                                'value' => $subValue,
                            ]);
                        }
                        continue;
                    }

                    // If value is a JSON string, decode and store separately
                    if (is_string($value) && $this->isJson($value)) {
                        $decodedValue = json_decode($value, true);
                        foreach ($decodedValue as $subKey => $subValue) {
                            if (is_array($subValue)) {
                                $subValue = json_encode($subValue);
                            }
                            Translation::updateOrCreate([
                                'lang' => $lang,
                                'file' => $filename,
                                'key' => $filename . '.' . $key . '.' . $subKey,
                            ], [
                                'value' => $subValue,
                            ]);
                        }
                        continue;
                    }

                    // Store regular translations
                    if (is_array($value)) {
                        $value = json_encode($value);
                    }
                    Translation::updateOrCreate([
                        'lang' => $lang,
                        'file' => $filename,
                        'key' => $filename . '.' . $key,
                    ], [
                        'value' => $value,
                    ]);
                }

                // Overwrite the language file with code to load translations from the database
                $newFileContent = "<?php\n\n";
                $newFileContent .= "use Soufian212\LaraTransManager\Facades\LaraTranslations;\n\n";
                $newFileContent .= "\$lang = basename(__DIR__); // Get the current language directory, e.g., '{$lang}'\n";
                $newFileContent .= "\$filename = pathinfo(__FILE__, PATHINFO_FILENAME); // Get the current filename, e.g., '{$filename}'\n";
                $newFileContent .= "return LaraTranslations::get(\$lang, \$filename);\n";

                // Write the new content back to the language file
                File::put($file->getPathname(), $newFileContent);
            }
        }

        $this->info('Translations have been exported and language files overwritten successfully!');
    }

    private function isJson($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
