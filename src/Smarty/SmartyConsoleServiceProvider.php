<?php
/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Ytake\LaravelSmarty;

use Illuminate\Support\ServiceProvider;

/**
 * Class SmartyConsoleServiceProvider
 *
 * @package Ytake\LaravelSmarty
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class SmartyConsoleServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function boot()
    {
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
            return new Console\CacheClearCommand($app['view']->getSmarty());

        });

        // clear compiled
        $this->app->singleton('command.ytake.laravel-smarty.clear.compiled', function ($app) {
            return new Console\ClearCompiledCommand($app['view']->getSmarty());
        });

        // clear compiled
        $this->app->singleton('command.ytake.laravel-smarty.optimize', function ($app) {
            return new Console\OptimizeCommand($app['view']->getSmarty(), $app['config']);
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
