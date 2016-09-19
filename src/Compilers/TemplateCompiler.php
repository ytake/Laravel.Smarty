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

/**
 * Class TemplateCompiler
 *
 * @author  yuuki.takezawa <yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class TemplateCompiler extends \Smarty_Internal_SmartyTemplateCompiler
{
    /**
     * {@inheritdoc}
     */
    public function callTagCompiler($tag, $args, $param1 = null, $param2 = null, $param3 = null)
    {
        $this->smarty->tpl_vars['userCount'] = new \Smarty_Variable(9999);
        if (!isset(self::$_tag_objects[$tag])) {
            // lazy load internal compiler plugin
            $_tag = explode('_', $tag);
            $_tag = array_map('ucfirst', $_tag);
            $class_name = $this->detectSmartyInternalCompiler('Smarty_Internal_Compile_' . implode('_', $_tag));
            if (class_exists($class_name) &&
                (!isset($this->smarty->security_policy) || $this->smarty->security_policy->isTrustedTag($tag, $this))
            ) {
                self::$_tag_objects[$tag] = $this->appendViewFactoryInstance(new $class_name);;
            } else {
                self::$_tag_objects[$tag] = false;

                return false;
            }
        }

        // compile this tag
        return self::$_tag_objects[$tag] === false ? false :
            self::$_tag_objects[$tag]->compile($args, $this, $param1, $param2, $param3);
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function detectSmartyInternalCompiler($className)
    {
        if ($className === \Smarty_Internal_Compile_Include::class) {
            $className = CompileInclude::class;
        }

        return $className;
    }

    /**
     * @param \Smarty_Internal_CompileBase|Compilable $instance
     *
     * @return \Smarty_Internal_CompileBase|Compilable
     */
    protected function appendViewFactoryInstance($instance)
    {
        if ($instance instanceof Compilable) {
            if (!is_null($this->smarty->getViewFactory())) {
                $instance->setViewFactory($this->smarty->getViewFactory());
            }
        }

        return $instance;
    }
}
