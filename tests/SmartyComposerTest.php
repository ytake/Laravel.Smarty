<?php

/**
 * Class SmartyComposerTest.
 */
class SmartyComposerTest extends SmartyTestCase
{
    public function testShouldDispatchViewComposerCaseAlwaysCompile(): void
    {
        $this->factory->composer('include', function (\Illuminate\View\View $view) {
            $view->with('value', 'dispatch view composer');
        });
        $this->assertStringContainsString('dispatch view composer', $this->factory->make('include_test')->render());
    }

    public function testShouldDispatchViewComposerCaseOnceCompile(): void
    {
        $this->config->set('ytake-laravel-smarty.force_compile', false);
        $this->factory->setSmartyConfigure();
        $this->factory->composer('include', function (\Illuminate\View\View $view) {
            $view->with('value', 'dispatch view composer');
        });
        $this->factory->creator('include', function (\Illuminate\View\View $view) {
            $view->with('creator', 'dispatch view creator');
        });
        $view = $this->factory->make('include_test')->render();
        $this->assertStringContainsString('dispatch view composer', $view);
        $this->assertStringContainsString('dispatch view creator', $view);
    }

    public function tearDown(): void
    {
        $smartyExtension = $this->factory->getSmarty()->ext;
        $class = $smartyExtension->clearCompiledTemplate;
        $class->clearCompiledTemplate($this->factory->getSmarty());
    }
}
