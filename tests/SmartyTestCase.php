<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Events\Dispatcher;
use Illuminate\View\Engines\EngineResolver;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use SmartyException;
use Ytake\LaravelSmarty\Engines\SmartyEngine;
use Ytake\LaravelSmarty\Smarty;
use Ytake\LaravelSmarty\SmartyFactory;

/**
 * Class SmartyTestCase
 */
class SmartyTestCase extends TestCase
{
    /** @var SmartyFactory $factory */
    protected $factory;

    /** @var Repository $config */
    protected $config;

    /** @var Dispatcher */
    protected $events;

    /**
     * @throws FileNotFoundException|SmartyException
     */
    protected function setUp(): void
    {
        $this->config = new Repository();
        $filesystem = new \Illuminate\Filesystem\Filesystem();
        $this->events = new Dispatcher();
        $items = $filesystem->getRequire(__DIR__ . '/config/config.php');
        $this->config->set("ytake-laravel-smarty", $items);
        $viewFinder = new \Illuminate\View\FileViewFinder(
            $filesystem,
            [__DIR__ . '/views'],
            ['.tpl']
        );
        $smarty = new Smarty();
        $this->factory = new SmartyFactory(
            new EngineResolver(),
            $viewFinder,
            $this->events,
            $smarty,
            $this->config
        );
        $this->factory->setSmartyConfigure();
        $this->factory->resolveSmartyCache();
        $smarty->setViewFactory($this->factory);
        $extension = $this->config->get('ytake-laravel-smarty.extension', 'tpl');
        $this->factory->addExtension($extension, 'smarty', function () {
            // @codeCoverageIgnoreStart
            return new SmartyEngine($this->factory->getSmarty());
            // @codeCoverageIgnoreEnd
        });
    }

    /**
     * @param object $class
     * @param string $name
     * @return ReflectionMethod
     * @throws ReflectionException
     */
    protected function getProtectMethod(object $class, string $name): ReflectionMethod
    {
        $class = new ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
