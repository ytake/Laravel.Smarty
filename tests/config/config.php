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
	'plugins_paths' => array(
        PATH . '/views/plugins',
    ),
    'config_paths' => array(
        PATH . '/app/config/smarty',
    ),
    'force_compile' => true
];