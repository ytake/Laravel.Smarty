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

namespace Ytake\LaravelSmarty\Cache;

use Memcached as MemcachedExtension;

/**
 * Class Memcached
 *
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class Memcached extends KeyValueStorage
{
    /** @var MemcachedExtension */
    protected $memcached;

    /**
     * @param MemcachedExtension $memcached
     */
    public function __construct(MemcachedExtension $memcached, array $servers)
    {
        $this->memcached = $this->connection($memcached, $servers);
    }

    /**
     * @param MemcachedExtension $memcached
     * @param array              $servers
     *
     * @return MemcachedExtension
     */
    protected function connection(MemcachedExtension $memcached, array $servers)
    {
        foreach ($servers as $server) {
            $memcached->addServer($server['host'], $server['port'], $server['weight']);
        }
        return $memcached;
    }


    /**
     * Read values for a set of keys from cache
     *
     * @param  array $keys list of keys to fetch
     *
     * @return array   list of values with the given keys used as indexes
     */
    protected function read(array $keys)
    {
        $map = $lookup = [];
        list($map, $lookup) = $this->eachKeys($keys, $map, $lookup);
        $result = [];
        $memcachedResult = $this->memcached->getMulti($map);
        foreach ($memcachedResult as $k => $v) {
            $result[$lookup[$k]] = $v;
        }
        return $result;
    }

    /**
     * Save values for a set of keys to cache
     *
     * @param  array $keys list of values to save
     * @param  int   $expire expiration time
     *
     * @return bool true on success, false on failure
     */
    protected function write(array $keys, $expire = null)
    {
        foreach ($keys as $k => $v) {
            $k = sha1($k);
            $this->memcached->set($k, $v, $expire);
        }
        return true;
    }

    /**
     * Remove values from cache
     *
     * @param  array $keys list of keys to delete
     *
     * @return bool true on success, false on failure
     */
    protected function delete(array $keys)
    {
        foreach ($keys as $k) {
            $k = sha1($k);
            $this->memcached->delete($k);
        }
        return true;
    }

    /**
     * Remove *all* values from cache
     *
     * @return bool true on success, false on failure
     */
    protected function purge()
    {
        return $this->memcached->flush();
    }
}
