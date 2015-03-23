<?php

class Project_Widget_Comment extends System_Lib_Widget
{
	protected function getViewPath()
	{
		return BASE_PATH . "{$this->getPath()}/View/Widget/";
	}
}