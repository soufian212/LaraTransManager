<?php

namespace Soufian212\LaraTransManager\Http\Controller;


use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Soufian212\LaraTransManager\Facades\LaraTranslations;
use Soufian212\LaraTransManager\Models\Translation;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $translations = Translation::select('lang')
            ->selectRaw('COUNT(value) as total_keys')
            ->groupBy('lang')
            ->paginate(20)
            ->through(function ($item) {
                return [
                    'lang' => $item->lang,
                    'total_keys' => $item->total_keys,
                    'details' => get_language_details($item->lang)
                ];
            });


        // Fetch the total keys for the English translation
        $englishTotalKeys = Translation::where('lang', 'en')->count();

        return Inertia::render('Dashboard', [
            'translations' => $translations,
            'langs' => get_language_details('all'),
            'englishTotalKeys' => $englishTotalKeys,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $language)
    {

        $availableLanguages = array_keys(get_language_details('all'));

        if (!in_array($language, $availableLanguages)) {
            return redirect()->back()->with('toast', [
                'severity' => 'error',
                'detail' => 'Language not found',
                'summary' => 'Error',
            ]);
        }

        //check if the language alredy exist
        if (Translation::where('lang', $language)->exists()) {

            return redirect()->back()->with('toast', [
                'severity' => 'error',
                'detail' => 'Language already exist',
                'summary' => 'Error',
            ]);
        }

        // create new language dir on lang folder
        //create new folder
        $lang_dir = lang_path($language);
        if (!is_dir($lang_dir)) {
            mkdir($lang_dir, 0777, true);
            //get files from en folder and make a copy of it
            $files = scandir(lang_path('en'));
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    copy(lang_path('en/' . $file), $lang_dir . '/' . $file);
                }
            }
        }

        // Copy translations from database
        $enTranslations = Translation::where('lang', 'en')->get();
        foreach ($enTranslations as $translation) {
            $trans = Translation::create([
                'lang' => $language,
                'file' => $translation->file,
                'key' => $translation->key,
                'value' => null,
            ]);
        }

        return redirect()->back()->with('toast', [
            'severity' => 'success',
            'detail' => 'Language created successfully',
            'summary' => 'Success',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return Inertia::render('LanguageTranslations', [
            'translations' => Translation::where('lang', $id)->paginate(20)->through(function ($translation) {
                return [
                    'id' => $translation->id,
                    'translated' => $translation->value !== null,
                    'file' => $translation->file,
                    'key' => $translation->key,
                    'value' => $translation->value,
                    'source' => Str::limit(
                        Translation::where('lang', 'en')
                            ->where('file', $translation->file)
                            ->where('key', $translation->key)
                            ->value('value'),
                        25,
                        '...'
                    ),
                ];
            }),
            'lang' => $id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($lang, $phraseId)
    {
        $translation = Translation::where('lang', $lang)->findOrFail($phraseId);
        return Inertia::render('EditTranslation', [
            'translation' => $translation,
            'lang' => $lang,
            'source' => Translation::where('lang', 'en')
                ->where('file', $translation->file)
                ->where('key', $translation->key)
                ->value('value'),
            'source_lang' => get_language_details('en'),
            'target_lang' => get_language_details($lang),


        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Retrieve the source text to validate against
        $translation = Translation::findOrFail($id);
        $sourceText = $translation->source; // Example: "The :attribute field must be accepted."

        // Extract placeholders from the source text
        preg_match_all('/:\w+/', $sourceText, $matches);
        $placeholders = $matches[0]; // Example: [":attribute"]

        // Validate the request to ensure all placeholders are present in the translation
        $rules = [
            'translatedSource' => [
                'required',
                function ($attribute, $value, $fail) use ($placeholders) {
                    foreach ($placeholders as $placeholder) {
                        if (strpos($value, $placeholder) === false) {
                            dd("The translation must include the placeholder $placeholder.");
                        }
                    }
                },
            ],
        ];

        $request->validate($rules);


        // Save the translation
        $translation->value = $request->input('translatedSource');
        $translation->save();


        if ($request->input('translateNext')) {
            return to_route('ltm.translations.edit', [$translation->lang, $translation->id + 1])->with('toast', [
                'severity' => 'success',
                'detail' => 'Translation updated successfully',
                'summary' => 'Success',
            ]);
        }

        return to_route('ltm.translations.show', $translation->lang)->with('toast', [
            'severity' => 'success',
            'detail' => 'Translation updated successfully',
            'summary' => 'Success',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if ($id == 'en') {
            return redirect()->back()->with('toast', [
                'severity' => 'error',
                'detail' => 'You can not delete the english language',
                'summary' => 'Error',
                'life' => 5000,
            ]);
        }

        //check if the lang is exist in the database
        $translation = Translation::where('lang', $id)->first();
        if (!$translation) {
            return redirect()->back()->with('toast', [
                'severity' => 'error',
                'detail' => 'Language not found',
                'summary' => 'Error',
                'life' => 5000,
            ]);
        }

        //delete the lang folder
        $lang_dir = lang_path($id);
        if (is_dir($lang_dir)) {
            //delete the folder and its contents recursively
            array_map('unlink', glob("$lang_dir/*.*"));
            rmdir($lang_dir);
        }
        //delete the translations from the database
        Translation::where('lang', $id)->delete();
        return redirect()->back()->with('toast', [
            'severity' => 'success',
            'detail' => 'Language deleted successfully',
            'summary' => 'Success',
            'life' => 5000,
        ]);
    }





    public function sourceShow()
    {
        return Inertia::render('SourceLanguageShow', [
            'translations' => Translation::where('lang', 'en')->orderBy('created_at', 'desc')->paginate(20),
            'files' => Translation::select('file')->where('lang', 'en')->distinct()->get(),
        ]);
    }




    public function sourceCreate()
    {
        return Inertia::render('SourceLanguageNewPhrase', [
            'files' => Translation::select('file')->where('lang', 'en')->distinct()->get()->map(function ($file) {
                return [
                    'file' => $file->file . '.php',
                ];
            }),
            'lang' => get_language_details('en'),
        ]);
    }

    public function sourceStore(Request $request)
    {
        $request->merge(['file' => str_replace('.php', '', $request->input('file')['file'])]);
        $request->validate([
            'phrase' => 'required',
            'key' => 'required',
            'file' => 'required',
        ]);


        // Check if the key already exists in the English language
        if (Translation::where('lang', 'en')->where('file', $request->input('file'))->where('key', $request->input('key'))->exists()) {
            return redirect()->back()->with('toast', [
                'severity' => 'error',
                'detail' => 'The key already exists',
                'summary' => 'Error',
            ]);
        }

        // Add the phrase to the English language
        Translation::create([
            'lang' => 'en',
            'file' => $request->input('file'),
            'key' => $request->input('key'),
            'value' => $request->input('phrase'),
        ]);

        // Get all other languages
        $languages = Translation::select('lang')->distinct()->where('lang', '!=', 'en')->get();

        // Add the phrase to other languages with value set to null
        foreach ($languages as $language) {
            Translation::create([
                'lang' => $language->lang,
                'file' => $request->input('file'),
                'key' => $request->input('key'),
                'value' => null,
            ]);
        }

        return to_route('ltm.translations.sourceShow')->with('toast', [
            'severity' => 'success',
            'detail' => 'Phrase added successfully',
            'summary' => 'Success',
        ]);
    }



    public function sourceEdit($phraseId)
    {
        $translation = Translation::where('lang', 'en')->findOrFail($phraseId);
        return Inertia::render('SourceLanguageEdit', [
            'translation' => $translation,
            'lang' => get_language_details('en'),
        ]);
    }

    public function sourceUpdate(Request $request, $id)
    {
        $translation = Translation::where('lang', 'en')->findOrFail($id);
        $translation->value = $request->input('translatedSource');
        $translation->save();
        return to_route('ltm.translations.sourceShow')->with('toast', [
            'severity' => 'success',
            'detail' => 'Translation updated successfully',
            'summary' => 'Success',
        ]);
    }

    public function sourceDestroy($id)
    {
        $translation = Translation::where('lang', 'en')->findOrFail($id);
        $key = $translation->key;
        $file = $translation->file;

        // Delete the key from all languages
        Translation::where('file', $file)->where('key', $key)->delete();

        return redirect()->back()->with('toast', [
            'severity' => 'success',
            'detail' => 'Translation deleted successfully',
            'summary' => 'Success',
        ]);
    }













    public function clearAllCache()
    {
        LaraTranslations::clearAllCache();
        return redirect()->back()->with('toast', [
            'severity' => 'success',
            'detail' => 'Cache cleared successfully',
            'summary' => 'Success',
        ]);
    }
}
