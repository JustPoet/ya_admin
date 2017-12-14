<?php
/**
 * Cache.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/12/13 19:18
 * 修改记录:
 *
 * $Id$
 */

class Cache
{
    /**
     * @var Redis
     */
    protected static $redis;

    protected static $cacheDB = 0;

    protected static function getRedis()
    {
        if (!static::$redis) {
            $config = (Yaf_Registry::get('config'))->cache->redis;
            static::$redis = new Redis();
            static::$redis->connect($config->host, $config->port);
            if ($config->password) {
                static::$redis->auth($config->password);
            }
            static::$redis->select(static::$cacheDB);
        }

        return static::$redis;
    }

    public static function remember($timeout = 0)
    {

    }
}