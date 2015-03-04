<?php

class System_Lib_SlowLogMonitor
{
	private $msg;
	private $startTime;
	private $endTime;
	private $requestUri;

	const SLOW_LIMIT = 1;	// 超时200ms则记日志
	const MEMORY_LIMIT = 10485760; // 内存使用超过10M的记日志
	
	public function __construct()
	{
		$request = new System_Lib_Request();
		$this->requestUri = $request->getUri();
	}
	
	public function start($startTime = null)
	{
		$this->startTime = is_null($startTime) ? microtime(true) : $startTime;
		$this->record('begin '.$this->requestUri);
	}

	public function record($log)
	{
		$this->msg[] = array('time' => microtime(true), 'log' => $log);
	}

	public function end($endTime = null)
	{
		$this->endTime = is_null($endTime) ? microtime(true) : $endTime;
		$this->log();
	}

	public function runTime()
	{
		return $this->formatRunTime($this->startTime, $this->endTime);
	}

	private function log()
	{
		$cost = $this->endTime - $this->startTime;
		if (defined('MEMORY_LOG_OPEN') && MEMORY_LOG_OPEN == true) {
			//记录内存使用超过20M的程序
			$size = memory_get_peak_usage(true);
			if ($size > self::MEMORY_LIMIT) {
				$log = $this->formatTime(microtime(true)).' '.number_format($size, 0).' '.$this->requestUri."\n";
				if (defined('SLOW_LOG_PATH')) {
					$logFile = SLOW_LOG_PATH.'memory_'.date('Y-m-d').'.log';
					@file_put_contents($logFile, $log, FILE_APPEND);
				}
			}
		}
		if (!defined('SLOW_LOG_OPEN') || SLOW_LOG_OPEN == false) {
			return false;
		}
		if ($cost >= self::SLOW_LIMIT && !empty($this->msg)) {
			$log = "\n+------------------\n";
			for ($i=0; $i<count($this->msg); $i++) {
				$item = $this->msg[$i];
				$lastTime = ($i==0) ? $this->startTime : $this->msg[$i-1]['time'];
				$log .= $this->formatTime($item['time']).' '.$this->formatRunTime($lastTime, $item['time']).$item['log']."\n";
			}
			$log .= $this->formatTime($this->endTime).' '.$this->formatRunTime($this->startTime, $this->endTime).'end'."\n";
			//output file
			if (defined('SLOW_LOG_PATH')) {
				$logFile = SLOW_LOG_PATH.'slow_'.date('Y-m-d').'.log';
				@file_put_contents($logFile, $log, FILE_APPEND);
			}
			//output page
			if (!empty($_GET['debug'])) {
				echo "\n<!--".$log."-->\n";
			}
		}
		return true;
	}

	private function formatTime($time)
	{
		list($sec, $mic) = explode('.', $time);
		return '['.date('Y-m-d H:i:s', $sec).'.'.str_pad($mic, 4, '0', STR_PAD_RIGHT).']';
	}

	private function formatRunTime($start, $end)
	{
		return str_pad(number_format($end - $start, 3), 10, ' ', STR_PAD_RIGHT);
	}
}
