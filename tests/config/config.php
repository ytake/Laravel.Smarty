<?php

return [
    // smarty file extension
    'extension' => 'tpl',
    //
    'debugging' => false,
    // use cache
    'caching' => false,
    //
    'cache_lifetime' => 120,
    //
    'compile_check' => false,
    // delimiters
    // default "{$smarty}"
    'left_delimiter' => '{',
    'right_delimiter' => '}',
    // path info
    'template_path' => PATH . '/views',
    'cache_path' => PATH . '/storage/smarty/cache',
    'compile_path' => PATH . '/storage/smarty/compile',
    'plugins_paths' => [
        PATH . '/views/plugins',
    ],
    'config_paths' => [
        null
    ],
    'force_compile' => true,

    // smarty cache driver "file", "memcached", "redis"
    'cache_driver' => 'file',

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
