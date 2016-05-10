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
namespace Ytake\LaravelSmarty;

use Illuminate\Support\ServiceProvider;

/**
 * Class SmartyCompileServiceProvider
 *
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class SmartyCompileServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function compiles()
    {
        return [
            base_path() . '/vendor/ytake/laravel-smarty/src/Cache/Memcached.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Cache/Redis.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Cache/Storage.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Console/CacheClearCommand.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Console/ClearCompiledCommand.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Console/OptimizeCommand.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/SmartyFactory.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Engines/SmartyEngine.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/SmartyServiceProvider.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/SmartyConsoleServiceProvider.php',
            base_path() . '/vendor/smarty/smarty/libs/Smarty.class.php',
            base_path() . '/vendor/smarty/smarty/libs/Autoloader.php',
        ];
    }
}
