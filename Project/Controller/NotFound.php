<?php

class Project_Controller_NotFound extends System_Lib_Controller{
    public function defaultAction(){
        $this->render('404');
    }
}