<?php
use Mockery as m;
class StorageTest extends TestCase
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

	public function tearDown()
	{
		m::close();
	}

	public function testInstance()
	{
		$this->assertInstanceOf("Ytake\LaravelSmarty\Cache\Storage", $this->storage);
	}

	public function testRedisDriver()
	{
		$reflection = $this->getProtectMethod($this->storage, 'redisStorage');
		$this->assertInstanceOf("Ytake\LaravelSmarty\Cache\Redis", $reflection->invoke($this->storage));
	}

	public function testMemcachedDriver()
	{
		$storageMock = m::mock($this->storage);
		$storageMock->makePartial()->shouldAllowMockingProtectedMethods();
		$storageMock->shouldReceive("memcachedStorage")->andReturn("Ytake\LaravelSmarty\Cache\Memcached");
		$reflection = $this->getProtectMethod($storageMock, 'memcachedStorage');
		$this->assertEquals("Ytake\LaravelSmarty\Cache\Memcached", $reflection->invoke($storageMock));
	}

	public function testCacheDriver()
	{
		$smarty = new Smarty();
		$this->config->set('ytake-laravel-smarty.cache_driver', 'redis');
		$storage = new \Ytake\LaravelSmarty\Cache\Storage(
			$smarty, $this->config
		);
		$this->assertInstanceOf("Ytake\LaravelSmarty\Cache\Storage", $this->storage);
		$this->assertSame($smarty->caching_type, 'redis');
	}
}
