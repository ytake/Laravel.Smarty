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

namespace Ytake\LaravelSmarty\Cache;

/**
 * Class KeyValueStorage
 *
 * @package Ytake\LaravelSmarty\Smarty\Cache
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 */
abstract class KeyValueStorage extends \Smarty_CacheResource_KeyValueStore
{
    /**
     * @param array $keys
     * @param       $_keys
     * @param       $lookup
     *
     * @return array
     */
    protected function eachKeys(array $keys, $_keys, $lookup)
    {
        foreach ($keys as $k) {
            $_k = sha1($k);
            $_keys[] = $_k;
            $lookup[$_k] = $k;
        }
        return array($_keys, $lookup);
    }
}
