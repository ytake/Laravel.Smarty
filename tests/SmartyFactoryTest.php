<?php

class SmartyManagerFactory extends SmartyTestCase
{

    protected function setUp()
    {
        parent::setUp();
    }

    public function testInstance()
    {
        $this->assertInstanceOf("Ytake\LaravelSmarty\SmartyFactory", $this->factory);
    }

    public function testSmarty()
    {
        $this->assertInstanceOf("Smarty", $this->factory->getSmarty());
        $this->assertNotTrue($this->factory->getVersion());
    }

    public function testConfigure()
    {
        $smarty = $this->factory->getSmarty();
        foreach($smarty->getTemplateDir() as $dir) {
            $this->assertSame(true, file_exists($dir));
        }
    }


    public function testUndefinedFunction()
    {
        $this->setExpectedException('Ytake\LaravelSmarty\Exception\MethodNotFoundException');
        $this->factory->hello();
        $this->factory->assing();
        $this->factory->smarty([1 => 2]);
    }

    public function testPlugins()
    {
        $this->factory->assign('value', 'hello');
        $this->assertSame('test', $this->factory->fetch('plugin_test.tpl'));
    }

    public function testClearFile()
    {
        $smartyExtension = $this->factory->getSmarty()->ext;
        $class = $smartyExtension->clearCompiledTemplate;
        $class->clearCompiledTemplate($this->factory->getSmarty());
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
        $dir = opendir(__DIR__ . '/storage/smarty/compile');
        while($file = readdir($dir)) {
            if($file != '.' && $file != '..' && $file != '.gitignore') {
                $files[] = $file;
            }
        }
        closedir($dir);
        return $files;
    }
}