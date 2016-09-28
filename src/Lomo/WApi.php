<?php

namespace App\Libs\LomoWallet;

class WApi
{
    /**
     * 创建账户
     *
     * @param int $_intAccountId 账户ID
     * @return boolean
     */
    public function newAccount( $_intAccountId = 0 )
    {
        if ( empty( $_intAccountId ) )
            throw new CModelException( '钱包：创建账号参数错误！' );

        // 发送数据
        $aryData = array(
            'account'=>$_intAccountId,
        );

        return WApiRpc::callApi( self::getUri().self::WALLET_API_NEW_ACCOUNT , $aryData , WALLET_HOT_KEY , false );
    }
    /**
     * 发送转账验证码
     *
     * @param string $_strPhone 邮箱地址
     * @return boolean
     */
    public function sendPhoneCode( $_strPhone = '' )
    {
        // 发送数据
        $aryData = array(
            'phone'=>$_strPhone,
            'tmpl'=>1,
            'signature'=>5,
        );

        return CWalletApiRpc::callApi( self::getUri().self::WALLET_API_SEND_SMS , $aryData , WALLET_HOT_KEY , false );
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
    public function transMoney( $_strFromAdd = '' , $_strToAdd = '' , $_strPhone = '' , $_strCode = '' , $_floatCoins = 0 )
    {
        if ( empty( $_strFromAdd ) || empty( $_strToAdd ) || empty( $_strPhone ) || empty( $_strCode ) || empty( $_floatCoins ) )
            throw new CModelException( '钱包：转账参数错误！' );

        // 发送数据
        $aryData = array(
            'from'=>$_strFromAdd,
            'to'=>$_strToAdd,
            'coins'=>$_floatCoins,
            'phone'=>$_strPhone,
            'code'=>$_strCode,
            'tmpl'=>1,
            'codetype'=>'SMS',
        );

        return CWalletApiRpc::callApi( self::getUri().self::WALLET_API_TRANS , $aryData , WALLET_HOT_KEY );
    }

}
