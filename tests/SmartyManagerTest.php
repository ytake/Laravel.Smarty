<?php

class SmartyManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Ytake\LaravelSmarty\SmartyManager  */
    protected $manager;

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
        $this->manager = new \Ytake\LaravelSmarty\SmartyManager(
            new \Illuminate\View\Engines\EngineResolver,
            $viewFinder,
            new \Illuminate\Events\Dispatcher,
            new Smarty,
            $repo
        );
    }

    public function testInstance()
    {
        $this->assertInstanceOf("Ytake\LaravelSmarty\SmartyManager", $this->manager);
    }

    public function testSmarty()
    {
        $this->assertInstanceOf("Smarty", $this->manager->getSmarty());
    }

    public function testVersion()
    {
        $this->assertSame('1.2.0', $this->manager->getVersion());
    }

    public function testConfigure()
    {
        $reflectionMethod = $this->getProtectMethod($this->manager, 'setConfigure');
        $this->assertNull($reflectionMethod->invoke($this->manager));
        $smarty = $this->manager->getSmarty();
        foreach($smarty->getTemplateDir() as $dir) {
            $this->assertSame(true, file_exists($dir));
        }
    }

    /**
     * @expectedException \Ytake\LaravelSmarty\Exception\MethodNotFoundException
     */
    public function testUndefinedFunction()
    {
        $this->manager->hello();
        $this->manager->assing();
        $this->manager->smarty([1 => 2]);
    }

    public function testPlugins()
    {
        $this->manager->assign('value', 'hello');
        $this->assertSame('test', $this->manager->fetch('plugin_test.tpl'));
    }

    public function testClearFile()
    {
        $this->manager->clearCompiledTemplate();
        $this->assertSame(0, count($this->scan()));
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

    public function scan()
    {
        $files = [];
        $dir = opendir(PATH . '/storage/smarty/compile');
        while($file = readdir($dir))
        {
            if($file != '.' && $file != '..' && $file != '.gitkeep')
            {
                $files[] = $file;
            }
        }
        closedir($dir);
        return $files;
    }
}