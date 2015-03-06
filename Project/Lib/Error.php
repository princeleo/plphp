<?php

// 本类处于过渡状态，有新增的错误码和提示请用const和$ERR_MSG_MAP，其余两块逐步废弃
// 有不清楚的地方可以咨询terryxia或yunteng

class Tuan_Lib_Error
{
	//定义错误码
	//本静态变量，待后台错误码规范化后逐步废弃
	public static $TUAN_ERR_CODE = array
	(
		'SUCC'										=>	0, 				//OK
		'PARAMETER_ERR'								=>	-100001,		//参数错误
		'NOT_LOGIN'									=>	-100002,		//未登陆
		'SVR_ERR'									=>	-100005,		//操作后台失败
		'NOT_CLUB'									=>	-100006,		//非会员不能操作
		'ERR_REFUND'								=>	-100007,		//系统出错，会退款给用户
		'ERR_REDO'									=>	-100008,		//系统出错，会重试
		'NOTPAY'									=>	-100009,		//未支付
		'NOTTUAN'									=>	-100010,		//未成团
		'OVERUSERLIMIT'								=>	-100011,		//超过单人限制
		'OVERTOTALLIMIT'							=>	-100012,		//超过总量限制
		'OVERTIME'									=>	-100013,		//已经过期
		'PAYED'										=>	-100014,		//已经支付过了
		'REFUNDED'									=>	-100015,		//已经退款
		'CREATEOVERTIME'							=>	-100016,		//创建订单已经过期
		'CREATEOVERTOTALLIMIT'						=>	-100017,		//创建订单超过总量限制
		'CREATEOVERUSERLIMIT'						=>	-100018,		//创建订单超过单人限制
		'CREATEOVERFREQUENCY'						=>	-100019,		//创建订单超过频率限制
		'REFUND_GROUPON_INVALID'					=>	-100020,		//资源不允许退款
		'REFUND_ORDER_INVALID'						=>	-100021,		//订单下可退验证码数目不足
		'HAS_DIRTY_WORD'							=>	-100022,		//有脏话
		'INSERT_ERR'								=>	-100023,		//插入评论内容失败
		'TOO_MANY_WORD'								=>	-100024,		//字数超过限制
		'TOKEN_ERROR'								=>	-100025,		//token错误，防止CSRF
		'COMMENT_OUTDATE'							=>	-100026,		//已经超过评论时间 （消费时间超过31天）
		'REPEAT_COMMENT'							=>	-100027,		//重复点评
		'UNEXPECT_ERROR'							=>	-100028,
		'REFUND_FEE_TOO_SMALL'						=>	-100029,		//订单退款金额小于财付通优惠券金额
		'REFUND_RETRY_NEXTDAY'						=>	-100030,		//全额预付代理商的团购资源不允许当天购买当天退款
		'DIFFERENT_USER'							=>	-100031,		//用其他账号登录（地址管理时用）
		'ADDRESS_NOT_FOUND'							=>	-100032,		//该地址不存在
		'VERIFY_CODE_INVALID'						=>	-100033,		//验证码错误
		'GROUPON_HAVE_NO_ORDER'						=>	-100034,		//团购没有订单

		'SINGLE_LIMIT_AT_LEAST_LOWER_THAN'			=>	-100091,		//创建订单低于单人购买下限
		'SINGLE_LIMIT_AT_LEAST_CAUSE_CLOSED'		=>	-100092,		//创建订单时由于单人购买下限大于剩余库存导致无法购买
	);


	// 后续逐步迁移成此方法，将错误码与展示给用户的错误信息匹配，
	// （目前仅列出评论用到的错误，后续开发请逐步补充，并规范化）
	const SUCC							= 0;			//成功
	const PARAMETER_ERR					= -100001;		//参数错误
	const NOT_LOGIN						= -100002;		//未登陆
	const SVR_ERR						= -100005;		//操作后台失败
	const PAYED							= -100014;		//已经支付过了
	const REFUNDED						= -100015;		//已经退款
	const TOO_MANY_WORD					= -100024;		//字数超过限制
	const TOKEN_ERROR					= -100025;		//token错误，防止CSRF
	const DIFFERENT_USER				= -100031;		//用其他账号登录（地址管理/(新增/取消)收藏时用）
	const ADDRESS_NOT_FOUND				= -100032;		//该地址不存在

