<?php
/**
 * Singleton.php
 *
 * 作者: zhengzean (zhengzean01@gmail.com)
 * 创建日期: 2017/10/30 14:36
 * 修改记录:
 *
 * $Id$
 */

trait Singleton
{
    /**
     * @var static
     */
    private static $_instance;

    /**
     * 构造函数声明为private,防止直接创建对象
     *
     * Singleton constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {

            self::$_instance = new static();
        }

        return self::$_instance;
    }

    /**
     * 防止对象实例被克隆
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * 防止被反序列化
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}