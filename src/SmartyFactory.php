<?php
declare(strict_types=1);

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
 * Copyright (c) 2014-2019 Yuuki Takezawa
 *
 */

namespace Ytake\LaravelSmarty;

use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\ViewFinderInterface;
use Illuminate\Support\Arr;
use ReflectionClass;
use Ytake\LaravelSmarty\Cache\Storage;
use Ytake\LaravelSmarty\Engines\SmartyTemplate;
use Ytake\LaravelSmarty\Exception\MethodNotFoundException;

/**
 * Class SmartyManager
 *
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class SmartyFactory extends Factory
{
    /**
     * @var string  version
     */
    const VERSION = '2.6.0';

    /** @var Smarty $smarty */
    protected $smarty;

    /** @var ConfigContract $config */
    protected $config;

    /** @var array valid config keys */
    protected $configKeys = [
        'auto_literal',
        'error_unassigned',
        'use_include_path',
        'joined_template_dir',
        'joined_config_dir',
        'default_template_handler_func',
        'default_config_handler_func',
        'default_plugin_handler_func',
        'force_compile',
        'compile_check',
        'use_sub_dirs',
        'allow_ambiguous_resources',
        'merge_compiled_includes',
        'inheritance_merge_compiled_includes',
        'force_cache',
        'left_delimiter',
        'right_delimiter',
        'security_class',
        'php_handling',
        'allow_php_templates',
        'direct_access_security',
        'debugging',
        'debugging_ctrl',
        'smarty_debug_id',
        'debug_tpl',
//      'error_reporting', added below with default value
        'get_used_tags',
        'config_overwrite',
        'config_booleanize',
        'config_read_hidden',
        'compile_locking',
        'cache_locking',
        'locking_timeout',
        'default_resource_type',
        'caching_type',
        'properties',
        'default_config_type',
        'source_objects',
        'template_objects',
        'resource_caching',
        'template_resource_caching',
        'cache_modified_check',
        'registered_plugins',
        'plugin_search_order',
        'registered_objects',
        'registered_classes',
        'registered_filters',
        'registered_resources',
        '_resource_handlers',
        'registered_cache_resources',
        '_cacheresource_handlers',
        'autoload_filters',
        'default_modifiers',
        'escape_html',
        'start_time',
        '_file_perms',
        '_dir_perms',
        '_tag_stack',
        '_current_file',
        '_parserdebug',
        '_is_file_cache',
        'cache_id',
        'compile_id',
        'caching',
        'cache_lifetime',
        'template_class',
        'tpl_vars',
        'parent',
        'config_vars',
    ];

    /** @var array valid security policy config keys */
    protected $securityPolicyConfigKeys = [
        'php_handling',
        'secure_dir',
        'trusted_dir',
        'trusted_uri',
        'trusted_constants',
        'static_classes',
        'trusted_static_methods',
        'trusted_static_properties',
        'php_functions',
        'php_modifiers',
        'allowed_tags',
        'disabled_tags',
        'allowed_modifiers',
        'disabled_modifiers',
        'disabled_special_smarty_vars',
        'streams',
        'allow_constants',
        'allow_super_globals',
        'max_template_nesting',
    ];

    /** @var string  smarty template file extension */
    protected $smartyFileExtension;

    /**
     * @param EngineResolver      $engines
     * @param ViewFinderInterface $finder
     * @param DispatcherContract  $events
     * @param Smarty              $smarty
     * @param ConfigContract      $config
     */
    public function __construct(
        EngineResolver $engines,
        ViewFinderInterface $finder,
        DispatcherContract $events,
        Smarty $smarty,
        ConfigContract $config
    ) {
        parent::__construct($engines, $finder, $events);
        $this->smarty = $smarty;
        $this->config = $config;
    }

    /**
     * @return Smarty
     */
    public function getSmarty(): Smarty
    {
        return $this->smarty;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return self::VERSION;
    }

    public function resolveSmartyCache()
    {
        $cacheStorage = new Storage($this->getSmarty(), $this->config);
        $cacheStorage->cacheStorageManaged();
    }

    /**
     * smarty configure
     *
     * @throws \SmartyException
     */
    public function setSmartyConfigure()
    {
        $config = $this->config->get('ytake-laravel-smarty');
        $smarty = $this->smarty;

        $smarty->setTemplateDir(Arr::get($config, 'template_path'));
        $smarty->setCompileDir(Arr::get($config, 'compile_path'));
        $smarty->setCacheDir(Arr::get($config, 'cache_path'));
        $smarty->setConfigDir(Arr::get($config, 'config_paths'));

        foreach (Arr::get($config, 'plugins_paths', []) as $plugins) {
            $smarty->addPluginsDir($plugins);
        }

        $smarty->error_reporting = Arr::get($config, 'error_reporting', E_ALL & ~E_NOTICE);
        // SmartyTemplate class for laravel
        $smarty->template_class = SmartyTemplate::class;
        foreach ($config as $key => $value) {
            if (in_array($key, $this->configKeys)) {
                $smarty->{$key} = $value;
            }
        }

        if (Arr::get($config, 'enable_security')) {
            $smarty->enableSecurity();
            $securityPolicy = $smarty->security_policy;
            $securityConfig = Arr::get($config, 'security_policy', []);
            foreach ($securityConfig as $key => $value) {
                if (in_array($key, $this->securityPolicyConfigKeys)) {
                    $securityPolicy->{$key} = $value;
                }
            }
        }
        $this->smartyFileExtension = $config['extension'];
    }

    /**
     * @return string
     */
    public function getSmartyFileExtension(): string
    {
        return $this->smartyFileExtension;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     */
    public function __call($name, $arguments)
    {
        $reflectionClass = new ReflectionClass($this->smarty);
        if (!$reflectionClass->hasMethod($name)) {
            throw new MethodNotFoundException("{$name} : Method Not Found");
        }

        return call_user_func_array([$this->smarty, $name], $arguments);
    }
}
