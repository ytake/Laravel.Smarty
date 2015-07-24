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
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
        // register template cache driver
        $this->registerCacheStorage();
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
     * @return void
     */
    protected function registerCacheStorage()
    {
        $cacheStorage = new Storage($this->app['view']->getSmarty(), $this->app['config']);
        $cacheStorage->cacheStorageManaged();
    }


}
