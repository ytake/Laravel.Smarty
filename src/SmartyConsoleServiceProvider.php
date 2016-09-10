<?php

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 *
 * Copyright (c) 2014-2016 Yuuki Takezawa
 *
 */
namespace Ytake\LaravelSmarty;

use Illuminate\Support\ServiceProvider;

/**
 * Class SmartyConsoleServiceProvider
 *
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class SmartyConsoleServiceProvider extends ServiceProvider
{
    /** @var bool  */
    protected $defer = true;

    /**
     * @return void
     */
    public function boot()
    {
        // register commands
        $this->registerCommands();
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
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
        $this->app->singleton('command.ytake.laravel-smarty.info', function () {
            return new Console\PackageInfoCommand;
        });

        // cache clear
        $this->app->singleton('command.ytake.laravel-smarty.clear.cache', function ($app) {
            return new Console\CacheClearCommand($app['smarty.view']->getSmarty());
        });

        // clear compiled
        $this->app->singleton('command.ytake.laravel-smarty.clear.compiled', function ($app) {
            return new Console\ClearCompiledCommand($app['smarty.view']);
        });

        // clear compiled
        $this->app->singleton('command.ytake.laravel-smarty.optimize', function ($app) {
            return new Console\OptimizeCommand($app['smarty.view']->getSmarty(), $app['config']);
        });

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
