<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Homepage Redirect
    |--------------------------------------------------------------------------
    |
    | Redirect to a page of your choice if something calls mugshot directly.
    |
    */

    'redirect' => env('MUGSHOT_HOMEPAGE_REDIRECT'),

    /*
    |--------------------------------------------------------------------------
    | Cache Time
    |--------------------------------------------------------------------------
    |
    | How long we should keep screenshots before they are recreated again.
    | Value in minutes.
    |
    */

    'cache' => (int) env('MUGSHOT_CACHE_TIME', 30),

    /*
    |--------------------------------------------------------------------------
    | Screenshot Timeout
    |--------------------------------------------------------------------------
    |
    | Specifies when the screenshot process should timeout.
    | Value in seconds.
    |
    */

    'timeout' => (int) env('MUGSHOT_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Default Values
    |--------------------------------------------------------------------------
    |
    | These values control the defaults for random requests.
    |
    */

    'defaults' => [

        // Picture width of screenshot.
        'width' => 1280,

        // Picture height of screenshot.
        'height' => 800,

        // Allows you to capture the entire page. Overwrites the width and height value.
        'fullPage' => false,

        // Allows the change of device scale.
        'deviceScale' => 1,

        // Caution: Quality is unsupported with png screenshots. Value is percent (int).
        'quality' => 70,

        // Delay before capturing a screenshot. Useful for heavily bloated pages or slow servers. Value in seconds.
        'delay' => 1,

        // Supported: "jpeg", "png"
        'fileExtension' => 'jpeg',

    ],

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    'validation' => [

        // Max width of a screenshot.
        'maxWidth' => 1280,

        // Max height of a screenshot.
        'maxHeight' => 800,

        // Max delay of a screenshot. Value in seconds.
        'maxDelay' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Request
    |--------------------------------------------------------------------------
    */

    'request' => [

        // If you want to specify a custom user agent, here is the opportunity to do so.
        'useragent' => 'mugshot screenshot service',

    ],

    /*
    |--------------------------------------------------------------------------
    | Puppeteer
    |--------------------------------------------------------------------------
    */

    'puppeteer' => [

        // Path to the Node.js binary. Usually `/usr/bin/node` or `C:\\nodejs\\node.exe` for Windows.
        'node' => env('MUGSHOT_BINARY_NODE'),

        // Path to the npm binary. Usually `/usr/bin/npm` or `C:\\nodejs\\npm.cmd` for Windows.
        'npm' => env('MUGSHOT_BINARY_NPM'),

        // Here you may specify your custom Node modules path if you prefer not to use the global installation of Puppeteer.
        'nodeModulesPath' => env('MUGSHOT_NODE_MODULES_PATH'),

        // Here you may specify your custom Chrome executable.
        'chrome' => env('MUGSHOT_BINARY_CHROME'),

        // Here you may specify your custom Puppeteer proxy settings.
        'proxyServer' => env('MUGSHOT_PUPPETEER_PROXY'),

        // Allows you to disable the sandbox mode of Puppeteer.
        // Keep in mind: This is considered dangerous and should only be used if you trust the content you are opening.
        'sandbox' => env('MUGSHOT_PUPPETEER_SANDBOX', true),

        // If you prefer to use a remote Chrome instance, such as a Docker instance, you can configure it here.
        'remoteChromeInstance' => [
            'host' => env('MUGSHOT_REMOTE_CHROME_HOST', ''),
            'port' => (int) env('MUGSHOT_REMOTE_CHROME_PORT', 9222),
        ],

    ],
];
