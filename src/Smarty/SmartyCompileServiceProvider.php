<?php
namespace Ytake\LaravelSmarty;

use Illuminate\Support\ServiceProvider;

/**
 * Class SmartyCompileServiceProvider
 * @package Ytake\LaravelSmarty
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 */
class SmartyCompileServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * for Laravel performance optimize
     * @return array
     */
    public static function compiles()
    {
        return [
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/Cache/Memcached.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/Cache/Redis.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/Cache/Storage.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/Console/CacheClearCommand.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/Console/ClearCompiledCommand.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/Console/OptimizeCommand.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/SmartyFactory.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/Engines/SmartyEngine.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/SmartyServiceProvider.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/SmartyConsoleServiceProvider.php',
            base_path() . '/vendor/smarty/smarty/libs/Smarty.class.php',
            base_path() . '/vendor/smarty/smarty/libs/Autoloader.php',
        ];
    }
}