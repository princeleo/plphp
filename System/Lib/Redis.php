<?php
/**
 * redis封装，下列所有方法中，当value为int且需要调用Increase方法是，$isSerialize传false，其余true
 * @author anakinli
 * 2012-09-16
 */

class System_Lib_Redis
{
	static $redisHash;

	public static function Get($key, $server, $isSerialize = true)
	{
		if (!defined(CACHE_ENABLE) && CACHE_ENABLE === false) {
			return false;
		}

		$name = $server['ip'] . '-' . $server['port'];

		if (!isset(self::$redisHash[$name]) || self::$redisHash[$name] === null) {
			self::$redisHash[$name] = new Redis();
		}

		$redis = self::$redisHash[$name];

// 		$retValue = $redis->pconnect($server['ip'], $server['port'], $server['timeout']);
		$retValue = $redis->connect($server['ip'], $server['port'], $server['timeout']);
		if ($retValue === false) {
			return false;
		}

		$retValue = $redis->get($key);
		if ($retValue === false) {
			return false;
		}

		return $isSerialize ? unserialize($retValue) : $retValue;
	}

	public static function MGet($keys, $server, $isSerialize = true)
	{
		$name = $server['ip'] . '-' . $server['port'];

		if (!isset(self::$redisHash[$name]) || self::$redisHash[$name] === null) {
			self::$redisHash[$name] = new Redis();
		}

		$redis = self::$redisHash[$name];

// 		$retValue = $redis->pconnect($server['ip'], $server['port'], $server['timeout']);
		$retValue = $redis->connect($server['ip'], $server['port'], $server['timeout']);
		if ($retValue === false) {
			return false;
		}

		$retValue = $redis->mGet($keys);
		if ($retValue === false) {
			return false;
		}

		$ret = array();
		foreach ($retValue as $v) {
			$ret[] = $isSerialize ? unserialize($v) : $v;
		}

		return $ret;
	}

	public static function Set($key, $value, $server, $isSerialize = true, $expired = false)
	{
		$name = $server['ip'] . '-' . $server['port'];

		if (!isset(self::$redisHash[$name]) || self::$redisHash[$name] === null) {
			self::$redisHash[$name] = new Redis();
		}

		$redis = self::$redisHash[$name];

// 		$retValue = $redis->pconnect($server['ip'], $server['port'], $server['timeout']);
		$retValue = $redis->connect($server['ip'], $server['port'], $server['timeout']);
		if ($retValue === false) {
			return false;
		}

		if ($redis->set($key, $isSerialize ? serialize($value) : $value)) {
			if ($expired !== false) {
				$redis->expire($key, $expired);
			}

			return true;
		}

		return false;
	}

	/*
	 * list, left push
	 */
	public static function lPush($key, $value, $server, $isSerialize = true)
	{
		$name = $server['ip'] . '-' . $server['port'];

		if (!isset(self::$redisHash[$name]) || self::$redisHash[$name] === null) {
			self::$redisHash[$name] = new Redis();
		}

		$redis = self::$redisHash[$name];

// 		$retValue = $redis->pconnect($server['ip'], $server['port'], $server['timeout']);
		$retValue = $redis->connect($server['ip'], $server['port'], $server['timeout']);
		if ($retValue === false) {
			return false;
		}

		return $redis->lPush($key, $isSerialize ? serialize($value) : $value);
	}

	/*
	 * list, right pop
	 */
	public static function rPop($key, $server, $isSerialize = true)
	{
		$name = $server['ip'] . '-' . $server['port'];
		if (!isset(self::$redisHash[$name]) || self::$redisHash[$name] === null) {
			self::$redisHash[$name] = new Redis();
		}
		$redis    = self::$redisHash[$name];
// 		$retValue = $redis->pconnect($server['ip'], $server['port'], $server['timeout']);
		$retValue = $redis->connect($server['ip'], $server['port'], $server['timeout']);
		if ($retValue === false) {
			return false;
		}
		$retValue = $redis->rPop($key);
		if ($retValue === false) {
			return false;
		}

		return $isSerialize ? unserialize($retValue) : $retValue;
	}

