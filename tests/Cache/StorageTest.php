<?php
use Mockery as m;
class StorageTest extends \PHPUnit_Framework_TestCase
{
	/** @var  \Ytake\LaravelSmarty\Cache\Storage */
	protected $storage;
	/** @var  \Illuminate\Config\Repository */
	protected $repositopry;
	public function setUp()
	{
		parent::setUp();
		$fileSystem = new \Illuminate\Filesystem\Filesystem;
		$array['ytake.laravel-smarty'] = $fileSystem->getRequire(PATH . '/config/config.php');
		$config = new \Illuminate\Config\Repository($array);
		var_dump($config);
		$this->storage = new \Ytake\LaravelSmarty\Cache\Storage(
			new Smarty(), $config
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