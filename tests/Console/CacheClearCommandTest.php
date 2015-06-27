<?php

class CacheClearCommandTest extends TestCase
{
    /** @var \Ytake\LaravelSmarty\Console\CacheClearCommand */
    protected $command;
    protected function setUp()
    {
        parent::setUp();
        $this->command = new \Ytake\LaravelSmarty\Console\CacheClearCommand(
            $this->factory->getSmarty()
        );
        $this->command->setLaravel(new MockApplication());
    }
    public function testInstance()
    {
        $this->assertInstanceOf("Ytake\LaravelSmarty\Console\CacheClearCommand", $this->command);
    }
    public function testCommand()
    {
        $this->command->run(new \Symfony\Component\Console\Input\ArrayInput([]), new \Symfony\Component\Console\Output\NullOutput);
        $this->assertSame('ytake:smarty-clear-cache', $this->command->getName());
        $this->assertSame("Flush the smarty cache", $this->command->getDescription());
    }

    public function testCommandFile()
    {
        $this->command->run(new \Symfony\Component\Console\Input\ArrayInput(['--file' => 'test']), new \Symfony\Component\Console\Output\NullOutput);
    }
}