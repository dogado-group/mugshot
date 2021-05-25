<?php

return [

    'redirect' => env('HOMEPAGE_REDIRECT'),

    /*
    |--------------------------------------------------------------------------
    | Default Values
    |--------------------------------------------------------------------------
    */

    'defaults' => [

        'width' => 1280,

        'height' => 800,

        'fullPage' => false,

        'deviceScale' => 1,

        /*
         * not supported with png
         */
        'quality' => 70,

        'delay' => 1,

        /*
         * Supported: "jpeg", "png"
         */
        'fileExtension' => 'jpeg',

        /*
         * How long we should hold screenshots before we update
         *
         * in minutes
         */
        'cache' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    'validation' => [

        'maxWidth' => 1280,

        'maxHeight' => 800,

        'maxDelay' => '10'
    ],

    /*
    |--------------------------------------------------------------------------
    | Request
    |--------------------------------------------------------------------------
    */

    'request' => [

        'useragent' => 'mugshot screenshot bot'

    ],

    /*
    |--------------------------------------------------------------------------
    | Puppeteer
    |--------------------------------------------------------------------------
    */

    'puppeteer' => [

        'node' => env('BINARY_NODE_PATH', '/usr/local/bin/node'),

        'npm' => env('BINARY_NPM_PATH', '/usr/local/bin/npm'),

        'proxyServer' => env('PUPPETEER_PROXY', ''),

        'chrome' => env('BINARY_CHROME_PATH', ''),

    ]
];
