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
use Throwable;
use Illuminate\View\Engines\EngineInterface;
use Symfony\Component\Debug\Exception\FatalThrowableError;

/**
 * Class SmartyEngine
 *
 * @author  yuuki.takezawa <yuuki.takezawa@comnect.jp.net>
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
     * {@inheritdoc}
     */
    public function get($path, array $data = [])
    {
        return $this->evaluatePath($path, $data);
    }

    /**
     * Get the evaluated contents of the view at the given path.
     *
     * @param       $path
     * @param array $data
     *
     * @return string
     * @throws \Exception
     */
    protected function evaluatePath($path, array $data = [])
    {
        extract($data, EXTR_SKIP);
        try {
            if (!$this->smarty->isCached($path)) {
                foreach ($data as $var => $val) {
                    $this->smarty->assign($var, $val);
                }
            }
            // render
            $cacheId = isset($data['smarty.cache_id']) ? $data['smarty.cache_id'] : null;
            $compileId = isset($data['smarty.compile_id']) ? $data['smarty.compile_id'] : null;

            return $this->smarty->fetch($path, $cacheId, $compileId);
            // @codeCoverageIgnoreStart
        } catch (\Exception $e) {
            $this->handleViewException($e);
        } catch (Throwable $e) {
            $this->handleViewException(new FatalThrowableError($e));
        }
    }
    // @codeCoverageIgnoreEnd

    /**
     * @codeCoverageIgnore
     * @param \Exception $e
     *
     * @throws \Exception
     */
    protected function handleViewException(\Exception $e)
    {
        throw $e;
    }
}
