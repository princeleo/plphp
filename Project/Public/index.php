<?
/**
 * 统一入口文件
 *
 * |@author leo
 *
 * 2015-01-29
 */

ini_set('display_errors', 'ON');
error_reporting(E_ALL);
ini_set('mbstring.internal_encoding', "UTF-8");
session_start();

define('MEMORY_LOG_OPEN',true);//内存日志配置开关，内存使用超过10M的记日志
define('SLOW_LOG_OPEN',true); //程序运行时间超长日志开关,默认超时200ms则记日志
define('BASE_PATH',dirname(dirname(dirname(__FILE__))).'/');
define('PROJECT_PATH', dirname(dirname(__FILE__)).'/');
define('DEBUG_LOG_PATH',dirname(dirname(__FILE__)).'/Public/debug/');//debug日志目录
define('SLOW_LOG_PATH',dirname(dirname(__FILE__)).'/Public/monitor/');//内存日志目录

// fix real ip
if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && !empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
    list($_SERVER["REMOTE_ADDR"]) = explode(',', $_SERVER["HTTP_X_FORWARDED_FOR"]);
    $_SERVER["REMOTE_ADDR"] = trim($_SERVER["REMOTE_ADDR"]);
}

spl_autoload_register(array('Autoload', 'load'));

class Autoload 
{
    public static function load($class) 
    {
        require_once(BASE_PATH . str_replace('_', '/', $class . '.php'));
    }
}

//server ip不同，使用不同的配置
if (isset($_SERVER['SERVER_ADDR'])){
    switch($_SERVER['SERVER_ADDR'])
    {
        case '10.6.223.150':
            $config = Project_Config_Config::getDevConfig();
            break;
        case '10.180.15.30' :
        case '10.130.131.54' :
        case '10.6.223.136' :
            $config = Project_Config_Config::getTestConfig();
            break;
        default:
            $config = Project_Config_Config::getConfig();
    }
}else{
    $config = Project_Config_Config::getConfig();
}

//let's run
System_Lib_App::createApp($config)->run();
