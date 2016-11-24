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

use Illuminate\View\Factory;
use Illuminate\View\View;

/**
 * Class SmartyTemplate
 *
 * @author  yuuki.takezawa <yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class SmartyTemplate extends \Smarty_Internal_Template
{
    /**
     * Get called sub-templates and save call count
     */
    public function _subTemplateRegister()
    {
        foreach ($this->compiled->includes as $name => $count) {
            if (isset($this->smarty->_cache['subTplInfo'][$name])) {
                $this->smarty->_cache['subTplInfo'][$name] += $count;
            } else {
                $this->smarty->_cache['subTplInfo'][$name] = $count;
            }
            $this->dispatch($this->normalizeTemplateName($name));
        }
    }

    /**
     * @param string $name
     */
    protected function dispatch($name)
    {
        /** @var Factory $viewFactory */
        $viewFactory = $this->smarty->getViewFactory();
        $view = new View(
            $viewFactory,
            $viewFactory->getEngineResolver()->resolve('smarty'),
            $name,
            null,
            []
        );
        $viewFactory->callCreator($view);
        $viewFactory->callComposer($view);
        foreach ($view->getData() as $key => $data) {
            $this->assign($key, $data);
        }
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function normalizeTemplateName($name)
    {
        $name = "\"$name\"";
        if (preg_match('/^([\'"])(([A-Za-z0-9_\-]{2,})[:])?(([^$()]+)|(.+))\1$/', $name, $match)) {
            $name = !empty($match[5]) ? $match[5] : $match[6];
        }
        $fileInfo = new \SplFileInfo($name);
        $path = ($fileInfo->getPath() === '') ? null : $fileInfo->getPath() . '/';
        $viewPathInfo = $path . $fileInfo->getBasename('.' . $fileInfo->getExtension());

        return trim(str_replace('/', '.', $viewPathInfo), '\'"');
    }
}
