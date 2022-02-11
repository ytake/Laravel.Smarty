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
 * Copyright (c) 2014-2022 Yuuki Takezawa
 *
 */

declare(strict_types=1);

namespace Ytake\LaravelSmarty\Cache;

use Ytake\LaravelSmarty\Smarty;
use Illuminate\Contracts\Config\Repository as ConfigContract;

/**
 * Class Storage
 *
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class Storage
{
    /**
     * @param Smarty $smarty
     * @param ConfigContract $repository
     */
    public function __construct(
        protected Smarty $smarty,
        protected ConfigContract $repository
    ) {
    }

    /**
     */
    public function cacheStorageManaged(): void
    {
        $driver = $this->repository->get('ytake-laravel-smarty.cache_driver', 'file');
        if ($driver !== 'file') {
            $storage = $driver . "Storage";
            $this->smarty->registerCacheResource($driver, $this->$storage());
        }
        $this->smarty->caching_type = $driver;
    }

    /**
     * @return Redis
     */
    protected function redisStorage(): Redis
    {
        return new Redis($this->repository->get('ytake-laravel-smarty.redis'));
    }

    /**
     * @return Memcached
     */
    protected function memcachedStorage(): Memcached
    {
        return new Memcached(
            new \Memcached(),
            $this->repository->get('ytake-laravel-smarty.memcached')
        );
    }
}
