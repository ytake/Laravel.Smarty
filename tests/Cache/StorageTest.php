<?php

declare(strict_types=1);

namespace Tests\Cache;

use Illuminate\View\View;
use ReflectionException;
use SmartyException;
use Tests\SmartyTestCase;
use Throwable;
use Ytake\LaravelSmarty\Cache\Memcached;
use Ytake\LaravelSmarty\Cache\Redis;
use Ytake\LaravelSmarty\Cache\Storage;
use Ytake\LaravelSmarty\Smarty;

final class StorageTest extends SmartyTestCase
{
    /** @var  Storage */
    protected $storage;

    protected function setUp(): void
    {
        parent::setUp();
        $this->storage = new Storage(
            new Smarty(),
            $this->config
        );
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(Storage::class, $this->storage);
    }

    /**
     * @throws ReflectionException
     */
    public function testRedisDriver(): void
    {
        $reflection = $this->getProtectMethod($this->storage, 'redisStorage');
        $this->assertInstanceOf(Redis::class, $reflection->invoke($this->storage));
    }

    /**
     * @throws ReflectionException
     */
    public function testMemcachedDriver(): void
    {
        $reflection = $this->getProtectMethod($this->storage, 'memcachedStorage');
        $this->assertInstanceOf(Memcached::class, $reflection->invoke($this->storage));
    }

    /**
     * @throws SmartyException
     * @throws Throwable
     */
    public function testCacheableTemplate(): void
    {
        $smarty = new Smarty();
        $this->config->set('ytake-laravel-smarty.cache_driver', 'redis');
        $storage = new Storage(
            $smarty, $this->config
        );
        $storage->cacheStorageManaged();
        $this->assertInstanceOf(Storage::class, $this->storage);
        $this->assertSame($smarty->caching_type, 'redis');

        $this->config->set('ytake-laravel-smarty.cache_driver', 'redis');
        $this->config->set('ytake-laravel-smarty.caching', true);
        $this->factory->setSmartyConfigure();
        $this->factory->resolveSmartyCache();
        /** @var View $view */
        $view = $this->factory->make('test', ['value' => 'hello']);
        $this->assertSame('hellohello', $view->render());
        $smartyExtension = $this->factory->getSmarty()->ext;
        $class = $smartyExtension->clearCompiledTemplate;
        $class->clearCompiledTemplate($this->factory->getSmarty());
        $this->factory->getSmarty()->clearAllCache();
    }
}
