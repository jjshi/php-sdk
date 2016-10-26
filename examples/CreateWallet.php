<?php
require_once __DIR__ . '/../autoload.php';

use Lomo\Auth;
use Lomo\Config;
use Lomo\Http\Client;

$accessKey = 'Access_Key';
$secretKey = 'Secret_Key';

// 初始化签权对象。
$auth = new Auth( $accessKey , $secretKey );
// 整理数据
$aryData = array(
    'access_Key'    => '_strAccessKey',
    'account_id'    => '_intAccountId',
    'request_time'  => '_intRequestTime',
    'symbol'        => '_strSymbol'
);
$aryData['auth_code'] = $auth->sign( $aryData );
try{
    // 调用创建钱包
    $ret = Client::post( Config::WALLET_HOST.'/wallet/address' , $aryData  );
    // 验证网络状态
    if(!$ret->ok() ){
        $errOb = new Error(Config::WALLET_HOST , $ret);
        throw new Exception($errOb->message(),$errOb->code());
    }
    $resData = $ret->json();
    // 验证业务码
    if($resData['code'] !== 200 ){
        throw new  Exception($resData['msg'],$resData['code']);
    }
    return $resData ;
}catch (Exception $e){
    $resData['msg'] = $e->getMessage();
    $resData['code'] = $e->getCode();
    return $resData ;
}


