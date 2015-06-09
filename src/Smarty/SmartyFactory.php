<?php
namespace Ytake\LaravelSmarty;

use Smarty;
use ReflectionClass;
use Illuminate\View\Factory;
use Illuminate\View\ViewFinderInterface;
use Illuminate\View\Engines\EngineResolver;
use Ytake\LaravelSmarty\Exception\MethodNotFoundException;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;

/**
 * Class SmartyManager
 * @package Ytake\LaravelSmarty
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class SmartyFactory extends Factory
{

    /**
     * @var string  version
     */
    const VERSION = '2.1.0';

    /** @var Smarty $smarty */
    protected $smarty;

    /** @var ConfigContract $config */
    protected $config;

    /**
     * @param EngineResolver $engines
     * @param ViewFinderInterface $finder
     * @param DispatcherContract $events
     * @param Smarty $smarty
     * @param ConfigContract $config
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
        $this->setConfigure();
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
     * smarty configure
     * @access private
     * @return void
     */
    private function setConfigure()
    {
        $this->smarty->left_delimiter = $this->config->get('ytake-laravel-smarty.left_delimiter');
        $this->smarty->right_delimiter = $this->config->get('ytake-laravel-smarty.right_delimiter');
        $this->smarty->setTemplateDir($this->config->get('ytake-laravel-smarty.template_path'));
        $this->smarty->setCompileDir($this->config->get('ytake-laravel-smarty.compile_path'));
        $this->smarty->setCacheDir($this->config->get('ytake-laravel-smarty.cache_path'));
        $this->smarty->setConfigDir($this->config->get('ytake-laravel-smarty.config_paths'));

        foreach($this->config->get('ytake-laravel-smarty.plugins_paths', []) as $plugins) {
            $this->smarty->addPluginsDir($plugins);
        }

        $this->smarty->debugging = $this->config->get('ytake-laravel-smarty.debugging');
        $this->smarty->caching = $this->config->get('ytake-laravel-smarty.caching');
        $this->smarty->cache_lifetime = $this->config->get('ytake-laravel-smarty.cache_lifetime');
        $this->smarty->compile_check = $this->config->get('ytake-laravel-smarty.compile_check');
        $this->smarty->force_compile = $this->config->get('ytake-laravel-smarty.force_compile', false);
        $this->smarty->error_reporting = E_ALL &~ E_NOTICE;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws MethodNotFoundException
     */
    public function __call($name, $arguments)
    {
        $reflectionClass = new ReflectionClass($this->smarty);
        if(!$reflectionClass->hasMethod($name)) {
            throw new MethodNotFoundException("{$name} : Method Not Found");
        }
        return call_user_func_array([$this->smarty, $name], $arguments);
    }
}
