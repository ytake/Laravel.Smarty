<?php

class CompiledClearCommandTest extends TestCase
{
    /** @var \Ytake\LaravelSmarty\Console\CompiledClearCommand */
    protected $command;
    protected function setUp()
    {
        parent::setUp();
        $this->command = new \Ytake\LaravelSmarty\Console\CompiledClearCommand(
            $this->factory->getSmarty()
        );
        $this->command->setLaravel(new MockApplication());
    }
    public function testInstance()
    {
        $this->assertInstanceOf("Ytake\LaravelSmarty\Console\CompiledClearCommand", $this->command);
    }
    public function testCommand()
    {
        $this->command->run(new \Symfony\Component\Console\Input\ArrayInput([]), new \Symfony\Component\Console\Output\NullOutput);
        $this->assertInstanceOf("Symfony\Component\Console\Output\NullOutput", $this->command->getOutput());
        $this->assertSame("Remove the compiled smarty file", $this->command->getDescription());
        $this->assertSame('ytake:smarty-clear-compiled [-f|--file[="..."]] [-compile|--compile_id[="..."]]', $this->command->getSynopsis());
    }
}