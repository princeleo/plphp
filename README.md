  PL-PHP

  设计初衷：采用传统的MVC+Drupal中的一些好的设计方法集成体，比如钩子的设计，等等。
  
  
  hook机制：
	主要是适用于一些特殊的情况使用，比如订单状态改过的时候，可能会触发一系列action，正常的处理方法，就是在改变订单状态的地主加一大堆action，
	但是如果使钩子机制，则可以把不同的action独立出来，新增删除都相当容易。
	hook.php   配置钩子钩到的具体类与方法
  
  
  权限机制：
  THINK-PHP中的rbac基于角色的权限管理
  
  
  多语言机制：
	1.通过多语言的模版分离
	2.通过多语言翻译文件
	3.可以在后台配置多语言
  
  
  多主题机制：
  
  
  路由机制：
	1.keymap的方式
	2.apache自身的Rewrite机制
	3.CI等框加通过基础框架的路由解析机制
  
  
  Debug和Log机制：
  
  
  错误提示机制：
  
  
  缓存机制：  
	1.页面生成静态
	2.数据缓存
	3.http缓存
  
  
   index------config.php
          |
          |
          |---app.php---base.php,urlManager.php,Factory.php,Request.php,Response.php
                      |
                      |--$app
                      |--$factory
                      |--redirect()
                      |--get()   外部调用System_Lib_App::get('S', System_Lib_Request::TYPE_STRING, '');
                      |--getPost()
                      |--getRequest()
                      |--getCookie()
                      |--setCookie()
                      |--delCookie()
                      |--createUrl()
                      |--getConfig()
                      |
                      |--run()----xxx.php（对应的action和method）
                                        |
                                        |
                                        |----System_Lib_Controller
                                        |            |---$layoutName    定义layout相关功能
                                        |            |---layout()
                                        |            |---getViewPath()
                                        |            |---getPath()
                                        |            |---widget()
                                        |
                                        |----method,具体对应的功能实现
                                                        |---Controller 控制器
                                                        |---Modle  请求数据源/API
                                                                    |---DbDriver