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
        $this->command->run(
            new \Symfony\Component\Console\Input\ArrayInput([]),
            new \Symfony\Component\Console\Output\NullOutput
        );
        $this->assertSame("Remove the compiled smarty file", $this->command->getDescription());
        $this->assertNotNull($this->command->getSynopsis());
    }

    public function testCleaCompile()
    {
        $smarty = $this->factory->getSmarty();
        $smarty->force_compile = true;
        ob_start();
        $smarty->display('test.tpl');
        ob_get_clean();
        $dir = new DirectoryIterator($smarty->getCompileDir());
        $fileCount = 0;
        $pathName = null;
        foreach ($dir as $file) {
            if(!$file->isDot()) {
                if($file->getFilename() != '.gitignore') {
                    $pathName = $file->getPathname();
                    $fileCount++;
                }
            }
        }
        $this->assertSame(1, $fileCount);
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $this->command->run(
            new \Symfony\Component\Console\Input\ArrayInput([]),
            $output
        );
        $this->assertFileNotExists($pathName);
        $this->assertSame('done.', trim($output->fetch()));
    }

    public function testNotExistsFiles()
    {
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $this->command->run(
            new \Symfony\Component\Console\Input\ArrayInput(['--file' => 'testing']),
            $output
        );
        $this->assertEmpty(trim($output->fetch()));
    }
}