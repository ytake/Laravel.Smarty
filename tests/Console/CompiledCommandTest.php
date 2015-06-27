<?php

class CompiledCommandTest extends TestCase
{
    /** @var \Ytake\LaravelSmarty\Console\OptimizeCommand */
    protected $command;
    protected function setUp()
    {
        parent::setUp();
        $this->command = new \Ytake\LaravelSmarty\Console\OptimizeCommand(
            $this->factory->getSmarty(), $this->config
        );
        $this->command->setLaravel(new MockApplication());
    }
    public function testInstance()
    {
        $this->assertInstanceOf("Ytake\LaravelSmarty\Console\OptimizeCommand", $this->command);
    }
    public function testCommand()
    {
        $this->command->run(new \Symfony\Component\Console\Input\ArrayInput([]), new \Symfony\Component\Console\Output\NullOutput);
        $this->assertSame("compiles all known templates", $this->command->getDescription());
        $this->assertNotNull($this->command->getSynopsis());
    }
}