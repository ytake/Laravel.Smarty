<?php
class SmartyEngineTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Ytake\LaravelSmarty\Engines\SmartyEngine  */
    protected $engine;

    public function setUp()
    {
        parent::setUp();
        $fileSystem = new \Illuminate\Filesystem\Filesystem;
        $filePath = PATH;
        $fileLoad = new \Illuminate\Config\FileLoader($fileSystem, $filePath);
        $repo = new \Illuminate\Config\Repository($fileLoad, 'config');
        $repo->package('laravel-smarty', PATH, 'laravel-smarty');
        $viewFinder = new \Illuminate\View\FileViewFinder(
            $fileSystem,
            [$filePath . '/views'],
            ['.tpl']
        );
        $manager = new \Ytake\LaravelSmarty\SmartyManager(
            new \Illuminate\View\Engines\EngineResolver,
            $viewFinder,
            new \Illuminate\Events\Dispatcher,
            new Smarty,
            $repo
        );
        $this->engine = new \Ytake\LaravelSmarty\Engines\SmartyEngine($manager->getSmarty());
    }

    public function testInstance()
    {
        $this->assertInstanceOf("Ytake\LaravelSmarty\Engines\SmartyEngine", $this->engine);
    }

    public function testGet()
    {
        $this->assertSame('hello', $this->engine->get('test.tpl'));
        $this->assertSame('helloSmarty', $this->engine->get('test.tpl', ['value' => 'Smarty']));
    }

    /**
     * @expectedException \SmartyException
     */
    public function testException()
    {
        $this->engine->get('testing.tpl');
    }
}