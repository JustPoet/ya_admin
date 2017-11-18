<?php
define('APPLICATION_PATH', dirname(__FILE__).'/..');
define('DEFAULT_APPLICATION_INI', 'application.ini');
define('LOG_DIR', APPLICATION_PATH . '/log/');

require APPLICATION_PATH.'/vendor/autoload.php';

$dir = APPLICATION_PATH."/conf/".DEFAULT_APPLICATION_INI;

$application = new Yaf_Application($dir);
$application->bootstrap()->run();
