<?php
//Create by MySQL Model generator

class Project_Model_Test extends System_DB_DataObject
{
    const ID          = 'id';
    const DEAL_ID  = 'dealId';
    const DEAL_CATEGORY_ID = 'dealCategoryId';
    const ORDER_ID        = 'orderId';
    const STATUS      = 'status';
    const WX_ID = 'wxid';
    const CASH_MONEY = 'cashMoney';
    const PAY_TYPE = 'payType';
    public $id;
    public $dealId;
    public $dealCategoryId;
    public $orderId;
    public $status;
    public $wxid;
    public $cashMoney;
    public $payType;

    public static function getMapping()
    {
        return array(
            'table'       => 'weixin_order',
            'key'         => self::ID,
            'columns'     => array(
                self::ID          => 'id',
                self::DEAL_ID  => 'dealId',
                self::DEAL_CATEGORY_ID => 'dealCategoryId',
                self::ORDER_ID        => 'orderId',
                self::WX_ID      => 'wxid',
                self::CASH_MONEY => 'cashMoney',
                self::STATUS => 'status',
                self::PAY_TYPE => 'payType'
            ),
            'columnTypes' => array(
                self::ID          => 'int',
                self::DEAL_ID  => 'string',
                self::DEAL_CATEGORY_ID => 'string',
                self::ORDER_ID        => 'string',
                self::WX_ID      => 'string',
                self::CASH_MONEY => 'string',
                self::STATUS => 'int',
                self::PAY_TYPE => 'string'
            )
        );
    }

    /**
     * @return
     */
    public static function getSourceConfig()
    {
        $config = System_Lib_App::app()->getConfig('dbConfig');

        return $config['weixin_order'];
    }

    /**
     * @return System_DB_MysqlAccessor
     */
    public static function dataAccess()
    {
        return System_DB_MysqlAccessor::useModel(get_class());
    }

    /**
     * @return
     */
    public static function getDataAccessName()
    {
        return 'System_DB_MysqlAccessor';
    }
}
