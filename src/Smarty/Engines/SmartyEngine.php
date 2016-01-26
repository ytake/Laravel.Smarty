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

namespace Ytake\LaravelSmarty\Engines;

use Smarty;
use Illuminate\View\Engines\EngineInterface;

/**
 * Class SmartyEngine
 *
 * @package Ytake\LaravelSmarty\Engines
 * @author yuuki.takezawa <yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class SmartyEngine implements EngineInterface
{
    /** @var Smarty $smarty */
    protected $smarty;

    /**
     * @param Smarty $smarty
     */
    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
    }

    /**
     * Get the evaluated contents of the view.
     *
     * @param  string $path
     * @param  array  $data
     *
     * @return string
     */
    public function get($path, array $data = [])
    {
        return $this->evaluatePath($path, $data);
    }

    /**
     * Get the evaluated contents of the view at the given path.
     *
     * @param string $path
     * @param array  $data
     *
     * @return string
     */
    protected function evaluatePath($path, array $data = [])
    {
        ob_start();
        try {
            if (!$this->smarty->isCached($path)) {
                foreach ($data as $var => $val) {
                    $this->smarty->assign($var, $val);
                }
            }
            // render
            $cacheId = isset($data['smarty.cache_id']) ? $data['smarty.cache_id'] : null;
            $compileId = isset($data['smarty.compile_id']) ? $data['smarty.compile_id'] : null;
            echo $this->smarty->fetch($path, $cacheId, $compileId);
        } catch (\Exception $e) {
            $this->handleViewException($e);
        }
        return ob_get_clean();
    }

    /**
     * @param \Exception $e
     *
     * @throws \Exception
     */
    protected function handleViewException(\Exception $e)
    {
        ob_get_clean();
        throw $e;
    }
}
