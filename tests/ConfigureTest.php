<?php

class ConfigureTest extends SmartyTestCase
{

    protected function setUp()
    {
        parent::setUp();
        $filesystem = new \Illuminate\Filesystem\Filesystem;

        $items = $filesystem->getRequire(__DIR__ . '/config/config.php');
        $this->config->set('ytake-laravel-smarty', $items);
    }

    public function testHasConfigure()
    {
        $this->assertInternalType('array', $this->config->all());
        $config = $this->config->get('ytake-laravel-smarty');
        $this->assertInternalType('array', $config);
        $this->assertArrayHasKey('extension', $config);
        $this->assertArrayHasKey('debugging', $config);
        $this->assertArrayHasKey('caching', $config);
        $this->assertArrayHasKey('cache_lifetime', $config);
        $this->assertArrayHasKey('compile_check', $config);
        $this->assertArrayHasKey('left_delimiter', $config);
        $this->assertArrayHasKey('right_delimiter', $config);
        $this->assertArrayHasKey('template_path', $config);
        $this->assertArrayHasKey('cache_path', $config);
        $this->assertArrayHasKey('compile_path', $config);
        $this->assertArrayHasKey('plugins_paths', $config);
        $this->assertArrayHasKey('config_paths', $config);
        $this->assertArrayHasKey('force_compile', $config);
        $this->assertArrayHasKey('cache_driver', $config);
        $this->assertArrayHasKey('memcached', $config);
        $this->assertArrayHasKey('redis', $config);
    }
}