	const HAS_DIRTY_WORD				= -100022;		//有敏感词
	const REPEAT_COMMENT				= -100027;		//重复点评
	const NO_COMMENT_RIGHT				= -100035;		//订单没有点评资格
	const COMMENT_ADD_RIGHT_EXPIRED		= -100036;		//点评发表资格过期
	const COMMENT_MODIFY_RIGHT_EXPIRED	= -100037;		//点评修改资格过期
	const NO_COMMENT					= -100038;		//没有点评过
	const COMMENT_DIFFERENT_USER		= -100039;		//用其他账号登录（评论时用）
	const GROUPON_NOT_FOUND				= -100040;		//团购不存在
	const ORDER_NOT_FOUND				= -100041;		//订单不存在
	const GROUPON_CANNOT_COMMENT		= -100042;		//团购资源不能评论
	const COMMENT_NOT_FOUND				= -100043;		//评论不存在

	const CONFIRM_DIFFERENT_USER		= -100044;		//用其他账号登录（确认收货时用）
	const CONFIRM_EXIPRED				= -100045;		//已过确认收货有效期
	const CONFIRM_DUPLICATED			= -100046;		//重复确认收货

	const CASH_COUPON_DIFFERENT_USER	= -100047;		//用其他账号登录（领取抵用券时用）
	const CASH_COUPON_ALREADY_FETCHED	= -100048;		//批次已领取
	const CASH_COUPON_NOT_NEW_USER		= -100049;		//非新人
	const CASH_COUPON_INVALID_BATCHID	= -100050;		//批次无效
	const CASH_COUPON_OUT_OF_STOCK		= -100051;		//批次已领完
	const CASH_COUPON_NOT_VIP_USER		= -100052;		//非QQ会员
	const REFUND_ERROR					= -100060;		//退款失败

	const GROUPON_TYPE_ERROR			= -100100;		//团购类型不正确
	const MOVIE_SMS_SEND_FAILD			= -100101;		//电影短信发送失败
	const SMS_SEND_FAILD				= -100102;		//短信发送失败
	const NOT_FOUND_TOKEN				= -100103;		//未找到匹配的订单和凭证

	const FAV_ADD_ERR					= -100104;		//收藏失败
	const FAV_CANCEL_ERR				= -100105;		//取消收藏失败

	const GROUPON_DISCOUNT_LIMITED		= -10000553;	//团购折扣优惠超限
	const GROUPON_DISCOUNT_EXPIRED		= -10000554;	//团购折扣优惠已失效（只有会员独享时出现）
	const GROUPON_ACCOUNT_ERROR			= -10000556;	//身份账号类型错误（只有会员独享时出现）

	const ORDER_CANCELED				= -900001;		//用户已取消订单
	const OUT_OF_STOCK					= -900002;		//库存余量不足
	const CASH_COUPON_USED				= -900003;		//抵用券已被其他订单使用
	const EXT_CHECK_FAILED					= -900004;		//未通过外部对接系统售卖条件校验


