<?php

use Ytake\LaravelSmarty\Smarty;

class StorageTest extends SmartyTestCase
{
    /** @var  \Ytake\LaravelSmarty\Cache\Storage */
    protected $storage;
    /** @var  \Illuminate\Config\Repository */
    protected $repositopry;

    protected function setUp()
    {
        parent::setUp();
        $this->storage = new \Ytake\LaravelSmarty\Cache\Storage(
            new Smarty(), $this->config
        );
    }

    public function testInstance()
    {
        $this->assertInstanceOf("Ytake\\LaravelSmarty\\Cache\\Storage", $this->storage);
    }

    public function testRedisDriver()
    {
        $reflection = $this->getProtectMethod($this->storage, 'redisStorage');
        $this->assertInstanceOf("Ytake\\LaravelSmarty\\Cache\\Redis", $reflection->invoke($this->storage));
    }

    public function testMemcachedDriver()
    {
        $reflection = $this->getProtectMethod($this->storage, 'memcachedStorage');
        $this->assertInstanceOf("Ytake\\LaravelSmarty\\Cache\\Memcached", $reflection->invoke($this->storage));
    }

    public function testCacheableTemplate()
    {
        $smarty = new Smarty();
        $this->config->set('ytake-laravel-smarty.cache_driver', 'redis');
        $storage = new \Ytake\LaravelSmarty\Cache\Storage(
            $smarty, $this->config
        );
        $storage->cacheStorageManaged();
        $this->assertInstanceOf("Ytake\\LaravelSmarty\\Cache\\Storage", $this->storage);
        $this->assertSame($smarty->caching_type, 'redis');

        $this->config->set('ytake-laravel-smarty.cache_driver', 'redis');
        $this->config->set('ytake-laravel-smarty.caching', true);
        $this->factory->setSmartyConfigure();
        $this->factory->resolveSmartyCache();
        /** @var Illuminate\View\View $view */
        $view = $this->factory->make('test', ['value' => 'hello']);
        $this->assertSame('hellohello', $view->render());

        $smartyExtension = $this->factory->getSmarty()->ext;
        $class = $smartyExtension->clearCompiledTemplate;
        $class->clearCompiledTemplate($this->factory->getSmarty());
        $this->factory->getSmarty()->clearAllCache();
    }
}
