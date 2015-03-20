<?php

class System_DB_MysqlAccessor extends System_DB_DataAccessor
{


	const SORT_TYPE_DESC = 'desc';
	const SORT_TYPE_ASC = 'asc';

	public static $lastSql = null;
	
	/**
	 *
	 * @var PDO
	 */
	public $connection = null;
	public $readOnly = false;
	public $forceMaster = false;
	protected $sql;
	protected $pdoValues = array();
	protected $userDefineWhere = '';
	protected $userDefineValues = array();
	protected $userDefineFlag = false;

	/**
	 *
	 * @return <string>
	 */
	public static function getLastSql()
	{
		return System_DB_MysqlAccessor::$lastSql;
	}

	/**
	 *
	 * @return System_DB_MysqlAccessor
	 */
	public function forceMaster()
	{
		$this->forceMaster = true;
		return $this;
	}

	/**
	 *
	 * @param <string> $sql
	 * @param <array> $values
	 * @param <bool> $readOnly
	 * @return <mix>
	 */
	public function nativeSql($sql, $values, $readOnly = true)
	{
		$this->readOnly = $readOnly;
		$className = $this->modelName;
		$this->connection = $this->getConnection(call_user_func(array($this->modelName, 'getSourceConfig')));
		$stmt = $this->connection->prepare($sql);
		$stmt->execute($values);
		if ($readOnly)
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $stmt->rowCount();
	}

	/**
	 *
	 * @param <string> $sqlWhere
	 * @param <array> $values
	 * @return System_DB_MysqlAccessor
	 */
	public function userDefineWhere($sqlWhere, $values)
	{
		$this->userDefineWhere = $sqlWhere;
		$this->userDefineValues = $values;
		$this->userDefineFlag = true;
		return $this;
	}

	/**
	 *
	 * @return <array>
	 */
	public function find()
	{
		$this->readOnly = true;
		$fields = '*';
		if (count($this->loadFields))
		{
			$fields = '';
			foreach ($this->loadFields as $field)
				$fields .= " `{$this->mappingToDb($field)}` ,";
			$fields = rtrim($fields, ',');
		}
		$this->sql = "select {$fields} from `{$this->mapping['table']}` ";
		$this->appendWhere();
		$this->appendOrder();
		$this->appendLimit();
		return $this->initObjs($this->executeRead()->fetchAll(PDO::FETCH_ASSOC));
	}



	/**
	 *
	 * @return <int>
	 */
	public function update()
	{
		$this->sql = "update `{$this->mapping['table']}` ";
		$this->appendUpdate();
		$this->appendWhere();
		return $this->executeWrite();
	}

	/**
	 *
	 * @param <string> $type
	 * @return <int>
	 */
	public function insert($type = 'insert')
	{
		$this->sql = "{$type} into `{$this->mapping['table']}` ";
		$this->appendInsert();
		return $this->executeWrite();
	}

	/**
	 *
	 * @return <int>
	 */
	public function delete()
	{
		$this->sql = "delete from `{$this->mapping['table']}` ";
		$this->appendWhere();
		$this->appendLimit();
		return $this->executeWrite();
	}

	/**
	 *
	 * @return <int>
	 */
	public function count()
	{
		$this->sql = "select count(1) from `{$this->mapping['table']}` ";
		$this->appendWhere();
		return $this->executeRead()->fetchColumn();
	}

	public function groupCount()
	{
		if (empty($this->groups)) {
			throw new Exception('groupCount() must call group()');
		}
		$fields = '';
		foreach ($this->groups as $field) {
			if ($this->mappingToDb($field)) {
				$fields .= " `{$this->mappingToDb($field)}` ,";
			}
			else {
				$fields .= " {$field} ,";
			}
		}
		$fields .= " count(1) as `count`";
		$this->sql = "select {$fields} from `{$this->mapping['table']}` ";
		$this->appendWhere();
		$this->appendGroup();
		$this->appendOrder();
		$this->appendLimit();
		return $this->executeRead()->fetchAll();
	}
	/**
		new SQL;
	*/
	public function QuerySQl( $sql ){
		$this->sql = $sql;
		$this->pdoValues = array();	
		return $this->executeRead()->fetchAll(PDO::FETCH_ASSOC);
	}
	public function groupSum($fieldName)
	{
		if (empty($this->groups)) {
			throw new Exception('groupSum() must call group()');
		}
		$fields = '';
		foreach ($this->groups as $field) {
			if ($this->mappingToDb($field)) {
				$fields .= " `{$this->mappingToDb($field)}` ,";
			}
			else {
				$fields .= " {$field} ,";
			}
		}
		$fields .= " sum(`{$this->mappingToDb($fieldName)}`) as `sum`";
		$this->sql = "select {$fields} from `{$this->mapping['table']}` ";
		$this->appendWhere();
		$this->appendGroup();
		$this->appendOrder();
		$this->appendLimit();
		return $this->executeRead()->fetchAll();
	}

