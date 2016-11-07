<?php
namespace Lomo;

use Lomo\Http\Client;
use Lomo\Http\Error;

final class Wallet
{
    public $upHost;
    public $upHostBackup;

    /**
     * 创建账户钱包地址
     *
     * @param int $_intAccountId 账户ID
     * @param object  Auth $auth
     * @return boolean
     */
    public static function newAccount( $_intAccountId = 0 , Auth $auth )
    {
        // 整理数据
        $aryData = array(
            'account_id'    => $_intAccountId,
            'access_key'    => $auth->getAccessKey(),
            'request_time'  => time(),
        );

        $_aryData['auth_code'] = $auth->sign( $aryData );
        $ret = Client::post( Config::WALLET_HOST.'/wallet/address' , $aryData  );
        if (!$ret->ok()) {
            return array(null, new Error(Config::WALLET_HOST , $ret));
        }
        return array($ret->json(), null);
    }
    /**
     * 发送转账验证码
     *
     * @param string $_intPhone 手机号
     * @param string $_intAccountId 用户开放平台标识id
     * @param object  Auth $auth

     * @return boolean
     */
    public static function sendPhoneCode( $_intPhone = '' ,$_intAccountId ,  Auth $auth )
    {
        // 发送数据
        $aryData = array(
            'access_key'    => $auth->getAccessKey(),
            'phone_num'     => $_intPhone,
            'account_id'    => $_intAccountId,

        );
        $_aryData['auth_code'] = $auth->sign( $aryData );
        $ret = Client::get( Config::WALLET_HOST.'/sms' , $aryData  );
        if (!$ret->ok()) {
            return array(null, new Error(Config::WALLET_HOST , $ret));
        }
        return array($ret->json(), null);
    }

    /**
     * 转账
     *
     * @param string $_strFromAdd 转出钱包地址
     * @param string $_strToAdd 转入钱包地址
     * @param int $_intTransferId 本次交易id,必须为递增整数
     * @param int $_floatCoins 币数
     * @return boolean
     */
    public static function transMoney( $_strFromAdd = '' , $_strToAdd = '' ,  $_floatCoins = 0 , $_intTransferId = 0 ,  Auth $auth)
    {
        // 发送数据
        $aryData = array(
            'from_wallet'   =>  $_strFromAdd,
            'to_wallet'     =>  $_strToAdd,
            'coins'         =>  $_floatCoins,
            'transfer_id'   =>  $_intTransferId,
            'symbol'        => 'LMC' ,
            'request_time'  => time() ,
            'access_key'    => $auth->getAccessKey(),
        );
        $ret = Client::post(  Config::WALLET_HOST.'/wallet/transfer' , $aryData  );
        if (!$ret->ok()) {
            return array(null, new Error(Config::WALLET_HOST , $ret));
        }
        return array($ret->json(), null);
    }
}
