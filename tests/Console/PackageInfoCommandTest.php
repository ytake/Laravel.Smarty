<?php

declare(strict_types=1);

namespace Tests\Console;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Tests\SmartyTestCase;
use Tests\MockApplication;
use Ytake\LaravelSmarty\Console\PackageInfoCommand;

final class PackageInfoCommandTest extends SmartyTestCase
{
    /** @var PackageInfoCommand */
    protected $command;

    protected function setUp(): void
    {
        parent::setUp();
        $this->command = new PackageInfoCommand();
        $this->command->setLaravel(new MockApplication());
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(PackageInfoCommand::class, $this->command);
    }

    public function testCommand(): void
    {
        $this->command->run(
            new ArrayInput([]),
            new NullOutput()
        );
        $this->assertSame("Information about ytake/laravel-smarty", $this->command->getDescription());
        $this->assertSame("ytake:smarty-package-info", $this->command->getSynopsis());
    }
}