	/**
	 *
	 * @param <type> $name
	 * @param <type> $type
	 * @return System_DB_DataAccessor
	 */
	public function sort($name, $type = self::SORT_TYPE_DESC)
	{
		if ($this->mappingToDb($name)) {
			$this->sorts[$this->mappingToDb($name)] = $type;
		}else{
			$this->sorts[$name] = $type;
		}
		return $this;
	}
	/**
	 *
	 * @return <int>
	 */
	public function sum($field)
	{
		$this->sql = "select sum(`".$field."`) from `{$this->mapping['table']}` ";
		$this->appendWhere();
		return $this->executeRead()->fetchColumn(0);
	}

	/**
	 *
	 * @return <int>
	 */
	public function replace()
	{
		return $this->insert('replace');
	}

	/**
	 *
	 * @param <array> $rs
	 * @return <mix>
	 */
	public function initObjs($rs)
	{
		$objs = array();
		foreach ($rs as $r)
		{
			$obj = new $this->modelName();
			foreach ($this->mapping['columns'] as $objColumn => $dbColumn)
				$obj->$objColumn = isset($r[$dbColumn]) ? $r[$dbColumn] : null;
			$obj->isLoaded = true;
			$objs[] = $obj->afterInitObj();
		}
		return $objs;
	}

	/**
	 *
	 * @param <array> $sourceConfig
	 * @return  PDO
	 */
	protected function getConnection($sourceConfig)
	{
		return System_Lib_App::app()->pdo()->get($sourceConfig, $this->readOnly);
	}

	/**
	 *
	 * @return PDOStatement
	 */
	protected function executeInternal()
	{
		$startTime = microtime(true);
		$this->connection = $this->getConnection(call_user_func(array($this->modelName, 'getSourceConfig')));
		$stmt = $this->connection->prepare($this->sql);
		System_DB_MysqlAccessor::$lastSql = $this->sql;
		$stmt->execute($this->pdoValues);
		$endTime = microtime(true);
		$executeTime = number_format($endTime - $startTime, 3, '.', '');
		$sql = $this->parseSql($this->sql, $this->pdoValues);
		if ($executeTime > 0.1 || !empty($_GET['debug'])) {
			System_Lib_App::app()->recordRunTime('sql '.$executeTime.' '.$sql);
			$GLOBALS['sql_logs'][] = array(
				'time'    => $startTime,
				'sql'     => $this->sql,
				'values'  => $this->pdoValues,
				'sec'     => $executeTime,
			);
		}
		return $stmt;
	}

	public function parseSql($sql, $values)
	{
		$a = explode('?', $sql);
		$sql = '';
		for ($i=0;$i<count($a);$i++) {
			$sql .= $a[$i];
			if (!empty($values[$i])) {
				$sql .= $values[$i];
			}
		}
		return $sql;
	}

	/**
	 *
	 * @return <int>
	 */
	public function lastInsertId()
	{
		return $this->connection->lastInsertId();
	}

	/**
	 *
	 * @return <int>
	 */
	protected function executeWrite()
	{
		return $this->executeInternal()->rowCount();
	}

	/**
	 *
	 * @return PDOStatement
	 */
	protected function executeRead()
	{
		return $this->executeInternal();
	}

