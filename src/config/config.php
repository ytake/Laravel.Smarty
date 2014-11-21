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
    'debugging' => false,

    // use cache
    'caching' => true,

    //
    'cache_lifetime' => 120,

    //
    'compile_check' => false,

    // delimiters
    // default "{$smarty}"
    'left_delimiter' => '{',
    'right_delimiter' => '}',

    // path info
    'template_path' => base_path() . '/app/views',

	'cache_path' => base_path() . '/app/storage/smarty/cache',

	'compile_path' => base_path() . '/app/storage/smarty/compile',

	'plugins_paths' => [
        base_path() . '/app/views/smarty/plugins',
    ],

	'config_paths' => [
        base_path() . '/app/config/smarty',
    ],

	// production false
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
