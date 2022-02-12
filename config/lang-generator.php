<?php

return [
    'defaultLang' => 'en',

    /*
     * The lang files paths.
     */

    'lang' => [
        'auth' => base_path('lang/{lang}/auth.php'),
        'pagination' => base_path('lang/{lang}/pagination.php'),
        'passwords' => base_path('lang/{lang}/passwords.php'),
        'validation' => base_path('lang/{lang}/validation.php'),
    ],

    /*
     * The paths that will scanned for translations.
     */

    'matches' => [
        app_path(),
        base_path('views'),
    ],
];