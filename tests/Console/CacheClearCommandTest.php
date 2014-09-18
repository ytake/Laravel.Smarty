<?php

class CacheClearCommandTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Ytake\LaravelSmarty\Console\CacheClearCommand */
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
        $this->command = new \Ytake\LaravelSmarty\Console\CacheClearCommand($manager->getSmarty());
    }
    public function testInstance()
    {
        $this->assertInstanceOf("Ytake\LaravelSmarty\Console\CacheClearCommand", $this->command);
    }
    public function testCommand()
    {
        $this->command->run(new \Symfony\Component\Console\Input\ArrayInput([]), new \Symfony\Component\Console\Output\NullOutput);
        $this->assertInstanceOf("Symfony\Component\Console\Output\NullOutput", $this->command->getOutput());
        $this->assertSame("Flush the smarty cache", $this->command->getDescription());
        $this->assertSame('ytake:smarty-clear-cache [-f|--file[="..."]] [-t|--time[="..."]] [-cache|--cache_id[="..."]]', $this->command->getSynopsis());
    }
}