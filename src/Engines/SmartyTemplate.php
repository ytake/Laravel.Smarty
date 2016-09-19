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

use Smarty;
use Smarty_Internal_Data;
use Smarty_Template_Source;
use Ytake\LaravelSmarty\Compilers\TemplateCompiler;

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
    public function __construct(
        $template_resource,
        Smarty $smarty,
        Smarty_Internal_Data $_parent = null,
        $_cache_id = null,
        $_compile_id = null,
        $_caching = null,
        $_cache_lifetime = null
    ) {
        $this->smarty = $smarty;
        $this->cache_id = $_cache_id === null ? $this->smarty->cache_id : $_cache_id;
        $this->compile_id = $_compile_id === null ? $this->smarty->compile_id : $_compile_id;
        $this->caching = $_caching === null ? $this->smarty->caching : $_caching;
        if ($this->caching === true) {
            $this->caching = Smarty::CACHING_LIFETIME_CURRENT;
        }
        $this->cache_lifetime = $_cache_lifetime === null ? $this->smarty->cache_lifetime : $_cache_lifetime;
        $this->parent = $_parent;
        // Template resource
        $this->template_resource = $template_resource;
        $this->source = Smarty_Template_Source::load($this);
        $this->source->compiler_class = TemplateCompiler::class;

        Smarty_Internal_Data::__construct();
        if ($smarty->security_policy && method_exists($smarty->security_policy, 'registerCallBacks')) {
            $smarty->security_policy->registerCallBacks($this);
        }
    }
}
