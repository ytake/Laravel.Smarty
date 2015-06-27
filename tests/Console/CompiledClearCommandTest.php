<?php

class CompiledClearCommandTest extends TestCase
{
    /** @var \Ytake\LaravelSmarty\Console\ClearCompiledCommand */
    protected $command;
    protected function setUp()
    {
        parent::setUp();
        $this->command = new \Ytake\LaravelSmarty\Console\ClearCompiledCommand(
            $this->factory->getSmarty()
        );
        $this->command->setLaravel(new MockApplication());
    }
    public function testInstance()
    {
        $this->assertInstanceOf("Ytake\LaravelSmarty\Console\ClearCompiledCommand", $this->command);
    }
    public function testCommand()
    {
        $this->command->run(new \Symfony\Component\Console\Input\ArrayInput([]), new \Symfony\Component\Console\Output\NullOutput);
        $this->assertSame("Remove the compiled smarty file", $this->command->getDescription());
        $this->assertNotNull($this->command->getSynopsis());
    }
}