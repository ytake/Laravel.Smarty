<?php
namespace Ytake\LaravelSmarty;

use Illuminate\Support\ServiceProvider;

/**
 * Class SmartyConsoleServiceProvider
 * @package Ytake\LaravelSmarty
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 */
class SmartyConsoleServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // register commands
        $this->registerCommands();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.ytake.laravel-smarty.clear.compiled',
            'command.ytake.laravel-smarty.clear.cache',
            'command.ytake.laravel-smarty.optimize',
            'command.ytake.laravel-smarty.info',
        ];
    }

    /**
     * @return void
     */
    protected function registerCommands()
    {
        // Package Info command
        $this->app['command.ytake.laravel-smarty.info'] = $this->app->share(
            function () {
                return new Console\PackageInfoCommand;
            }
        );
        // cache clear
        $this->app['command.ytake.laravel-smarty.clear.cache'] = $this->app->share(function ($app) {
            return new Console\CacheClearCommand($app['view']->getSmarty());
        }
        );
        // clear compiled
        $this->app['command.ytake.laravel-smarty.clear.compiled'] = $this->app->share(function ($app) {
            return new Console\ClearCompiledCommand($app['view']->getSmarty());
        }
        );
        // clear compiled
        $this->app['command.ytake.laravel-smarty.optimize'] = $this->app->share(function ($app) {
            return new Console\OptimizeCommand($app['view']->getSmarty(), $app['config']);
        }
        );
        $this->commands(
            [
                'command.ytake.laravel-smarty.clear.compiled',
                'command.ytake.laravel-smarty.clear.cache',
                'command.ytake.laravel-smarty.optimize',
                'command.ytake.laravel-smarty.info',
            ]
        );
    }

}