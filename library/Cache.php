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
     * @var array
     */
    protected static $instances = [];

    /**
     * @var Redis
     */
    protected $redis;

    private function __construct($db = 0)
    {
        $config = (Yaf_Registry::get('config'))->cache->redis;
        $this->redis = new Redis();
        $this->redis->connect($config->host, $config->port);
        if ($config->password) {
            $this->redis->auth($config->password);
        }
        $this->redis->select($db);
    }

    public static function getCache($db = 0)
    {
        if (!isset(static::$instances[$db])) {
            static::$instances[$db] = new static($db);
        }

        return static::$instances[$db];
    }

    /**
     * 缓存
     *
     * @param string $model     model类名
     * @param mixed  $condition 条件（非数组时为主键）
     * @param int    $timeout   超时
     *
     * @return mixed
     */
    public function remember($model, $condition = [], $timeout = 600)
    {
        /**
         * @var \Illuminate\Database\Eloquent\Model $modelObj
         */
        $modelObj = new $model;
        $pk = $modelObj->getKeyName();

        /**
         * @var \Illuminate\Database\Eloquent\Builder $query
         */
        $query = $model::select($modelObj->getFillable());

        if (!empty($condition)) {
            if (is_array($condition)) {
                $query = $query->where($condition);
            } else {
                $query = $query->where($pk, $condition);
            }
        }

        $data = $query->get();

        $this->save($model, $data->groupBy($pk), $condition, $timeout);

        return $data;
    }

    /**
     * 查询已缓存，没有缓存则自动缓存并返回
     *
     * @param string $model     model类名
     * @param mixed  $condition 条件（非数组时为主键）
     *
     * @return array|mixed
     */
    public function get($model, $condition)
    {
        if (is_array($condition)) {
            $key = $model.':index:'.md5(serialize($condition));
            if ($indexes = $this->redis->get($key)) {
                $indexes = unserialize($indexes);
                $data = $this->redis->getMultiple($indexes);

                return array_map(function ($item) {
                    return unserialize($item);
                }, $data);
            }
        } else {
            return unserialize($this->redis->get($model.':data:'.$condition));
        }

        return $this->remember($model, $condition);
    }

    /**
     * 删除
     *
     * @param string $model model类名
     * @param int    $key   主键
     */
    public function del($model, $key)
    {
        $this->redis->del($model.':data:'.$key);
    }

    /**
     * 保存
     *
     * @param $model
     * @param $data
     * @param $condition
     * @param $timeout
     */
    protected function save($model, $data, $condition, $timeout)
    {
        $dataKey = $model.':data:';
        $indexKey = $model.':index:'.md5(serialize($condition));
        $indexes = serialize(array_keys($data));

        $this->redis->multi();
        if ($timeout > 0) {
            foreach ($data as $key => $val) {
                $this->redis->setex($dataKey.$key, $timeout,
                    serialize($val[0]));
            }
            $this->redis->setex($indexKey, $timeout, $indexes);
        } else {
            foreach ($data as $key => $val) {
                $this->redis->set($dataKey.$key, serialize($val[0]));
            }
            $this->redis->set($indexKey, $indexes);
        }
        $this->redis->exec();
    }
}