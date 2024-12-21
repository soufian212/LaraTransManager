<?php

if (!function_exists('get_language_details')) {
    function get_language_details($lang_prefix) {
        $languages = [
            'en' => ['us', 'English', 'United States'], // English
            'fr' => ['fr', 'French', 'France'], // French
            'es' => ['es', 'Spanish', 'Spain'], // Spanish
            'de' => ['de', 'German', 'Germany'], // German
            'it' => ['it', 'Italian', 'Italy'], // Italian
            'pt' => ['pt', 'Portuguese', 'Portugal'], // Portuguese
            'ru' => ['ru', 'Russian', 'Russia'], // Russian
            'zh' => ['cn', 'Chinese', 'China'], // Chinese
            'ja' => ['jp', 'Japanese', 'Japan'], // Japanese
            'ko' => ['kr', 'Korean', 'South Korea'], // Korean
            'ar' => ['ma', 'Arabic', 'Morocco'], // Arabic
            'hi' => ['in', 'Hindi', 'India'], // Hindi
            'bn' => ['bd', 'Bengali', 'Bangladesh'], // Bengali
            'fa' => ['ir', 'Persian', 'Iran'], // Persian
            'sw' => ['tz', 'Swahili', 'Tanzania'], // Swahili
            'nl' => ['nl', 'Dutch', 'Netherlands'], // Dutch
            'tr' => ['tr', 'Turkish', 'Turkey'], // Turkish
            'sv' => ['se', 'Swedish', 'Sweden'], // Swedish
            'no' => ['no', 'Norwegian', 'Norway'], // Norwegian
            'da' => ['dk', 'Danish', 'Denmark'], // Danish
            'fi' => ['fi', 'Finnish', 'Finland'], // Finnish
            'pl' => ['pl', 'Polish', 'Poland'], // Polish
            'cs' => ['cz', 'Czech', 'Czech Republic'], // Czech
            'vi' => ['vn', 'Vietnamese', 'Vietnam'], // Vietnamese
            'th' => ['th', 'Thai', 'Thailand'], // Thai
            'el' => ['gr', 'Greek', 'Greece'], // Greek
            'uk' => ['ua', 'Ukrainian', 'Ukraine'], // Ukrainian
            'he' => ['il', 'Hebrew', 'Israel'], // Hebrew
            'id' => ['id', 'Indonesian', 'Indonesia'], // Indonesian
            'ro' => ['ro', 'Romanian', 'Romania'], // Romanian
            'hu' => ['hu', 'Hungarian', 'Hungary'], // Hungarian
            'sk' => ['sk', 'Slovak', 'Slovakia'], // Slovak
            'ms' => ['my', 'Malay', 'Malaysia'], // Malay
        ];

        $lang_prefix = strtolower($lang_prefix);

        if ($lang_prefix == 'all') {
            return $languages;
        }

        // Return language details or null if not found
        return $languages[$lang_prefix] ?? null;
    }
}
