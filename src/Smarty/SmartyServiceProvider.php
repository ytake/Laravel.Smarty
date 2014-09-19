<?php
namespace Ytake\LaravelSmarty;

use Smarty;
use Illuminate\Support\ServiceProvider;

/**
 * Class LaravelSmartyServiceProvider
 * @package Ytake\LaravelSmarty
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class SmartyServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * boot process
     */
    public function boot()
    {
        $this->package('ytake/laravel-smarty');
        // register commands
        $this->registerCommands();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app['config']->package('ytake/laravel-smarty', __DIR__ . '/../config');

        $this->app['view'] = $this->app->share(
            function ($app) {
                return new SmartyManager(
                    $app['view.engine.resolver'],
                    $app['view.finder'],
                    $app['events'],
                    new Smarty,
                    $this->app['config']
                );
            }
        );

        // add smarty extension (.tpl)
        $this->app['view']->addExtension(
            $this->app['config']->get('laravel-smarty::extension', 'tpl'),
            'smarty',
            function() {
                return new Engines\SmartyEngine($this->app['view']->getSmarty());
            }
        );
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
     *
     */
    public function registerCommands()
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
                return new Console\CompiledClearCommand($app['view']->getSmarty());
            }
        );
        // clear compiled
        $this->app['command.ytake.laravel-smarty.optimize'] = $this->app->share(function ($app) {
                return new Console\CompiledCommand($app['view']->getSmarty(), $app['config']);
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
