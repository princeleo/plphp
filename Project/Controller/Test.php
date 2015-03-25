<?php

class Project_Controller_Test extends System_Lib_Controller{
    public function defaultAction(){
        //$g = System_Lib_App::app()->getPost('g',System_Lib_Request::TYPE_STRING,'test'); //调用核心app中的方法
        //System_Lib_Log::debug('test','test data'); //log

        //pr(System_Lib_App::app()->getConfig('DbConfig'));
        //echo $_GET['q'];

        //$db = System_Lib_Driver::DB(System_Lib_App::app()->getConfig('DbConfig'));
        //System_Lib_App::app()->recordRunTime('mkdfkdj');
        //System_Lib_App::app()->exceptions()->show_error('333','kkk');

        //连接DB，并且返回所取得数据
        //$getItem = Project_BModel_Test::getItem();pr($getItem);die;

        //添加插件
        //$this->widget(new Project_Widget_Comment());

        $this->render('test');
    }

    public function beforeAction(){
        die('kk');
    }
}