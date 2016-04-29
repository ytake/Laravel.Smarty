<?php

class SmartyEngineTest extends SmartyTestCase
{
    /** @var \Ytake\LaravelSmarty\Engines\SmartyEngine */
    protected $engine;

    protected function setUp()
    {
        parent::setUp();
        $this->engine = new \Ytake\LaravelSmarty\Engines\SmartyEngine(
            $this->factory->getSmarty()
        );
    }

    public function testInstance()
    {
        $this->assertInstanceOf("Ytake\LaravelSmarty\Engines\SmartyEngine", $this->engine);
    }

    public function testGet()
    {
        $this->assertSame('hello', $this->engine->get('test.tpl'));
        $this->assertSame('helloSmarty', $this->engine->get('test.tpl', ['value' => 'Smarty']));
    }

    /**
     * @expectedException \SmartyException
     */
    public function testException()
    {
        $this->engine->get('testing.tpl');
    }
}