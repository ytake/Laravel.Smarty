<?php
namespace Ytake\LaravelSmarty;

use Smarty;
use Illuminate\Support\ServiceProvider;
use Ytake\LaravelSmarty\Cache\Storage;
use Illuminate\Contracts\Config\Repository as ConfigContract;

/**
 * Class LaravelSmartyServiceProvider
 * @package Ytake\LaravelSmarty
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class SmartyServiceProvider extends ServiceProvider
{

    protected $packageName = "ytake.laravel-smarty";

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
        /** @var \Illuminate\Filesystem\Filesystem $fileSystem */
        $fileSystem = $this->app['files'];
        $this->app->instance('ytake.laravel-smarty.config', __DIR__ . '/config/config.php');
        config([
            $this->packageName => $fileSystem->getRequire(
                $this->app->make('ytake.laravel-smarty.config')
            )
        ]);
        /** @var ConfigContract $configure */
        $this->app['config']->set($this->packageName, $this->getApplicationPackagePath());
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
            $this->app['config']->get($this->packageName . '.extension', 'tpl'),
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
            'command.ytake.laravel-smarty.publish'
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
        // Package Info command
        $this->app['command.ytake.laravel-smarty.publish'] = $this->app->share(
            function ($app) {
                return new Console\PublishCommand($app);
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
                'command.ytake.laravel-smarty.publish',
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

    /**
     * @return mixed
     */
    protected function getApplicationPackagePath()
    {
        try {
            $configure = $this->app['files']->getRequire(
                $this->app->configPath()
                    . "/" . str_replace('.', '/', $this->packageName) . "/config.php"
            );
            return append_config($configure);
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            return append_config([]);
        }
    }
}
