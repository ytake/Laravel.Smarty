<?php

class PackageInfoCommandTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Ytake\LaravelSmarty\Console\PackageInfoCommand */
    protected $command;
    public function setUp()
    {
        parent::setUp();
        $this->command = new \Ytake\LaravelSmarty\Console\PackageInfoCommand;
    }
    public function testInstance()
    {
        $this->assertInstanceOf("Ytake\LaravelSmarty\Console\PackageInfoCommand", $this->command);
    }
    public function testCommand()
    {
        $this->command->run(new \Symfony\Component\Console\Input\ArrayInput([]), new \Symfony\Component\Console\Output\NullOutput);
        $this->assertInstanceOf("Symfony\Component\Console\Output\NullOutput", $this->command->getOutput());
        $this->assertSame("information about ytake/laravel-smarty", $this->command->getDescription());
        $this->assertSame("ytake:smarty-package-info", $this->command->getSynopsis());
    }
}