<?php

return [
    'defaultLang' => 'en',

    /*
     * The lang files paths.
     */

    'lang_paths' => [
        base_path('lang/{lang}'),
    ],

    /*
     * The paths that will scanned for translations.
     */

    'matches' => [
        app_path(),
        resource_path('views'),
    ],
];
