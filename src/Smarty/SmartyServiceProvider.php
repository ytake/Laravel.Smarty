<?php
namespace Ytake\LaravelSmarty;

use Smarty;
use Ytake\LaravelSmarty\Cache\Storage;
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
     * boot process
     */
    public function boot()
    {
        // register template cache driver
        $this->registerCacheStorage();
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
        $configPath = __DIR__ . '/../config/ytake-laravel-smarty.php';
        $this->mergeConfigFrom($configPath, 'ytake-laravel-smarty');
        $this->publishes([$configPath => config_path('ytake-laravel-smarty.php')]);

        $this->app['view'] = $this->app->share(
            function ($app) {
                $factory = new SmartyFactory(
                    $app['view.engine.resolver'],
                    $app['view.finder'],
                    $app['events'],
                    new Smarty,
                    $this->app['config']
                );

                // Pass the container to the factory so it can be used to resolve view composers.
                $factory->setContainer($app);

                return $factory;
            }
        );

        // add smarty extension (.tpl)
        $this->app['view']->addExtension(
            $this->app['config']->get('laravel-smarty.extension', 'tpl'),
            'smarty',
            function () {
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

    /**
     * @return Storage
     */
    protected function registerCacheStorage()
    {
        return new Storage($this->app['view']->getSmarty(), $this->app['config']);
    }

}
