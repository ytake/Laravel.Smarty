<?php
/**
 * Smarty configure
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
return [

    // smarty file extension
    'extension' => 'tpl',

    //
    'debugging' => env('SMARTY_DEBUG',false),

    // use cache
    'caching' => env('SMARTY_CACHING', false),

    //
    'cache_lifetime' => env('SMARTY_CACHE_LIFE', 120),

    //
    'compile_check' => env('SMARTY_COMPILE_CHECK', false),

    // delimiters
    // default "{$smarty}"
    'left_delimiter' => '{',
    'right_delimiter' => '}',

    // path info
    'template_path' => base_path() . '/resources/views',

    // smarty cache directory
    'cache_path' => storage_path() . '/framework/smarty/cache',

    // smarty template compiler
    'compile_path' => storage_path() . '/framework/smarty/compile',

    // smarty plugins
    'plugins_paths' => [
        base_path() . '/resources/smarty/plugins',
    ],

    // somarty configure
    'config_paths' => [
        base_path() . '/resources/smarty/config',
    ],

    /**
     * for develop true
     * for production false
     */
    'force_compile' => env('SMARTY_FORCE_COMPILE', true),

    // smarty cache driver "file", "memcached", "redis"
    'cache_driver' => env('SMARTY_CACHE_DRIVER', 'file'),

    // memcached servers
    'memcached' => [
        [
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100
        ],
    ],

    // redis configure
    'redis' => [
        [
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ],
    ],
];
