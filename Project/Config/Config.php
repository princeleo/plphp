<?php
define('LOG_ENABLE', TRUE); //debug日志
define('DEBUG_ENABLE', FALSE);
define('CACHE_ENABLE', TRUE);

class Project_Config_Config{
	/**
	 * 返回全局配置
	 */
	public static function getConfig(){
		return array(
			'404Controller' => 'Project_Controller_NotFound',
			'routeMapping'  => array(
				'/404$'                                 => 'WTG_Controller_NotFound/default', //404错误页
				'/404/<code>$'                          => 'WTG_Controller_NotFound/default', //404错误页
                '/test' => 'Project_Controller_Test/', //测试页面
			),

            //'DbConfig' => 'dbdriver://username:password@hostname/database?char_set=utf8&dbcollat=utf8_general_ci&cache_on=true&cachedir=/path/to/cache'
            'DbConfig' => array(
                'hostname' => 'localhost',
                'username' => 'root',
                'password' => '',
                'database' => '',
                'dbdriver' => 'mysql',
                'dbprefix' => '',
                'pconnect' => FALSE,
                'db_debug' => TRUE,
                'cache_on' => FALSE,
                'cachedir' => '',
                'char_set' => 'utf8',
                'dbcollat' => 'utf8_general_ci'
            )
		);
	}


    /**
     * 返回测试环境配置
     */
    public static function getTestConfig(){
        return array(
            '404Controller' => 'Project_Controller_NotFound',
            'routeMapping'  => array(
                '/404$'                                 => 'WTG_Controller_NotFound/default', //404错误页
                '/404/<code>$'                          => 'WTG_Controller_NotFound/default', //404错误页
            ),
        );
    }

    /**
     * 返回开发环境配置
     */
    public static function getDevConfig(){
        return array(
            '404Controller' => 'Project_Controller_NotFound',
            'routeMapping'  => array(
                '/404$'                                 => 'WTG_Controller_NotFound/default', //404错误页
                '/404/<code>$'                          => 'WTG_Controller_NotFound/default', //404错误页
            ),
        );
    }
}