	/*
	 * list, length g
	 */
	public static function lLen($key, $server)
	{
		$name = $server['ip'] . '-' . $server['port'];
		if (!isset(self::$redisHash[$name]) || self::$redisHash[$name] === null) {
			self::$redisHash[$name] = new Redis();
		}
		$redis    = self::$redisHash[$name];
// 		$retValue = $redis->pconnect($server['ip'], $server['port'], $server['timeout']);
		$retValue = $redis->connect($server['ip'], $server['port'], $server['timeout']);
		if ($retValue === false) {
			return false;
		}
		$retValue = $redis->lLen($key);
		if ($retValue === false) {
			return false;
		}

		return $retValue;
	}


	/**
	 * 删除单个key，
	 * @param string $key
	 * @return bool false表示key不存在或者删除失败
	 */
	public static function Delete($key, $server)
	{
		$name = $server['ip'] . '-' . $server['port'];

		if (!isset(self::$redisHash[$name]) || self::$redisHash[$name] === null) {
			self::$redisHash[$name] = new Redis();
		}

		$redis = self::$redisHash[$name];

// 		$retValue = $redis->pconnect($server['ip'], $server['port'], $server['timeout']);
		$retValue = $redis->connect($server['ip'], $server['port'], $server['timeout']);
		if ($retValue === false) {
			return false;
		}

		$ret = $redis->delete($key);

		if ($ret !== 1) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 删除多个key，
	 * @param $keys array
	 * @param $server redis服务器参数
	 * @param $keyPattern key是否是正则表达式
	 * @return bool false表示有些key不存在或者删除失败
	 */
	public static function MDelete($keys, $server, $keyPattern = false)
	{
		$name = $server['ip'] . '-' . $server['port'];

		if (!isset(self::$redisHash[$name]) || self::$redisHash[$name] === null) {
			self::$redisHash[$name] = new Redis();
		}

		$redis = self::$redisHash[$name];

// 		$retValue = $redis->pconnect($server['ip'], $server['port'], $server['timeout']);
		$retValue = $redis->connect($server['ip'], $server['port'], $server['timeout']);
		if ($retValue === false) {
			return false;
		}

		if ($keyPattern) {
			$deleteKeys = array();
			foreach ($keys as $item) {
				$deleteKeys = array_merge($deleteKeys, $redis->keys($item));
			}
		} else {
			$deleteKeys = $keys;
		}

		$ret = $redis->delete($deleteKeys);

		if ($ret !== count($deleteKeys)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 只有当原来不存在此key并且set成功时才返回true
	 * 其余情况返回false

	 */
	public static function SetIfNotExist($key, $value, $server, $isSerialize = true, $expired = false)
	{
		$name = $server['ip'] . '-' . $server['port'];

		if (!isset(self::$redisHash[$name]) || self::$redisHash[$name] === null) {
			self::$redisHash[$name] = new Redis();
		}

		$redis = self::$redisHash[$name];

// 		$retValue = $redis->pconnect($server['ip'], $server['port'], $server['timeout']);
		$retValue = $redis->connect($server['ip'], $server['port'], $server['timeout']);
		if ($retValue === false) {
			return false;
		}

		if ($redis->setnx($key, $isSerialize ? serialize($value) : $value)) {
			if ($expired !== false) {
				$redis->expire($key, $expired);
			}

			return true;
		}

		return false;
	}

	public static function Increase($key, $inc, $server)
	{
		$name = $server['ip'] . '-' . $server['port'];

		if (!isset(self::$redisHash[$name]) || self::$redisHash[$name] === null) {
			self::$redisHash[$name] = new Redis();
		}

		$redis = self::$redisHash[$name];

// 		$retValue = $redis->pconnect($server['ip'], $server['port'], $server['timeout']);
		$retValue = $redis->connect($server['ip'], $server['port'], $server['timeout']);
		if ($retValue === false) {
			return false;
		}

		return $redis->incrBy($key, $inc);
	}

	public static function Decrease($key, $inc, $server)
	{
		$name = $server['ip'] . '-' . $server['port'];

		if (!isset(self::$redisHash[$name]) || self::$redisHash[$name] === null) {
			self::$redisHash[$name] = new Redis();
		}

		$redis = self::$redisHash[$name];

// 		$retValue = $redis->pconnect($server['ip'], $server['port'], $server['timeout']);
		$retValue = $redis->connect($server['ip'], $server['port'], $server['timeout']);
		if ($retValue === false) {
			return false;
		}

		return $redis->decrBy($key, $inc);
	}
}
