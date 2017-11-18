<?php

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Events\Dispatcher;

/**
 * @name Bootstrap
 * @author zhengzean
 * @desc   所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see    http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract
{
    protected $config;

    public function _initConfig(Yaf_Dispatcher $dispatcher)
    {
        ini_set('yaf.st_compatible', 1);
        $this->config = Yaf_Application::app()->getConfig();
        Yaf_Registry::set('config', $this->config);
//        define('LOG_DIR', $this->config->log->dir);
    }

    public function _initError()
    {
        if ($this->config->application->debug) {
            define('DEBUG_MODE', true);
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        } else {
            define('DEBUG_MODE', false);
            ini_set('display_errors', 0);
        }
    }

    public function _initPlugin(Yaf_Dispatcher $dispatcher)
    {
        $pjax = new PjaxPlugin();
        $dispatcher->registerPlugin($pjax);
        $auth = new AuthPlugin();
        $dispatcher->registerPlugin($auth);
    }

    public function _initDatabase()
    {
        $databaseConfig = $this->config->db->config;

        if (isset($this->config->log->error)
            && $this->config->log->error
            && !defined('MYSQL_LOG_ERROR')) {
            define('MYSQL_LOG_ERROR', true);
        }

        if ($databaseConfig) {
            $db = new DB;
            $dbConfig = $this->config->db->config->toArray();
            $dbConfig['options'] = [
                PDO::ATTR_EMULATE_PREPARES         => false,
                PDO::ATTR_ERRMODE                  => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            ];
            $db->addConnection($dbConfig);
            $db->setEventDispatcher(new Dispatcher($db->getContainer()));
            $db->setAsGlobal();
            $db->bootEloquent();
        }
    }

    public function _initView(Yaf_Dispatcher $dispatcher)
    {
        if ($dispatcher->getRequest()->isCli()) {
            $dispatcher->disableView();
        } else {
            $paths = [
                APPLICATION_PATH.'/application/views',
            ];
            $view = new TwigAdapter($paths, $this->config->twig->toArray());
            $dispatcher->setView($view);
        }
    }

    public function _initAutoload()
    {
        Yaf_Loader::getInstance(APPLICATION_PATH.'/application')->registerLocalNamespace('requests');
    }
}
