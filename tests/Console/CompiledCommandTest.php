<?php

declare(strict_types=1);

namespace Tests\Console;

use DirectoryIterator;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;
use Tests\MockApplication;
use Tests\SmartyTestCase;
use Ytake\LaravelSmarty\Console\ClearCompiledCommand;
use Ytake\LaravelSmarty\Console\OptimizeCommand;

final class CompiledCommandTest extends SmartyTestCase
{
    /** @var OptimizeCommand */
    protected $command;

    protected function setUp(): void
    {
        parent::setUp();
        $this->command = new OptimizeCommand(
            $this->factory->getSmarty(), $this->config
        );
        $this->command->setLaravel(new MockApplication());
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(OptimizeCommand::class, $this->command);
    }

    public function testCommand(): void
    {
        $this->command->run(
            new ArrayInput([]),
            new NullOutput()
        );
        $this->assertSame("Compile all known templates.", $this->command->getDescription());
        $this->assertNotNull($this->command->getSynopsis());
    }

    public function testCleaCompile(): void
    {
        $smarty = $this->factory->getSmarty();
        $smarty->force_compile = false;

        $dir = new DirectoryIterator($smarty->getCompileDir());
        $fileCount = 0;
        foreach ($dir as $file) {
            if (!$file->isDot()) {
                if ($file->getFilename() != '.gitignore') {
                    $fileCount++;
                }
            }
        }
        $output = new BufferedOutput();
        $this->command->run(
            new ArrayInput(['--force' => null]),
            $output
        );
        $this->assertNotSame(0, $fileCount);
        $this->removeCompileFiles();
    }

    protected function removeCompileFiles(): void
    {
        $command = new ClearCompiledCommand(
            $this->factory
        );
        $command->setLaravel(new MockApplication());
        $output = new BufferedOutput();
        $command->run(
            new ArrayInput([]),
            $output
        );
    }
}