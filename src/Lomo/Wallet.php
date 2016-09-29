<?php
namespace Lomo;

use Lomo\Http\Client;
use Lomo\Http\Error;

final class Wallet
{
    public $upHost;
    public $upHostBackup;

    /**
     * 创建账户
     *
     * @param int $_intAccountId 账户ID
     * @param object  Auth $auth
     * @return boolean
     */
    public static function newAccount( $_intAccountId = 0 , Auth $auth )
    {
        // 整理数据
        $aryData = array(
            'account'    => $_intAccountId,
            'access_key' => $auth->getAccessKey()
        );

        $_aryData['auth_code'] = $auth->sign( $aryData );
        $ret = Client::get( Config::WALLET_HOST , $aryData  );
        if (!$ret->ok()) {
            return array(null, new Error(Config::WALLET_HOST , $ret));
        }
        return array($ret->json(), null);
    }
    /**
     * 发送转账验证码
     *
     * @param string $_strPhone 邮箱地址
     * @param object  Auth $auth

     * @return boolean
     */
    public static function sendPhoneCode( $_strPhone = '' , Auth $auth )
    {
        // 发送数据
        $aryData = array(
            'phone'         => $_strPhone,
            'access_key'    => $auth->getAccessKey()
        );
        $_aryData['auth_code'] = $auth->sign( $aryData );
        $ret = Client::get( Config::WALLET_HOST , $aryData  );
        if (!$ret->ok()) {
            return array(null, new Error(Config::WALLET_HOST , $ret));
        }
        return array($ret->json(), null);
    }

    /**
     * 转账
     *
     * @param string $_strFromAdd 来源地址
     * @param string $_strToAdd 发送地址
     * @param string $_strPhone 手机号
     * @param string $_strCode 验证码
     * @param float $_floatCoins 转账金额
     * @return boolean
     */
    public static function transMoney( $_strFromAdd = '' , $_strToAdd = '' , $_strPhone = '' , $_strCode = '' , $_floatCoins = 0 )
    {
        // 发送数据
        $aryData = array(
            'from'=>$_strFromAdd,
            'to'=>$_strToAdd,
            'coins'=>$_floatCoins,
            'phone'=>$_strPhone,
            'code'=>$_strCode,
            'codetype'=>'SMS',
        );

        $ret = Client::get( Config::WALLET_HOST , $aryData  );
        if (!$ret->ok()) {
            return array(null, new Error(Config::WALLET_HOST , $ret));
        }
        return array($ret->json(), null);
    }
}
