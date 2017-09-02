<?php

use Ytake\LaravelSmarty\Smarty;

class SmartyTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var \Ytake\LaravelSmarty\SmartyFactory $factory */
    protected $factory;

    /** @var \Illuminate\Config\Repository $config */
    protected $config;

    /** @var Illuminate\Events\Dispatcher */
    protected $events;

    protected function setUp()
    {
        $this->config = new \Illuminate\Config\Repository();
        $filesystem = new \Illuminate\Filesystem\Filesystem;
        $this->events = new \Illuminate\Events\Dispatcher;
        $items = $filesystem->getRequire(__DIR__ . '/config/config.php');
        $this->config->set("ytake-laravel-smarty", $items);

        new \Illuminate\Config\Repository();
        $viewFinder = new \Illuminate\View\FileViewFinder(
            $filesystem,
            [__DIR__ . '/views'],
            ['.tpl']
        );
        $smarty = new Smarty;
        $this->factory = new \Ytake\LaravelSmarty\SmartyFactory(
            new \Illuminate\View\Engines\EngineResolver,
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
            return new \Ytake\LaravelSmarty\Engines\SmartyEngine($this->factory->getSmarty());
            // @codeCoverageIgnoreEnd
        });
    }

    /**
     * @param $class
     * @param $name
     * @return \ReflectionMethod
     */
    protected function getProtectMethod($class, $name)
    {
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    /**
     * @param $class
     * @param $name
     * @return \ReflectionProperty
     */
    protected function getProtectProperty($class, $name)
    {
        $class = new \ReflectionClass($class);
        $property = $class->getProperty($name);
        $property->setAccessible(true);
        return $property;
    }

}

class MockApplication extends \Illuminate\Container\Container implements \Illuminate\Contracts\Foundation\Application
{
    public function runningInConsole()
    {
        // TODO: Implement runningInConsole() method.
    }

    public function getCachedPackagesPath()
    {
        // TODO: Implement getCachedPackagesPath() method.
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version()
    {
        // TODO: Implement version() method.
    }

    /**
     * Get or check the current application environment.
     *
     * @param  mixed
     * @return string
     */
    public function environment()
    {
        // TODO: Implement environment() method.
    }

    /**
     * Determine if the application is currently down for maintenance.
     *
     * @return bool
     */
    public function isDownForMaintenance()
    {
        // TODO: Implement isDownForMaintenance() method.
    }

    /**
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerConfiguredProviders()
    {
        // TODO: Implement registerConfiguredProviders() method.
    }

    /**
     * Register a service provider with the application.
     *
     * @param  \Illuminate\Support\ServiceProvider|string $provider
     * @param  array $options
     * @param  bool $force
     * @return \Illuminate\Support\ServiceProvider
     */
    public function register($provider, $options = [], $force = false)
    {
        // TODO: Implement register() method.
    }

    /**
     * Register a deferred provider and service.
     *
     * @param  string $provider
     * @param  string $service
     * @return void
     */
    public function registerDeferredProvider($provider, $service = null)
    {
        // TODO: Implement registerDeferredProvider() method.
    }

    /**
     * Boot the application's service providers.
     *
     * @return void
     */
    public function boot()
    {
        // TODO: Implement boot() method.
    }

    /**
     * Register a new boot listener.
     *
     * @param  mixed $callback
     * @return void
     */
    public function booting($callback)
    {
        // TODO: Implement booting() method.
    }

    /**
     * Register a new "booted" listener.
     *
     * @param  mixed $callback
     * @return void
     */
    public function booted($callback)
    {
        // TODO: Implement booted() method.
    }

    /**
     * Get the base path of the Laravel installation.
     *
     * @return string
     */
    public function basePath()
    {
        // TODO: Implement basePath() method.
    }

    /**
     * Get the path to the cached "compiled.php" file.
     *
     * @return string
     */
    public function getCachedCompilePath()
    {
        // TODO: Implement getCachedCompilePath() method.
    }

    /**
     * Get the path to the cached services.json file.
     *
     * @return string
     */
    public function getCachedServicesPath()
    {
        // TODO: Implement getCachedServicesPath() method.
    }
}

function base_path()
{
    return null;
}

function storage_path()
{
    return null;
}