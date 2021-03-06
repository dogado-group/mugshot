<?php

return [

    /*
     * Redirect to a Page of your choice if something calls mugshot directly
     */

    'redirect' => env('MUGSHOT_HOMEPAGE_REDIRECT'),

    /*
     * How long we should keep screenshots before they are recreated again
     *
     * Value in minutes
     */

    'cache' => (int) env('MUGSHOT_CACHE_TIME', 30),


    /*
     * Specifies when the screenshot process should timeout
     *
     * Value in seconds
     */

    'timeout' => (int) env('MUGSHOT_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Default Values
    |--------------------------------------------------------------------------
    |
    | These values controls the defaults for random requests.
    |
    */

    'defaults' => [

        /*
         * Picture width of screenshot
         */

        'width' => 1280,

        /*
         * Picture height of screenshot
         */

        'height' => 800,

        /*
         * Allows you to capture the entire page
         *
         * Overwrites the width and height value
         */

        'fullPage' => false,

        /*
         * Allows the change device scale
         */

        'deviceScale' => 1,

        /*
         * Caution: Quality is unsupported with png screenshots
         * Value is percent (int)
         */

        'quality' => 70,

        /*
         * Delay before capture a screenshot.
         * Useful for heavily bloat pages or slow servers.
         *
         * Value in seconds
         */

        'delay' => 1,

        /*
         * Supported: "jpeg", "png"
         */

        'fileExtension' => 'jpeg',

    ],

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    'validation' => [

        /*
         * Max width of a screenshot
         */

        'maxWidth' => 1280,

        /*
         * Max height of a screenshot
         */

        'maxHeight' => 800,

        /*
         * Max delay of a screenshot
         *
         * Value in seconds
         */

        'maxDelay' => 10
    ],

    /*
    |--------------------------------------------------------------------------
    | Request
    |--------------------------------------------------------------------------
    */

    'request' => [

        /*
         * If you want to specify a custom UserAgent, here is the opportunity to do so
         */

        'useragent' => 'mugshot screenshot service'

    ],

    /*
    |--------------------------------------------------------------------------
    | Puppeteer
    |--------------------------------------------------------------------------
    */

    'puppeteer' => [

        /*
         * Path to the Node.js binary
         * usually it is `/usr/bin/node` or `C:\\nodejs\\node.exe` for Windows
         */

        'node' => env('MUGSHOT_BINARY_NODE'),

        /*
         * Path to the npm binary
         * mostly usually `/usr/bin/npm` or `C:\\nodejs\\npm.cmd` for Windows
         */

        'npm' => env('MUGSHOT_BINARY_NPM'),

        /*
         * Here you may specify your custom Node modules Path if you prefer not to use
         * the global installation of Puppeteer
         */

        'nodeModulesPath' => env('MUGSHOT_NODE_MODULES_PATH'),

        /*
         * Here you may specify your custom Chrome Executable
         */

        'chrome' => env('MUGSHOT_BINARY_CHROME'),

        /*
         * Here you may specify your custom Puppeteer Proxy Settings
         */

        'proxyServer' => env('MUGSHOT_PUPPETEER_PROXY'),

        /*
         * Allows you to disable the Sandbox Mode of Puppeteer
         * Keep in Mind: This is considered dangerous and should only be used if you trust the content you are opening.
         */

        'sandbox' => env('MUGSHOT_PUPPETEER_SANDBOX', true),

        /*
         * If you prefer to use a remote Chrome instance, such as a Docker instance, you can configure it here
         */

        'remoteChromeInstance' => [
            'host' => env('MUGSHOT_REMOTE_CHROME_HOST', ''),
            'port' => (int) env('MUGSHOT_REMOTE_CHROME_PORT', 9222),
        ]

    ]
];
