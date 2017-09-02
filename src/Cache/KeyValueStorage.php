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
 * Copyright (c) 2014-2017 Yuuki Takezawa
 *
 */

namespace Ytake\LaravelSmarty\Cache;

/**
 * Class KeyValueStorage
 *
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
abstract class KeyValueStorage extends \Smarty_CacheResource_KeyValueStore
{
    /**
     * @param array $keys
     * @param array $map
     * @param array $lookup
     *
     * @return array
     */
    protected function eachKeys(array $keys, array $map, array $lookup)
    {
        foreach ($keys as $k) {
            $hash = sha1($k);
            $map[] = $hash;
            $lookup[$hash] = $k;
        }

        return [$map, $lookup];
    }
}
