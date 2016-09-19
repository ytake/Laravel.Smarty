<?php

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 *
 * Copyright (c) 2014-2016 Yuuki Takezawa
 *
 */
namespace Ytake\LaravelSmarty\Compilers;

use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;

/**
 * Class CompileInclude
 *
 * @author  yuuki.takezawa <yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class CompileInclude extends \Smarty_Internal_Compile_Include implements Compilable
{
    /** @var Factory|\Ytake\LaravelSmarty\SmartyFactory */
    protected $viewFactory;

    /** @var array */
    protected $laravelViewData;

    /**
     * {@inheritdoc}
     */
    public function compile($args, \Smarty_Internal_SmartyTemplateCompiler $compiler, $parameter)
    {
        foreach ($args as $arg) {
            if (isset($arg['file'])) {
                $viewName = $this->normalizeTemplateName($arg['file']);
                $this->dispatch($viewName);
            }
        }

        return parent::compile($args, $compiler, $parameter);
    }

    /**
     * @param Factory $factory
     */
    public function setViewFactory(Factory $factory)
    {
        $this->viewFactory = $factory;
    }

    /**
     * @param array $data
     */
    public function setLaravelViewData(array $data)
    {
        $this->laravelViewData = $data;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function normalizeTemplateName($name)
    {
        $fileInfo = new \SplFileInfo($name);
        $path = ($fileInfo->getPath() === '') ? null : $fileInfo->getPath() . '/';
        $viewPathInfo = $path . $fileInfo->getBasename('.' . $fileInfo->getExtension());

        return trim(str_replace('/', '.', $viewPathInfo), '"');
    }

    /**
     * @param $name
     */
    protected function dispatch($name)
    {
        $view = new View(
            $this->viewFactory,
            $this->viewFactory->getEngineResolver()->resolve('smarty'),
            $name,
            null,
            []
        );
        $this->viewFactory->callCreator($view);
        $this->viewFactory->callComposer($view);
    }
}
