<?php

/**
 * Class SmartyComposerTest
 */
class SmartyComposerTest extends SmartyTestCase
{
    public function testShouldDispatchViewComposerCaseAlwaysCompile()
    {
        $this->factory->composer('include', function (\Illuminate\View\View $view) {
            $view->with('value', 'dispatch view composer');
        });
        $this->assertContains('dispatch view composer', $this->factory->make('include_test')->render());
    }

    public function testShouldDispatchViewComposerCaseOnceCompile()
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
        $this->assertContains('dispatch view composer', $view);
        $this->assertContains('dispatch view creator', $view);
    }


    public function tearDown()
    {
        $smartyExtension = $this->factory->getSmarty()->ext;
        $class = $smartyExtension->clearCompiledTemplate;
        $class->clearCompiledTemplate($this->factory->getSmarty());
    }
}
