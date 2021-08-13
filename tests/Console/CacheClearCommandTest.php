<?php

declare(strict_types=1);

namespace Tests\Console;

use DirectoryIterator;
use SmartyException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Tests\MockApplication;
use Tests\SmartyTestCase;
use Ytake\LaravelSmarty\Console\CacheClearCommand;

use function ob_get_clean;
use function ob_start;
use function trim;

final class CacheClearCommandTest extends SmartyTestCase
{
    /** @var CacheClearCommand */
    protected $command;

    protected function setUp(): void
    {
        parent::setUp();
        $this->command = new CacheClearCommand(
            $this->factory->getSmarty()
        );
        $this->command->setLaravel(new MockApplication());
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(CacheClearCommand::class, $this->command);
    }

    /**
     * @throws SmartyException
     */
    public function testCommand(): void
    {
        $smarty = $this->factory->getSmarty();
        $smarty->caching = true;
        ob_start();
        $smarty->display('test.tpl');
        ob_get_clean();
        $output = new BufferedOutput();
        $this->command->run(
            new ArrayInput([]),
            $output
        );
        $this->assertSame('ytake:smarty-clear-cache', $this->command->getName());
        $this->assertSame('Flush the Smarty cache.', $this->command->getDescription());
        $this->assertSame('Smarty cache cleared!', trim($output->fetch()));
    }

    /**
     * @throws SmartyException
     */
    public function testCommandFile(): void
    {
        $smarty = $this->factory->getSmarty();
        $smarty->caching = true;
        ob_start();
        $smarty->display('test.tpl');
        ob_get_clean();
        $dir = new DirectoryIterator($smarty->getCacheDir());
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
            new ArrayInput(['--file' => 'test.tpl']),
            $output
        );
        $this->assertFileDoesNotExist($pathName);
        $this->assertSame('Specified file was cache cleared!', trim($output->fetch()));
    }

    public function testNoCacheFile(): void
    {
        $output = new BufferedOutput();
        $this->command->run(
            new ArrayInput(['--file' => 'test.tpl']),
            $output
        );
        $this->assertSame('Specified file not found', trim($output->fetch()));
    }
}
