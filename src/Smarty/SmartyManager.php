<?php
namespace Ytake\LaravelSmarty;

use Smarty;
use ReflectionClass;
use Illuminate\View\Factory;
use Illuminate\Config\Repository;
use Illuminate\Events\Dispatcher;
use Illuminate\View\ViewFinderInterface;
use Illuminate\View\Engines\EngineResolver;
use Ytake\LaravelSmarty\Exception\MethodNotFoundException;

/**
 * Class SmartyManager
 * @package Ytake\LaravelSmarty
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class SmartyManager extends Factory
{

    /**
     * @var string  version
     */
    const VERSION = '1.2.0';

    /** @var Smarty $smarty */
    protected $smarty;

    /** @var Repository $config */
    protected $config;

    /**
     * @param EngineResolver $engines
     * @param ViewFinderInterface $finder
     * @param Dispatcher $events
     * @param Smarty $smarty
     * @param Repository $config
     */
    public function __construct(
        EngineResolver $engines,
        ViewFinderInterface $finder,
        Dispatcher $events,
        Smarty $smarty,
        Repository $config
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
        $this->smarty->left_delimiter = $this->config->get('laravel-smarty::left_delimiter');
        $this->smarty->right_delimiter = $this->config->get('laravel-smarty::right_delimiter');
        $this->smarty->setTemplateDir($this->config->get('laravel-smarty::template_path'));
        $this->smarty->setCompileDir($this->config->get('laravel-smarty::compile_path'));
        $this->smarty->setCacheDir($this->config->get('laravel-smarty::cache_path'));
        $this->smarty->setConfigDir($this->config->get('laravel-smarty::config_paths'));

        foreach($this->config->get('laravel-smarty::plugins_paths', []) as $plugins) {
            $this->smarty->addPluginsDir($plugins);
        }

        $this->smarty->debugging = $this->config->get('laravel-smarty::debugging');
        $this->smarty->caching = $this->config->get('laravel-smarty::caching');
        $this->smarty->cache_lifetime = $this->config->get('laravel-smarty::cache_lifetime');
        $this->smarty->compile_check = $this->config->get('laravel-smarty::compile_check');
        $this->smarty->force_compile = $this->config->get('laravel-smarty::force_compile', false);
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