	/**
	 *
	 */
	protected function appendWhere()
	{
		if ((!count($this->filterNames) || !count($this->filterOps) || !count($this->filterValues)) && !$this->userDefineFlag)
			return false;
		$this->sql .= 'where ';
		$tmp = array();
		foreach ($this->filterNames as $k => $name)
		{
			if (is_array($this->filterValues[$k]))
			{
				$valueString = implode(' , ', array_fill(0, count($this->filterValues[$k]), '?'));
				$tmp[] = "`{$this->mappingToDb($name)}` {$this->filterOps[$k]} ( {$valueString} ) ";
				$this->pdoValues = array_merge($this->pdoValues, $this->filterValues[$k]);
			}
			else
			{
				$tmp[] = "`{$this->mappingToDb($name)}` {$this->filterOps[$k]} ? ";
				$this->pdoValues[] = $this->filterValues[$k];
			}
		}
		//user define where
		if ($this->userDefineFlag)
		{
			$tmp[] = '(' . $this->userDefineWhere . ')';
			$this->pdoValues = array_merge($this->pdoValues, $this->userDefineValues);
		}
		$this->sql .= implode(' and ', $tmp) . ' ';
	}

	protected function appendOrder()
	{
		if (!count($this->sorts))
			return false;
		$this->sql .= 'order by ';
		$tmp = array();
		foreach ($this->sorts as $k => $t)
			$tmp[] = "{$k} {$t}";
		$this->sql .= implode(' , ', $tmp) . ' ';
	}

	protected function appendGroup()
	{
		if (!count($this->groups))
			return false;
		$this->sql .= 'group by ';
		$tmp = array();
		foreach ($this->groups as $k)
			$tmp[] = "{$k}";
		$this->sql .= implode(' , ', $tmp) . ' ';
	}

	protected function appendLimit()
	{
		if (is_null($this->limit))
			return false;
		$offset = is_null($this->offset) ? '' : $this->offset . ',';
		$this->sql .= "limit {$offset}{$this->limit}";
	}

	protected function appendUpdate()
	{
		$this->sql .= 'set ';
		$tmp = array();
		foreach ($this->setFields as $k => $v)
			$tmp[] = "`{$this->mappingToDb($k)}` = ?";
		$this->sql .= implode(' , ', $tmp) . ' ';
		$this->pdoValues = array_merge($this->pdoValues, array_values($this->setFields));
	}

	protected function appendInsert()
	{
		$columns = array();
		$values = array();
		foreach ($this->setFields as $k => $v)
		{
			$columns[] = "`{$this->mappingToDb($k)}`";
			$values[] = '?';
		}
		$this->sql .= '( ' . implode(' , ', $columns) . ' ) values ( ' . implode(' , ', $values) . ' ) ';
		$this->pdoValues = array_merge($this->pdoValues, array_values($this->setFields));
	}

	/**
	 * @todo 	findPage方法，用于获取分页样式及数据，只需设置pageSize即可
	 * @param 	int 	$pageSize 每页展示的条数，即：步长
	 * @return  array 	array('html'=>分页的html样式内容,'data'=>该页对应的数据列表)
	 */
	public function findPage( $pageSize=16 )
	{
		//获取页码
		$page = System_Lib_App::app()->getRequest('t_page', System_DB_Request::TYPE_INT);
		//查询总页数
		$this->readOnly = true;
		$countAll = $this->count();
		$allRows = ceil( $countAll / $pageSize );
		//验证页码的有效性
		$page = ($page>$allRows) ? $allRows : $page;
		$page = ($page<1) ? 1 : $page;
		/**
		 * 获取对应的页面的数据
		 */
		$fields = '*';
		if (count($this->loadFields))
		{
			$fields = '';
			foreach ($this->loadFields as $field)
				$fields .= " `{$this->mappingToDb($field)}` ,";
			$fields = rtrim($fields, ',');
		}
		$this->sql = "select {$fields} from `{$this->mapping['table']}` ";
		//清空pdo值的内容
		$this->pdoValues = array();
		$this->appendWhere();
		$this->offset( ($page-1) * $pageSize );
		$this->limit($pageSize);
		$this->appendOrder();
		$this->appendLimit();
		$return = array();
		$data = $this->initObjs($this->executeRead()->fetchAll(PDO::FETCH_ASSOC));
		/**
		 * 定义分页样式
		 */
		//取得原始未经过转换的控制器和动作
		$controller = CONTROLLER;
		//$action     = ACTION;
		$action     = ACTION ? ACTION : Controller::DEFAULT_ACTION_FUNC_NAME_PERFIX;
		$urlManager = new System_DB_UrlManager();
		//获取原始的url参数，并重新设置上一页和下一页的参数，用于生曾url
		$urlParams = System_Lib_App::app()->getRequest();
		$PreUrlParams = $urlParams;
		$PreUrlParams['t_page'] = $page-1;
		$preUrl = $urlManager->createUrl($controller,$action,$PreUrlParams);
		$NextUrlParams = $PreUrlParams;
		$NextUrlParams['t_page'] = $page+1;
		$nextUrl = $urlManager->createUrl($controller,$action,$NextUrlParams);
		$html = "<div><span>共{$allRows}页&nbsp;，共{$countAll}条记录&nbsp;，当前为第{$page}页</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$preUrl."'>上一页</a>&nbsp;&nbsp;<a href='".$nextUrl."'>下一页</a></div>";
		//返回信息设置
		if( $data ){
			$return['data'] = $data;
			$return['html'] = $html;
		}
		//echo $html;
		return $return;
	}

