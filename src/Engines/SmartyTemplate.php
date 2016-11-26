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

namespace Ytake\LaravelSmarty\Engines;

use Illuminate\View\View;
use Ytake\LaravelSmarty\SmartyFactory;

/**
 * Class SmartyTemplate
 *
 * @author  yuuki.takezawa <yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class SmartyTemplate extends \Smarty_Internal_Template
{
    /**
     * {@inheritdoc}
     */
    public function _subTemplateRegister()
    {
        foreach ($this->compiled->includes as $name => $count) {
            // @codeCoverageIgnoreStart
            if (isset($this->smarty->_cache['subTplInfo'][$name])) {
                $this->smarty->_cache['subTplInfo'][$name] += $count;
            } else {
                $this->smarty->_cache['subTplInfo'][$name] = $count;
            }
            // @codeCoverageIgnoreEnd
            $this->dispatch(new self($name, $this->smarty));
        }
    }

    /**
     * @param \Smarty_Internal_Template $template
     */
    protected function dispatch(\Smarty_Internal_Template $template)
    {
        /** @var SmartyFactory $viewFactory */
        $viewFactory = $this->smarty->getViewFactory();
        $name = $this->normalizeName($template, $viewFactory);
        $view = new View(
            $viewFactory,
            $viewFactory->getEngineResolver()->resolve('smarty'),
            $name,
            $template->source->filepath,
            []
        );
        $viewFactory->callCreator($view);
        $viewFactory->callComposer($view);
        foreach ($view->getData() as $key => $data) {
            $this->assign($key, $data);
        }
        unset($template);
    }

    /**
     * @param \Smarty_Internal_Template $template
     * @param SmartyFactory             $viewFactory
     *
     * @return mixed
     */
    protected function normalizeName(
        \Smarty_Internal_Template $template,
        SmartyFactory $viewFactory
    ) {
        $name = str_replace('.' . $viewFactory->getSmartyFileExtension(), '', $template->source->name);

        return str_replace('/', '.', $name);
    }
}
