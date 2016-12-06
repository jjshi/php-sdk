<?php
namespace Lomo;

/**
 * 钱包管理
 *
 */
class Wallet
{
	const IS_BETA_VERSION = true;

	const ASSESS_KEY = '49934457c2c34d1623dc23413c6e392c';
	const SECRET_KEY = '04cd1206e944ef4c1308118a259d513e';

	/** 创建账户 */
	const WALLET_API_NEW_ACCOUNT = '/wallet/address';
	/** 短信验证码 */
	const WALLET_API_SEND_SMS = '/sms';
	/** 转账 */
	const WALLET_API_ASYNC_TRANSFER = '/wallet/transfer';
	/** 查询转账状况 */

	/**
	 * 获得钱包API地址URI
	 * 请根据自己环境设置
	 * @return string
	 */
	public static function getUri()
	{
		return self::IS_BETA_VERSION === false ? 'https://zsygnbsydcym.lomocoin.com' : 'http://testqb.lomocoin.com';
	}
	/**
	 * 发送转账验证码
	 *
	 * @param string $_strPhone 手机号
	 * @param string $_strAccountId 用户标识
	 * @return boolean
	 *
	 */
	public function sendPhoneCode( $_strPhone ,$_strAccountId  )
	{
		// 发送数据
		$aryData = array(
			'phone_num'     => $_strPhone,
			'account_id'    => $_strAccountId,
			'app_key'       => self::ASSESS_KEY,
			'symbol'        => 'LMC',
		);
		return WalletApi::callApi( self::getUri().self::WALLET_API_SEND_SMS , $aryData , self::SECRET_KEY  );
	}

	/**
	 * 创建账户
	 *
	 * @param int $_intAccountId 账户ID
	 * @return boolean
	 */
	public function newAccount( $_intAccountId )
	{
		// 发送数据
		$aryData = array(
			'account_id' 	=> $_intAccountId,
			'app_key'       => self::ASSESS_KEY,
			'symbol'        => 'LMC',
		);

		return WalletApi::callApi( self::getUri().self::WALLET_API_NEW_ACCOUNT , $aryData , self::SECRET_KEY  );
	}


	/**
	 * 转账
	 *
	 * @param string $_strFromAdd 来源地址
	 * @param string $_strToAdd 发送地址
	 * @param string $_strPhone 手机号
	 * @param string $_strCode 验证码
	 * @param string $_type 验证类型
	 * @param float $_floatCoins 转账金额
	 * @return boolean
	 */
	public function transMoney( $_strFromAdd , $_strToAdd  , $_floatCoins , $_strPhone , $_strCode , $_type  )
	{
		// TODO 注意,每次请求的交易id不得重复,请自行设置
		$transfer_id = 11;
		// 发送数据
		$aryData = array(
			'symbol'        => 'LMC' ,
			'from_wallet'   => $_strFromAdd,
			'to_wallet'     => $_strToAdd ,
			'coins'         => $_floatCoins ,
			'transfer_id'   => $transfer_id ,
			'app_key'       => self::ASSESS_KEY,
		);
		if(!empty($_type)){
			$aryData['validation_code'] = $_strCode;
			$aryData['validation_phone'] = $_strPhone;
		}

		return WalletApi::callApi( self::getUri().self::WALLET_API_ASYNC_TRANSFER , $aryData , self::SECRET_KEY  );
	}
}