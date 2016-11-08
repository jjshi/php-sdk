<?php
namespace Lomo;

use Lomo\Http\Client;
use Lomo\Http\Error;

final class Wallet
{
    /**
     * 发送转账验证码
     *
     * @param string $_intPhone 手机号
     * @param string $_intAccountId 用户开放平台标识id
     * @param object  Auth $auth

     * @return boolean
     */
    public static function sendPhoneCode($params = null) {
        return Client::post(Config::WALLET_HOST.'/sms', $params);
    }

    /**
     * 创建账户钱包地址
     *
     * @param int $_intAccountId 账户ID
     * @param object  Auth $auth
     * @return boolean
     */
    public static function newAccount($params = null) {
        return Client::post(Config::WALLET_HOST."/wallet/address", $params);
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
    public static function transMoney($params = null) {
        return Client::post(Config::WALLET_HOST."/wallet/transfer", $params);
    }
}