	// 注： 不同的错误码展示给用户错误信息可能相同
	// 目标: 后台Server错误码规范化，
	//       PHP直接产生错误码当作WebServer加入到Server错误码规范中
	public static $ERR_MSG_MAP = array(

		// *** WebServer错误 ***
		// （目前仅列出评论用到的错误，后续开发请逐步补充，并规范化）

		// 通用错误
		self::PARAMETER_ERR						=>	'参数错误',
		self::NOT_LOGIN							=>	'用户未登录',
		self::SVR_ERR							=>	'系统错误',
		self::TOKEN_ERROR						=>	'参数错误',
		self::DIFFERENT_USER					=>  '您已切换帐号，请刷新页面后重试',

		// 点评相关错误
		self::HAS_DIRTY_WORD					=>	'包含非法字段',
		self::REPEAT_COMMENT					=>	'请勿重复点评',
		self::NO_COMMENT_RIGHT					=>	'参数错误',
		self::COMMENT_ADD_RIGHT_EXPIRED			=>	'有效期已过',
		self::COMMENT_MODIFY_RIGHT_EXPIRED		=>	'有效期已过',
		self::NO_COMMENT						=>	'参数错误',
		self::COMMENT_DIFFERENT_USER			=>	'登陆账号错误',
		self::GROUPON_NOT_FOUND					=>	'参数错误',
		self::ORDER_NOT_FOUND					=>	'参数错误',
		self::GROUPON_CANNOT_COMMENT			=>	'参数错误',
		self::COMMENT_NOT_FOUND					=>	'参数错误',

		// 确认收货相关错误
		self::CONFIRM_DIFFERENT_USER			=>	'登陆账号错误，请刷新页面',
		self::CONFIRM_EXIPRED					=>	'已过确认收货有效期',
		self::CONFIRM_DUPLICATED				=>	'重复确认收货',

		// 抵用券相关错误
		self::CASH_COUPON_DIFFERENT_USER		=>	'您已切换帐号，请刷新页面后重试',
		self::CASH_COUPON_INVALID_BATCHID		=>	'暂无可领券',
		self::CASH_COUPON_ALREADY_FETCHED		=>	'抱歉，您已领取过该抵用券',
		self::CASH_COUPON_NOT_NEW_USER			=>	'抱歉，仅新人用户可领券',
		self::CASH_COUPON_OUT_OF_STOCK			=>	'抱歉，抵用券已领完',
		self::CASH_COUPON_NOT_VIP_USER			=>	'抱歉，您不是QQ会员，领取特权券不成功',
		self::REFUND_ERROR						=>	'系统繁忙，请稍后再试！',
		self::GROUPON_TYPE_ERROR				=>	'团购类型不正确',
		self::MOVIE_SMS_SEND_FAILD				=>	'短信发送失败',
		self::SMS_SEND_FAILD					=>	'短信发送失败',
		self::NOT_FOUND_TOKEN					=>	'未找到匹配的订单和凭证',

		self::FAV_ADD_ERR						=>	'收藏失败，请稍后再试',
		self::FAV_CANCEL_ERR					=>	'取消收藏失败，请稍后再试',

		-1712			=>	'交叉库存超限',
		-1713			=>	'未通过外部对接系统售卖条件校验',

		// *** CommentServer错误 ***
		-20000999		=>	'参数错误',						// 参数错误 COMMENT_RET_PARAMERR
		-20000998		=>	'系统错误',						// 系统错误 COMMENT_RET_SYSERR
		-20000997		=>	'参数错误',						// 非法资源 COMMENT_RET_RESOURCEILLEGAL
		-20000996		=>	'参数错误',						// 无点评权限 COMMENT_RET_NOCOMMENTRIGHT
		-20000995		=>	'请勿重复点评',					// 已经点评过 COMMENT_RET_HASCOMMENTED
		-20000994		=>	'有效期已过',						// 点评资格过期 COMMENT_RET_RIGHT_EXPIRED
		-20000993		=>	'有效期已过',						// 点评只读 COMMENT_RET_READONLY

		// *** OrderServer错误 （确认收货）***
		// 后续做错误码统一
		-1				=>	'系统错误',						// 读取、更新TTC失败 ORDER_RET_SYSERR
		-70				=>	'参数错误',						// 订单号不存在 ORDER_RET_NOTOKEN
		-190			=>	'参数错误',						// 传入团购id和订单不匹配 ORDER_RET_GROUPONMIS
		-90				=>	'参数错误',						// 订单未支付 ORDER_RET_NOTPAY
		-110			=>	'参数错误',						// 已验证 ORDER_RET_TOKENUSERD
		-120			=>	'参数错误',						// 已退款 ORDER_RET_TOKENREFUND
		-210			=>	'订单状态不合法',					// 订单未发货，或者订单已确认收货（，或者是导码资源） ORDER_RET_TOKENINVALID
		-80				=>	'参数错误',						// 团购未成团 ORDER_RET_NOTTUAN
		-100			=>	'团购已过期',						// 团购已过验证有效期 ORDER_RET_TOKENOVERTIME
		-250			=>	'此区域不售卖',					//实物，区域不售卖
		-240			=>	'地址省份信息不正确',				//省份地址信息不正确

		0x2016			=>	'重复确认收货',					// ERROR_ORDER_Received
		0x1019			=>	'已过最晚确认收货时间',			// ERROR_Groupon_OverTime

		// *** FilterServer错误 ***

		// 用户识别相关
		self::GROUPON_DISCOUNT_LIMITED			=>	'折扣优惠超限',
		self::GROUPON_DISCOUNT_EXPIRED			=>	'折扣优惠已失效',
		self::GROUPON_ACCOUNT_ERROR				=>	'身份账号类型错误',

		// 抵用券下单
		-2902			=>	'当前选择的抵用券已被使用，请重新选择',	// 抵用券已使用,ENU_RET_USER_CASH_COUPON_ALREADY_USED
		-2903			=>	'当前选择的抵用券已过期，请重新选择',	// 抵用券已过期,ENU_RET_USER_CASH_COUPON_ALREADY_EXPIRED
		-2904			=>	'该抵用券已与其它订单绑定，请在“我的团购”中取消之前的订单后刷新当前页面，才可选择此券',	// 抵用券已绑定,ENU_RET_USER_CASH_COUPON_ALREADY_BIND
		-2802			=>	'本单团购当前已不支持使用抵用券',		// 抵用使用范围过期,ENU_RET_CASH_COUPON_GROUPON_EXPIRED
		-4001			=>	'本单团购当前已不支持使用抵用券',		// 资源类型已不能使用抵用券,ENU_RET_GROUPON_ITEM_MAIN_CAN_NOT_USE_COUPON
		-3001			=>	'抱歉，此手机号码已经使用过此券',		//抵用券防刷提示！手机号-批次-面值=>数量超过限制

		// 领取抵用券
		-2600			=>	'抱歉，仅新人用户可领券',			// 用户非新人,ENU_RET_CASH_COUPON_BAD_TYPE
		-2601			=>	'暂无可领券',						// 未确认发券,ENU_RET_CASH_COUPON_OUT_OF_SERVICE
		-2602			=>	'暂无可领券',						// 批次已过期,ENU_RET_CASH_COUPON_EXPIRED
		-2603			=>	'暂无可领券',						// 批次不存在,ENU_RET_CASH_COUPON_INVAL_BATCH_ID
		-2604			=>	'抱歉，抵用券已领完',				// 批次已领完,ENU_RET_CASH_COUPON_OUT_OF_NUM
		-2901			=>	'抱歉，您已领取过该抵用券',			// 已领取,ENU_RET_USER_CASH_COUPON_ALREADY_CHECK_OUT

	);

