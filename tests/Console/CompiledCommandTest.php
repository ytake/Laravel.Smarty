<?php

class CompiledCommandTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Ytake\LaravelSmarty\Console\CompiledCommand */
    protected $command;
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
        $this->command = new \Ytake\LaravelSmarty\Console\CompiledCommand($manager->getSmarty(), $repo);
    }
    public function testInstance()
    {
        $this->assertInstanceOf("Ytake\LaravelSmarty\Console\CompiledCommand", $this->command);
    }
    public function testCommand()
    {
        $this->command->run(new \Symfony\Component\Console\Input\ArrayInput([]), new \Symfony\Component\Console\Output\NullOutput);
        $this->assertInstanceOf("Symfony\Component\Console\Output\NullOutput", $this->command->getOutput());
        $this->assertSame("compiles all known templates", $this->command->getDescription());
        $this->assertSame('ytake:smarty-optimize [-e|--extension[="..."]] [--force]', $this->command->getSynopsis());
    }
}