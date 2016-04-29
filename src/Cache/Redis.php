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

use Predis\Client;

/**
 * Class Redis
 *
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class Redis extends KeyValueStorage
{
    /** @var Client */
    protected $redis;

    /**
     * @param array $servers
     */
    public function __construct(array $servers)
    {
        if (count($servers) === 1) {
            $this->redis = new Client($servers[0]);
        } else {
            $this->redis = new Client($servers);
        }
    }

    /**
     * Read values for a set of keys from cache
     *
     * @param  array $keys list of keys to fetch
     *
     * @return array   list of values with the given keys used as indexes
     * @return boolean true on success, false on failure
     */
    protected function read(array $keys)
    {
        $_keys = $lookup = [];
        list($_keys, $lookup) = $this->eachKeys($keys, $_keys, $lookup);
        $_res = [];
        foreach ($_keys as $key) {
            $_res[$lookup[$key]] = $this->redis->get($key);
        }
        return $_res;
    }

    /**
     * Save values for a set of keys to cache
     *
     * @param  array $keys list of values to save
     * @param  int   $expire expiration time
     *
     * @return boolean true on success, false on failure
     */
    protected function write(array $keys, $expire = 1)
    {
        foreach ($keys as $k => $v) {
            $k = sha1($k);
            $this->redis->setex($k, $expire, $v);
        }
        return true;
    }

    /**
     * Remove values from cache
     *
     * @param  array $keys list of keys to delete
     *
     * @return boolean true on success, false on failure
     */
    protected function delete(array $keys)
    {
        foreach ($keys as $k) {
            $k = sha1($k);
            $this->redis->del($k);
        }
        return true;
    }

    /**
     * Remove *all* values from cache
     *
     * @return boolean true on success, false on failure
     */
    protected function purge()
    {
        $this->redis->flushdb();
    }
}
