<?php

class System_Lib_Controller
{
	const DEFAULT_ACTION_FUNC_NAME_PERFIX = 'default';

	protected $path = null;
	protected $widgets = array();
	protected $data = array();
	protected $cacheKey = null;
	protected $interceptors = array();
	protected $internalInterceptors = array();

	/**
	 *
	 * @var System_Lib_Controller
	 */
	public $baseController = null;
	
	/**
	 * @var System_Lib_Controller
	 */
	private $_layout = null;
	protected $layoutName = null;

	/**
	 * @var array 外联Action
	 */
	protected $actions = array();


	protected function defaultAction()
	{
		throw new Exception('controller must have defaultAction() function');
	}

	/**
	 *
	 * @return System_Lib_Layout
	 */
	public function layout()
	{
		if (is_null($this->_layout))
		{
			if (is_null($this->layoutName))
			{
				return null;
			}
			$this->_layout = new $this->layoutName;
		}
		return $this->_layout;
	}

	public function getInterceptors()
	{
		if (count($this->interceptors))
		{
			return $this->interceptors;
		}
		
		$className = get_class($this);
		$interceptors = array();
		while ($className)
		{
			$selfInterceptors = call_user_func(array($className, 'selfInterceptors'));
			if (count($selfInterceptors))
			{
				$interceptors = array_merge($selfInterceptors, $interceptors);
			}
			$className = get_parent_class($className);
		}

		return $interceptors;
	}
	
	public static function selfInterceptors()
	{
		return array();
	}

	/**
	 *
	 * @param string $action
	 */
	public function run($action)
	{
		$interceptors = array();
		$skipLogic = false;
		foreach ($this->getInterceptors() as $in => $acts)
		{
			if (empty($acts) || in_array($action, $acts))
			{
				$interceptor = new $in();
				$interceptor->baseController = $this;
				$this->internalInterceptors[$in] = $interceptor;
				if ($interceptor->before($action) === false)
				{
					$skipLogic = true;
				}
				$interceptors[] = $interceptor;
			}
		}

		if (!$skipLogic)
		{
			if (!is_null($this->layout()))
			{
				$this->widgetBegin($this->layout());
			}

			if (is_callable(array($this, $action . 'Action')))
			{
				$actionMethod = $action . 'Action';
				$this->$actionMethod();
			}
			elseif (isset($this->actions[$action]))
			{
				$actionClass = $this->actions[$action][0];
				/**
				 * @var System_Lib_Action $actionObj
				 */
				$actionObj = new $actionClass();
				if (method_exists($actionObj, 'init') && is_callable(array($actionObj, 'init'))) //__call
				{
					if (isset($this->actions[$action][1]) && is_array($this->actions[$action][1]))
					{
						call_user_func_array(array($actionObj, 'init'), $this->actions[$action][1]);
					}
					else
					{
						$actionObj->init();
					}
				}
				$actionObj->run();
			}
			else
			{
				$func_name = self::DEFAULT_ACTION_FUNC_NAME_PERFIX . 'Action';
				$this->$func_name();
			}

			if (!is_null($this->layout()))
			{
				$this->widgetEnd();
			}
		}

		foreach ($interceptors as $interceptor)
		{
			$interceptor->after();
		}
	}

	/**
	 *
	 * @param string $view
	 * @param array $data
	 */
	public function render($view, $data = array())
	{
		$this->data = array_merge($data, $this->data);
		$file_path = "{$this->getViewPath()}{$view}.php";
		echo $this->renderInternal($file_path);
	}

	/**
	 * @return string
	 */
	protected function getViewPath()
	{
		return SYSTEM_PATH . "{$this->getPath()}/View/";
	}

	/**
	 *
	 * @return string
	 */
	protected function getPath()
	{
		if (!is_null($this->path))
		{
			return $this->path;
		}
		$pathInfo = explode('_', get_class($this), 2);
		$this->path = array_shift($pathInfo);
		return $this->path;
	}

	/**
	 *
	 * @param string $filePath
	 * @return string
	 */
	protected function renderInternal($filePath)
	{
		extract($this->data, EXTR_PREFIX_SAME, 'data');
		ob_start();
		ob_implicit_flush(false);
		require($filePath);
		return trim(ob_get_clean());
	}

	/**
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	protected function assignData($name, $value)
	{
		$this->data[$name] = $value;
	}
	
	/**
	 *
	 * @param string $name
	 * @return mixed
	 */
	protected function getData($name)
	{
		return isset($this->data[$name]) ? $this->data[$name] : null;
	}

	/**
	 *
	 * @param System_Lib_Widget $widget
	 */
	public function widget($widget)
	{
		$widget->baseController = $this->baseController;
		$widget->processOutput();
	}

	/**
	 *
	 * @param System_Lib_Widget $widget
	 */
	public function widgetBegin($widget)
	{
		$widget->baseController = $this->baseController;
		$this->widgets[] = $widget;
		$widget->init();
	}

	/**
	 * 
	 */
	public function widgetEnd()
	{
		$widget = array_pop($this->widgets);
		$widget->run();
	}

	/**
	 * 获取当前Controller的外联Action列表
	 * @return array
	 */
	public function getActions()
	{
		return $this->actions;
	}

}