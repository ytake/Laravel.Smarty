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
use Illuminate\View\Factory;

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
    public function _subTemplateRender($template, $cache_id, $compile_id, $caching, $cache_lifetime, $data, $scope,
                                       $forceTplCache, $uid = null, $content_func = null)
    {
        $tpl = clone $this;
        $tpl->parent = $this;
        $smarty = &$this->smarty;
        $_templateId = $smarty->_getTemplateId($template, $cache_id, $compile_id, $caching, $tpl);
        // recursive call ?
        if (isset($tpl->templateId) ? $tpl->templateId : $tpl->_getTemplateId() != $_templateId) {
            // already in template cache?
            if (isset($smarty->_cache[ 'tplObjects' ][ $_templateId ])) {
                // copy data from cached object
                $cachedTpl = &$smarty->_cache[ 'tplObjects' ][ $_templateId ];
                $tpl->templateId = $cachedTpl->templateId;
                $tpl->template_resource = $cachedTpl->template_resource;
                $tpl->cache_id = $cachedTpl->cache_id;
                $tpl->compile_id = $cachedTpl->compile_id;
                $tpl->source = $cachedTpl->source;
                if (isset($cachedTpl->compiled)) {
                    $tpl->compiled = $cachedTpl->compiled;
                } else {
                    unset($tpl->compiled);
                }
                if ($caching != 9999 && isset($cachedTpl->cached)) {
                    $tpl->cached = $cachedTpl->cached;
                } else {
                    unset($tpl->cached);
                }
            } else {
                $tpl->templateId = $_templateId;
                $tpl->template_resource = $template;
                $tpl->cache_id = $cache_id;
                $tpl->compile_id = $compile_id;
                if (isset($uid)) {
                    // for inline templates we can get all resource information from file dependency
                    list($filepath, $timestamp, $type) = $tpl->compiled->file_dependency[ $uid ];
                    $tpl->source = new \Smarty_Template_Source($smarty, $filepath, $type, $filepath);
                    $tpl->source->filepath = $filepath;
                    $tpl->source->timestamp = $timestamp;
                    $tpl->source->exists = true;
                    $tpl->source->uid = $uid;

                    $pathinfo = pathinfo($tpl->source->filepath);
                    $this->dispatch($pathinfo['filename'], $tpl->source->filepath);
                } else {
                    $tpl->source = \Smarty_Template_Source::load($tpl);

                    $pathinfo = pathinfo($tpl->source->filepath);
                    $this->dispatch($pathinfo['filename'], $tpl->source->filepath);
                    unset($tpl->compiled);
                }
                if ($caching != 9999) {
                    unset($tpl->cached);
                }
            }
        } else {
            // on recursive calls force caching
            $forceTplCache = true;
        }
        $tpl->caching = $caching;
        $tpl->cache_lifetime = $cache_lifetime;
        // set template scope
        $tpl->scope = $scope;
        if (!isset($smarty->_cache[ 'tplObjects' ][ $tpl->templateId ]) && !$tpl->source->handler->recompiled) {
            // check if template object should be cached
            if ($forceTplCache || (isset($smarty->_cache[ 'subTplInfo' ][ $tpl->template_resource ]) &&
                                   $smarty->_cache[ 'subTplInfo' ][ $tpl->template_resource ] > 1) ||
                ($tpl->_isParentTemplate() && isset($smarty->_cache[ 'tplObjects' ][ $tpl->parent->templateId ]))
            ) {
                $smarty->_cache[ 'tplObjects' ][ $tpl->templateId ] = $tpl;
            }
        }

        if (!empty($data)) {
            // set up variable values
            foreach ($data as $_key => $_val) {
                $tpl->tpl_vars[ $_key ] = new \Smarty_Variable($_val, $this->isRenderingCache);
            }
        }
        if ($tpl->caching == 9999) {
            if (!isset($tpl->compiled)) {
                $this->loadCompiled(true);
            }
            if ($tpl->compiled->has_nocache_code) {
                $this->cached->hashes[ $tpl->compiled->nocache_hash ] = true;
            }
        }
        $tpl->_cache = array();
        if (isset($uid)) {
            if ($smarty->debugging) {
                if (!isset($smarty->_debug)) {
                    $smarty->_debug = new \Smarty_Internal_Debug();
                }
                $smarty->_debug->start_template($tpl);
                $smarty->_debug->start_render($tpl);
            }
            $tpl->compiled->getRenderedTemplateCode($tpl, $content_func);
            if ($smarty->debugging) {
                $smarty->_debug->end_template($tpl);
                $smarty->_debug->end_render($tpl);
            }
        } else {
            if (isset($tpl->compiled)) {
                $tpl->compiled->render($tpl);
            } else {
                $tpl->render();
            }
        }
    }

    /**
     * @param string $name
     */
    protected function dispatch($name, $path)
    {
        /** @var Factory $viewFactory */
        $viewFactory = $this->smarty->getViewFactory();
        $view = new View(
            $viewFactory,
            $viewFactory->getEngineResolver()->resolve('smarty'),
            $name,
            $path,
            []
        );

        $viewFactory->callCreator($view);
        $viewFactory->callComposer($view);
        foreach ($view->getData() as $key => $data) {
            $this->assign($key, $data);
        }
    }
}