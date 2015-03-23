<?php

class System_Lib_Widget extends System_Lib_Controller
{
	protected $divisionMode = false;
	protected $cacheHandler = null;
	protected $cacheExpire = 0;
	/**
	 *
	 * @var System_Lib_Cache_Base
	 */
	protected $cache = null;

	/**
	 *
	 * @param <array> $data
	 */
	public function __construct($data = array())
	{
		$this->data = $data;
	}

	/**
	 *
	 */
	protected function defaultAction()
	{
	}

	/**
	 *
	 */
	public function init()
	{
		$this->divisionMode = true;
		ob_start();
		ob_implicit_flush(false);
	}

	/**
	 *
	 */
	public function run()
	{
		$this->assignData('content', ob_get_clean());
		$this->processOutput();
	}

	/**
	 *
	 */
	public function processOutput()
	{
		if (!is_null($this->cacheHandler))
		{
			if ($this->divisionMode)
			{
				throw new Exception(get_class($this) . ' says do not enable cache in divisionMode');
			}
			$this->cache = new $this->cacheHandler();
			if (!$this->cache->tryGet($this->getCacheKey(), $this->cacheExpire))
			{
				$this->processOutputInternal();
			}
			$this->cache->end();
		}
		else
		{
			$this->processOutputInternal();
		}
	}

	protected function processOutputInternal()
	{
		$this->defaultAction();
		$this->render(array_pop(explode('_', get_class($this))));
	}

	protected function getCacheKey()
	{
		return get_class($this);
	}

	/**
	 *
	 */
	public function getContent()
	{
		$this->init();
		$this->processOutput();
		return trim(ob_get_clean());
	}

	/**
	 *
	 * @return <string>
	 */
	protected function getViewPath()
	{
		return BASE_PATH . "{$this->getPath()}/View/Widget/";
	}
}