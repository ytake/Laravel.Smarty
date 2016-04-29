<?php

class CacheClearCommandTest extends SmartyTestCase
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
        $smarty = $this->factory->getSmarty();
        $smarty->caching = true;
        ob_start();
        $smarty->display('test.tpl');
        ob_get_clean();
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $this->command->run(
            new \Symfony\Component\Console\Input\ArrayInput([]),
            $output
        );
        $this->assertSame('ytake:smarty-clear-cache', $this->command->getName());
        $this->assertSame("Flush the Smarty cache", $this->command->getDescription());
        $this->assertSame('Smarty cache cleared!', trim($output->fetch()));
    }

    public function testCommandFile()
    {
        $smarty = $this->factory->getSmarty();
        $smarty->caching = true;
        ob_start();
        $smarty->display('test.tpl');
        ob_get_clean();
        $dir = new DirectoryIterator($smarty->getCacheDir());
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
            new \Symfony\Component\Console\Input\ArrayInput(['--file' => 'test.tpl']),
            $output
        );
        $this->assertFileNotExists($pathName);
        $this->assertSame('specified file was cache cleared!', trim($output->fetch()));
    }

    public function testNoCacheFile()
    {
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $this->command->run(
            new \Symfony\Component\Console\Input\ArrayInput(['--file' => 'test.tpl']),
            $output
        );
        $this->assertSame('specified file not be found', trim($output->fetch()));
    }
}