	// 本函数，待后台错误码规范化后废弃
	public static function mapErrCode($SvrErrCode) {
		switch ($SvrErrCode) {
			case 0:
				return self::$TUAN_ERR_CODE['SUCC'];
			case -10:
				return self::$TUAN_ERR_CODE['PARAMETER_ERR'];
			case -11:
				return self::$TUAN_ERR_CODE['ADDRESS_NOT_FOUND'];
			case -20:
				return self::$TUAN_ERR_CODE['REFUNDED'];
			case -30:
				return self::$TUAN_ERR_CODE['PAYED'];
			case -40:
				return self::$TUAN_ERR_CODE['OVERTIME'];
			case -50:
				return self::$TUAN_ERR_CODE['OVERTOTALLIMIT'];
			case -60:
				return self::$TUAN_ERR_CODE['OVERUSERLIMIT'];
			case -80:
				return self::$TUAN_ERR_CODE['NOTTUAN'];
			case -90:
				return self::$TUAN_ERR_CODE['NOTPAY'];
			case -130:
				return self::$TUAN_ERR_CODE['ERR_REDO'];
			case -140:
				return self::$TUAN_ERR_CODE['ERR_REFUND'];
			case -160:
				return self::$TUAN_ERR_CODE['CREATEOVERTIME'];
			case -170:
				return self::$TUAN_ERR_CODE['CREATEOVERTOTALLIMIT'];
			case -180:
				return self::$TUAN_ERR_CODE['CREATEOVERUSERLIMIT'];
			case -900:
				return self::$TUAN_ERR_CODE['CREATEOVERFREQUENCY'];
			case -910:
				return self::$TUAN_ERR_CODE['SINGLE_LIMIT_AT_LEAST_LOWER_THAN'];
			case -920:
				return self::$TUAN_ERR_CODE['SINGLE_LIMIT_AT_LEAST_CAUSE_CLOSED'];
			case -200:
				return self::$TUAN_ERR_CODE['REFUND_GROUPON_INVALID'];
			case -100:
			case -210:
				return self::$TUAN_ERR_CODE['REFUND_ORDER_INVALID'];
			case -230:
				return self::ORDER_CANCELED;
			case -1712:
				return self::OUT_OF_STOCK;
			case -1713:
				return self::EXT_CHECK_FAILED;
			case -2902:
				return self::CASH_COUPON_USED;
			default:
				return self::$TUAN_ERR_CODE['SVR_ERR'];
		}
	}

	public static function errorLog($errmsg, $method='', $line='')
	{
		//error_log(" [$method~$line] $errmsg");
		//var_dump(" [$method~$line] $errmsg");
		System_Lib_Log::NetworkLog(System_Lib_Log::getAppLogId(),
									System_Lib_Log::NETWORKLOG_LOG_ERROR,
									" [$method~$line] $errmsg");
	}
}
