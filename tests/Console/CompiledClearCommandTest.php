<?php

declare(strict_types=1);

namespace Tests\Console;

use DirectoryIterator;
use SmartyException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;
use Tests\MockApplication;
use Tests\SmartyTestCase;
use Ytake\LaravelSmarty\Console\ClearCompiledCommand;

use function ob_get_clean;
use function ob_start;
use function trim;

final class CompiledClearCommandTest extends SmartyTestCase
{
    /** @var ClearCompiledCommand */
    protected $command;

    protected function setUp(): void
    {
        parent::setUp();
        $this->command = new ClearCompiledCommand(
            $this->factory
        );
        $this->command->setLaravel(new MockApplication());
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(ClearCompiledCommand::class, $this->command);
    }

    public function testCommand(): void
    {
        $this->command->run(
            new ArrayInput([]),
            new NullOutput()
        );
        $this->assertSame('Remove the compiled Smarty files.', $this->command->getDescription());
        $this->assertNotNull($this->command->getSynopsis());
    }

    /**
     * @throws SmartyException
     */
    public function testClearCompile(): void
    {
        $smarty = $this->factory->getSmarty();
        $smarty->force_compile = true;
        ob_start();
        $smarty->display('test.tpl');
        ob_get_clean();
        $dir = new DirectoryIterator($smarty->getCompileDir());
        $fileCount = 0;
        $pathName = '';
        foreach ($dir as $file) {
            if (!$file->isDot()) {
                if ($file->getFilename() != '.gitignore') {
                    $pathName = $file->getPathname();
                    $fileCount++;
                }
            }
        }
        $this->assertSame(1, $fileCount);
        $output = new BufferedOutput();
        $this->command->run(
            new ArrayInput([]),
            $output
        );
        $this->assertFileDoesNotExist($pathName);
        $this->assertSame('Removed 1 compiled Smarty file.', trim($output->fetch()));
    }

    /**
     * @throws SmartyException
     */
    public function testClearCompileMultipleFiles(): void
    {
        $smarty = $this->factory->getSmarty();
        $smarty->force_compile = true;
        ob_start();
        $smarty->display('test.tpl');
        $smarty->display('test2.tpl');
        ob_get_clean();
        $output = new BufferedOutput();
        $this->command->run(
            new ArrayInput([]),
            $output
        );
        $this->assertSame('Removed 2 compiled Smarty files.', trim($output->fetch()));
    }

    public function testNotExistsFiles(): void
    {
        $output = new BufferedOutput();
        $this->command->run(
            new ArrayInput(['--file' => 'testing']),
            $output
        );
        $this->assertEmpty(trim($output->fetch()));
    }
}
