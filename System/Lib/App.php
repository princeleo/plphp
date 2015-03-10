<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Leo Zou
 * Date: 15-01-30
 * Time: 下午14：46
 */

define('SYSTEM_PATH',BASE_PATH.'System/');
require_once(SYSTEM_PATH.'Helpers/basic.php');

final class System_Lib_App{
	/**
	 *
	 * @var System_Lib_Factory
	 */
	public $factory = null;
	
	/**
	 *
	 * @var <array>
	 */
	protected $config;

	/**
	 *
	 * @var System_Lib_App
	 */
	public static $app = null;

	/**
	 *
	 * @param <array> $config
	 * @return System_Lib_App
	 */
	public static function createApp($config){
        if (is_null(self::$app)){
            self::$app = new System_Lib_App($config);
        }

        return self::$app;
	}

	/**
	 *
	 * @return System_Lib_App
	 */
	public static function app(){
        return self::$app;
	}

	/**
	 *
	 * @param <array> $config
	 */
	public function __construct($config){
		$this->config = $config;
		$startTime = !empty($GLOBALS['appStartTime']) ? $GLOBALS['appStartTime'] : microtime(true);
		$this->slowLog()->start($startTime);
	}

	/**
	 *
	 */
	public function run(){
		$route = $this->url()->parse();
		//新增控制器和动作的常量
		define( 'CONTROLLER', $route['controller'] );
		define( 'ACTION', $route['action'] );
		System_Lib_App::app()->request()->init();
		$controller = new $route['controller'];
		$controller->baseController = $controller;
		$controller->run($route['action']);
		$this->end();
	}

	/**
	 * 记录执行时间
	 * @param int $iStep 执行序号 < 100 为系统所用，>=100 为页面所用
	 */
	public function recordRunTime($log = ''){
		$this->slowLog()->record($log);
	}

	/**
	 *
	 * @return System_Lib_SlowLogMonitor
	 */
	public function slowLog(){
		return $this->factory()->get('System_Lib_SlowLogMonitor');
	}

	/**
	 * URL请求， 数据处理类
	 * @return System_Lib_Request
	 */
	public function request(){
		return $this->factory()->get('System_Lib_Request');
	}

	/**
	 *
	 * @param <string> $url
	 * @param <bool> $permanent
	 */
	public static function redirect($url, $permanent = false){
		System_Lib_App::app()->recordRunTime('redirect '.$url);
		System_Lib_App::app()->response()->redirect($url, $permanent);
	}

	/**
	 *
	 * @param <string> $name
	 * @param <int> $type
	 * @param <mix> $default_value
	 * @return <mix>
	 */
	public static function get($name = null, $type = System_Lib_Request::TYPE_INT, $defaultValue = null){
		return System_Lib_App::app()->request()->get($name, $type, $defaultValue);
	}

	/**
	 *
	 * @param <string> $name
	 * @param <int> $type
	 * @param <mix> $default_value
	 * @return <mix>
	 */
	public static function getPost($name = null, $type = System_Lib_Request::TYPE_INT, $defaultValue = null){
		return System_Lib_App::app()->request()->getPost($name, $type, $defaultValue);
	}

	/**
	 *
	 * @param <string> $name
	 * @param <int> $type
	 * @param <mix> $default_value
	 * @return <mix>
	 */
	public static function getRequest($name = null, $type = System_Lib_Request::TYPE_INT, $defaultValue = null){
		return System_Lib_App::app()->request()->getRequest($name, $type, $defaultValue);
	}

	/**
	 *
	 * @param <string> $name
	 * @param <int> $type
	 * @param <mix> $default_value
	 * @return <mix>
	 */
	public static function getCookie($name = null, $type = System_Lib_Request::TYPE_INT, $defaultValue = null){
		return System_Lib_App::app()->request()->getCookie($name, $type, $defaultValue);
	}

	public static function setCookie($name, $value, $expire = 0, $path = '/', $domain = null, $secure = false, $httponly = false){
		return System_Lib_App::app()->response()->setCookie($name, $value, $expire, $path, $domain, $secure, $httponly);
	}

	public static function delCookie($name, $expire = 0, $path = '/', $domain = null, $secure = false, $httponly = false){
		return System_Lib_App::app()->response()->delCookie($name, $expire, $path, $domain, $secure, $httponly);
	}

	/**
	 *
	 * @param <string> $controller
	 * @param <string> $action
	 * @param <array> $params
	 * @return <mix>
	 */
	public static function createUrl($controller, $action, $params = array()){
		return System_Lib_App::app()->url()->createUrl($controller, $action, $params);
	}

	/**
	 *
	 * @return System_Lib_Response
	 */
	public function response(){
		return $this->factory()->get('System_Lib_Response');
	}

	/**
	 *
	 * @return System_Lib_UrlManager
	 */
	public function url(){
		return $this->factory()->get('System_Lib_UrlManager');
	}

	/**
	 *
	 * @param <string> $name
	 * @return <mix>
	 */
	public function getConfig($name = null){
		if (is_null($name)){
			return $this->config;
		}
		return isset($this->config[$name]) ? $this->config[$name] : null;
	}

	/**
	 *
	 * @return System_Lib_Factory
	 */
	public function factory(){
		if (is_null($this->factory)){
			$this->factory = new System_Lib_Factory();
		}
		return $this->factory;
	}

	/**
	 *
	 */
	public function end(){
        $this->slowLog()->end();
		exit;
	}

}