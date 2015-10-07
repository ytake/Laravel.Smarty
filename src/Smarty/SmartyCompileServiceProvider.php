<?php
/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Ytake\LaravelSmarty;

use Illuminate\Support\ServiceProvider;

/**
 * Class SmartyCompileServiceProvider
 *
 * @package Ytake\LaravelSmarty
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
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/Cache/Memcached.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/Cache/Redis.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/Cache/Storage.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/Console/CacheClearCommand.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/Console/ClearCompiledCommand.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/Console/OptimizeCommand.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/SmartyFactory.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/Engines/SmartyEngine.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/SmartyServiceProvider.php',
            base_path() . '/vendor/ytake/laravel-smarty/src/Smarty/SmartyConsoleServiceProvider.php',
            base_path() . '/vendor/smarty/smarty/libs/Smarty.class.php',
            base_path() . '/vendor/smarty/smarty/libs/Autoloader.php',
        ];
    }
}
