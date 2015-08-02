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

use Smarty;
use ReflectionClass;
use Illuminate\View\Factory;
use Illuminate\View\ViewFinderInterface;
use Illuminate\View\Engines\EngineResolver;
use Ytake\LaravelSmarty\Cache\Storage;
use Ytake\LaravelSmarty\Exception\MethodNotFoundException;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;

/**
 * Class SmartyManager
 *
 * @package Ytake\LaravelSmarty
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class SmartyFactory extends Factory
{

    /**
     * @var string  version
     */
    const VERSION = '2.1.3';

    /** @var Smarty $smarty */
    protected $smarty;

    /** @var ConfigContract $config */
    protected $config;


    /**
     * @param EngineResolver      $engines
     * @param ViewFinderInterface $finder
     * @param DispatcherContract  $events
     * @param Smarty              $smarty
     * @param ConfigContract      $config
     */
    public function __construct(
        EngineResolver $engines,
        ViewFinderInterface $finder,
        DispatcherContract $events,
        Smarty $smarty,
        ConfigContract $config
    ) {
        parent::__construct($engines, $finder, $events);
        $this->smarty = $smarty;
        $this->config = $config;
    }

    /**
     * @return \Smarty
     */
    public function getSmarty()
    {
        return $this->smarty;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * @return void
     */
    public function addSmartyExtension()
    {
        $extension = $this->config->get('ytake-laravel-smarty.extension', 'tpl');
        $this->addExtension($extension, 'smarty', function () {
            return new Engines\SmartyEngine($this->getSmarty());
        });
    }

    /**
     * @return void
     */
    public function resolveSmartyCache()
    {
        $cacheStorage = new Storage($this->getSmarty(), $this->config);
        $cacheStorage->cacheStorageManaged();
    }

    /**
     * smarty configure
     *
     * @access private
     * @return void
     */
    public function setSmartyConfigure()
    {
        $this->smarty->left_delimiter = $this->config->get('ytake-laravel-smarty.left_delimiter');
        $this->smarty->right_delimiter = $this->config->get('ytake-laravel-smarty.right_delimiter');
        $this->smarty->setTemplateDir($this->config->get('ytake-laravel-smarty.template_path'));
        $this->smarty->setCompileDir($this->config->get('ytake-laravel-smarty.compile_path'));
        $this->smarty->setCacheDir($this->config->get('ytake-laravel-smarty.cache_path'));
        $this->smarty->setConfigDir($this->config->get('ytake-laravel-smarty.config_paths'));

        foreach ($this->config->get('ytake-laravel-smarty.plugins_paths', []) as $plugins) {
            $this->smarty->addPluginsDir($plugins);
        }

        $this->smarty->debugging = $this->config->get('ytake-laravel-smarty.debugging');
        $this->smarty->caching = $this->config->get('ytake-laravel-smarty.caching');
        $this->smarty->cache_lifetime = $this->config->get('ytake-laravel-smarty.cache_lifetime');
        $this->smarty->compile_check = $this->config->get('ytake-laravel-smarty.compile_check');
        $this->smarty->force_compile = $this->config->get('ytake-laravel-smarty.force_compile', false);
        $this->smarty->error_reporting = E_ALL & ~E_NOTICE;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws MethodNotFoundException
     */
    public function __call($name, $arguments)
    {
        $reflectionClass = new ReflectionClass($this->smarty);
        if (!$reflectionClass->hasMethod($name)) {
            throw new MethodNotFoundException("{$name} : Method Not Found");
        }
        return call_user_func_array([$this->smarty, $name], $arguments);
    }
}
