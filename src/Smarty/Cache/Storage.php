<?php
namespace Ytake\LaravelSmarty\Cache;

use Smarty;
use Illuminate\Contracts\Config\Repository as ConfigContract;

/**
 * Class Storage
 * @package Ytake\LaravelSmarty\Cache
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class Storage
{

    /** @var Smarty  */
    protected $smarty;

    /** @var ConfigContract  */
    protected $repository;

    /**
     * @param Smarty $smarty
     * @param ConfigContract $repository
     */
    public function __construct(Smarty $smarty, ConfigContract $repository)
    {
        $this->smarty = $smarty;
        $this->repository = $repository;
        $this->cacheStorageManaged();
    }

    /**
     * @return void
     */
    protected function cacheStorageManaged()
    {
        $driver = $this->repository->get('ytake-laravel-smarty.cache_driver', 'file');
        if($driver !== 'file') {
            $storage = $driver . "Storage";
            $this->smarty->registerCacheResource($driver, $this->$storage());
        }
        $this->smarty->caching_type = $driver;
    }

    /**
     * @return Redis
     */
    protected function redisStorage()
    {
        return new Redis($this->repository->get('ytake-laravel-smarty.redis'));
    }

    /**
     * @return Memcached
     */
    protected function memcachedStorage()
    {
        return new Memcached(
            new \Memcached(),
            $this->repository->get('ytake-laravel-smarty.memcached')
        );
    }
}
