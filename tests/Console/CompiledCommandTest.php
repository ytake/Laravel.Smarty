<?php

class CompiledCommandTest extends SmartyTestCase
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
        $this->command->run(
            new \Symfony\Component\Console\Input\ArrayInput([]),
            new \Symfony\Component\Console\Output\NullOutput
        );
        $this->assertSame("compiles all known templates", $this->command->getDescription());
        $this->assertNotNull($this->command->getSynopsis());
    }

    public function testCleaCompile()
    {
        $smarty = $this->factory->getSmarty();
        $smarty->force_compile = false;

        $dir = new DirectoryIterator($smarty->getCompileDir());
        $fileCount = 0;
        $pathName = null;
        foreach ($dir as $file) {
            if (!$file->isDot()) {
                if ($file->getFilename() != '.gitignore') {
                    $fileCount++;
                }
            }
        }
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $this->command->run(
            new \Symfony\Component\Console\Input\ArrayInput(['--force' => null]),
            $output
        );
        $this->assertNotSame(0, $fileCount);
        $this->removeCompileFiles();
    }

    protected function removeCompileFiles()
    {
        $command = new \Ytake\LaravelSmarty\Console\ClearCompiledCommand(
            $this->factory
        );
        $command->setLaravel(new MockApplication());
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $command->run(
            new \Symfony\Component\Console\Input\ArrayInput([]),
            $output
        );
    }
}