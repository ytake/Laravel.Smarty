<?php

class CompiledCommandTest extends TestCase
{
    /** @var \Ytake\LaravelSmarty\Console\CompiledCommand */
    protected $command;
    protected function setUp()
    {
        parent::setUp();
        $this->command = new \Ytake\LaravelSmarty\Console\CompiledCommand(
            $this->factory->getSmarty(), $this->config
        );
        $this->command->setLaravel(new MockApplication());
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