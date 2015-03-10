<?php

class Project_Controller_Test extends System_Lib_Controller{
    public function defaultAction(){
        //$g = System_Lib_App::app()->getPost('g',System_Lib_Request::TYPE_STRING,'test'); //调用核心app中的方法
        //System_Lib_Log::debug('test','test data'); //log

        //pr(System_Lib_App::app()->getConfig('DbConfig'));
        $db = System_Lib_Driver::DB(System_Lib_App::app()->getConfig('DbConfig'));
        $this->render('test');
    }
}