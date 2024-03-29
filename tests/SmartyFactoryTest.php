<?php

namespace Tests;

class SmartyFactoryTest extends SmartyTestCase
{
    public function testInstance(): void
    {
        $this->assertInstanceOf('Ytake\\LaravelSmarty\\SmartyFactory', $this->factory);
    }

    public function testSmarty(): void
    {
        $this->assertInstanceOf('Smarty', $this->factory->getSmarty());
        $this->assertNotTrue($this->factory->getVersion());
    }

    public function testConfigure(): void
    {
        $smarty = $this->factory->getSmarty();
        foreach ($smarty->getTemplateDir() as $dir) {
            $this->assertSame(true, file_exists($dir));
        }
    }

    public function testUndefinedFunction(): void
    {
        $this->expectException(\Ytake\LaravelSmarty\Exception\MethodNotFoundException::class);
        $this->factory->hello();
        $this->factory->assing();
        $this->factory->smarty([1 => 2]);
    }

    public function testPlugins(): void
    {
        $this->factory->assign('value', 'hello');
        $this->assertSame('test', $this->factory->fetch('plugin_test.tpl'));
    }

    public function testClearFile(): void
    {
        $smartyExtension = $this->factory->getSmarty()->ext;
        $class = $smartyExtension->clearCompiledTemplate;
        $class->clearCompiledTemplate($this->factory->getSmarty());
        $this->assertSame(0, count($this->scan()));
    }

    public function scan()
    {
        $files = [];
        $dir = opendir(__DIR__.'/storage/smarty/compile');
        while ($file = readdir($dir)) {
            if ($file != '.' && $file != '..' && $file != '.gitignore') {
                $files[] = $file;
            }
        }
        closedir($dir);

        return $files;
    }
}
