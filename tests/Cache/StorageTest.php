<?php

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
		$filePath = PATH;
		$fileLoad = new \Illuminate\Config\FileLoader($fileSystem, $filePath);
		$this->repositopry = new \Illuminate\Config\Repository($fileLoad, 'config');
		$this->repositopry->package('laravel-smarty', PATH, 'laravel-smarty');
		$this->storage = new \Ytake\LaravelSmarty\Cache\Storage(
			new Smarty(), $this->repositopry
		);
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
		$reflection = $this->getProtectMethod($this->storage, 'memcachedStorage');
		$this->assertInstanceOf("Ytake\LaravelSmarty\Cache\Memcached", $reflection->invoke($this->storage));
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