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
    'app_id'        => 'app_id' ,
    'account_id'    => 'account_id' ,
    'symbol'        => 'symbol' ,
    'from'          => 'from' ,
    'to'            => 'to' ,
    'coins'         => 'coins' ,
    'request_time'  => 'request_time' ,
    'transfer_id'   => 'transfer_id' ,
    'transfer_type' => 'transfer_type' ,
    'callback'      => 'callback' ,
);
$aryData['auth_code'] = $auth->sign( $aryData );
try{
    // 调用转账接口
    $ret = Client::post( Config::WALLET_HOST .'/transfer', $aryData  );
    // 验证网络状态
    if(!$ret->ok() ){
        $errOb = new Error( Config::WALLET_HOST , $ret );
        throw new  Exception( $errOb->message() , $errOb->code() );
    }
    $resData = $ret->json() ;
    // 验证业务逻辑
    if($resData['code'] !== 200 ){
        throw new Exception( $resData['msg'] ,$resData['code']);
    }
    return $resData;
}catch (Exception $e){
    $resData['msg'] = $e->getMessage();
    $resData['code'] = $e->getCode();
    return $resData ;
}