	/**
	 * 设置内部的sql语句及语句中的值，方便联合查询
	 * @param string $sql 		sql语句
	 * @param array  $values 	预处理sql对应的值
	 * @param int    $usePage 	是否分页
	 * @param string $orderBy 	排序条件，仅在处理分页时有效，如果不分页，排序直接写到sql即可，如果排序，不要将排序写到sql
	 * @return array 注意：返回结果为数组类型，不是对象类型，这是因为没有经过ORM处理，无法完成多个model的属性绑定
	 */
	function fetchBySql($sql,$values=array(),$usePage=0,$pageSize=16){
		
		if( empty($sql) ){
			return $this;
		}
		$this->sql = $sql;
		$this->pdoValues = $values;
		
		if( $usePage ){
			//获取页码
			$page = System_Lib_App::app()->getRequest('t_page', System_DB_Request::TYPE_INT);
			//查询总页数
			$this->readOnly = true;
			$tempSql = $this->sql;
			//判断是否为聚合查询
			if( stripos($this->sql, 'group')==false ){
				$parren = '/(.*?)select(.|\s)*?from(.*?)/i';
				$replace = '${1}SELECT count(*) AS count FROM ${3}';
				$this->sql = preg_replace($parren, $replace, $tempSql);				
			}else{
				$this->sql = "SELECT COUNT(*) AS count FROM ( ". $this->sql ." ) as tmp";
			}
			$countRes = $this->executeRead()->fetchAll();
			$count = empty($countRes) ? 0 : $countRes[0]['count'];
			$this->sql = $tempSql;
			$allRows = ceil( $count / $pageSize );
			//验证页码的有效性
			$page = ($page>$allRows) ? $allRows : $page;
			$page = ($page<1) ? 1 : $page;
			/**
			 * 获取对应的页面的数据
			 */
			$this->sql .= " LIMIT " . (($page-1) * $pageSize) . "," .$pageSize;
			$return = array();
			$data = $this->executeRead()->fetchAll(PDO::FETCH_ASSOC);
			/**
			 * 定义分页样式
			 */
			//取得原始未经过转换的控制器和动作
			$controller = CONTROLLER;
			//$action     = ACTION;
			$action     = ACTION ? ACTION : Controller::DEFAULT_ACTION_FUNC_NAME_PERFIX;
			$urlManager = new System_DB_UrlManager();
			//获取原始的url参数，并重新设置上一页和下一页的参数，用于生曾url
			$urlParams = System_Lib_App::app()->getRequest();
			$PreUrlParams = $urlParams;
			$PreUrlParams['t_page'] = $page-1;
			$preUrl = $urlManager->createUrl($controller,$action,$PreUrlParams);
			$NextUrlParams = $PreUrlParams;
			$NextUrlParams['t_page'] = $page+1;
			$nextUrl = $urlManager->createUrl($controller,$action,$NextUrlParams);
			$html = "<div><span>共{$allRows}页&nbsp;，共{$count}条记录，每页{$pageSize}条，当前为第{$page}页</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$preUrl."'>上一页</a>&nbsp;&nbsp;<a href='".$nextUrl."'>下一页</a></div>";
			//返回信息设置
			if( $data ){
				$return['data'] = $data;
				$return['html'] = $html;
			}
		}else{
			$return = $this->executeRead()->fetchAll(PDO::FETCH_ASSOC);
		}
		return $return;



	}


	/**
	 * 获取pdoValues
	 */
	public function getPdoValues(){
		return $this->pdoValues;
	}


}