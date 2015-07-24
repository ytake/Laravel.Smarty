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
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/ytake-laravel-smarty.php';
        $this->mergeConfigFrom($configPath, 'ytake-laravel-smarty');
        $this->publishes([$configPath => config_path('ytake-laravel-smarty.php')]);

        $this->app->singleton('view', function ($app) {
            $factory = new SmartyFactory(
                $app['view.engine.resolver'],
                $app['view.finder'],
                $app['events'],
                new Smarty,
                $this->app['config']
            );

            // Pass the container to the factory so it can be used to resolve view composers.
            $factory->setContainer($app);

            $factory->share('app', $app);
            // add Smarty Extension
            $factory->addSmartyExtension();
            // resolve cache storage
            $factory->resolveSmartyCache();
            // smarty configure(use ytake-laravel-smarty.php)
            $factory->setSmartyConfigure();

            return $factory;
        });

    }

}
