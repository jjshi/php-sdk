<?php
namespace Lomo;
/**
 * 钱包API调用
 * Created by PhpStorm.
 * User: zihao
 * Date: 16/12/2
 * Time: 下午3:03
 */
class WalletApi
{
    /**
     * 接口
     *
     * @param string $_strRoute	路由
     * @param array $_aryData 传递的参数
     * @param string $_strSignKey 加密串
     * @return array
     *          (
     *              'code'  => 200,
     *              'data'  => array(),
     *              'msg'   => '错误消息'
     *          );
     */
    public static function callApi( $_strRoute = "" , $_aryData = array() , $_strSignKey = ""  )
    {
        //补时间戳数据
        if ( empty( $_aryData['request_time'] ) ){
            $_aryData['request_time'] = time();
        }
        //对参数进行签名
        $sign = self::sign( $_aryData , $_strSignKey );
        $_aryData['auth_code'] = $sign;
        $url = $_strRoute.'?'.http_build_query($_aryData);

        // 初始化一个 cURL 对象
        $curl = curl_init();
        // 设置你需要抓取的URL
        curl_setopt($curl, CURLOPT_URL, $url);
        // 设置header
        curl_setopt($curl, CURLOPT_HEADER, false);
        // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 设置不验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // 运行cURL，请求网页
        $res = curl_exec($curl);
        $error = curl_error($curl);
        // 关闭URL请求
        curl_close($curl);
        if(!empty($error)){
            return [
                'code' => curl_errno($curl),
                'msg'  => $error,
            ];
        }
        $resJson = json_decode( $res, true);
        if( empty($resJson)){
            return [
                'code' => 500,
                'msg'  => '解析失败返回值,请检查原因',
            ];
        }
        return $resJson;
    }

    /**
     * POST方式请求接口
     *
     * @param string $_strRoute	路由
     * @param array $_aryData 传递的参数
     * @param string $_strSignKey 加密串
     * @return array
     *          (
     *              'code'  => 200,
     *              'data'  => array(),
     *              'msg'   => '错误消息'
     *          );
     */
    public static function callPostApi( $_strRoute = "" , $_aryData = array() , $_strSignKey = "")
    {
        //补时间戳数据
        if ( empty( $_aryData['request_time'] ) ){
            $_aryData['request_time'] = time();
        }
        //对参数进行签名
        $sign = self::sign( $_aryData , $_strSignKey );
        $_aryData['auth_code'] = $sign;
        $strData = http_build_query($_aryData);
        // 初始化一个 cURL 对象
        $curl = curl_init();
        // 设置你需要抓取的URL
        curl_setopt($curl, CURLOPT_URL, $_strRoute);
        // 设置header
        curl_setopt($curl, CURLOPT_HEADER, false);
        // 设置不验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        // 使用POST方式提交
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $strData);
        // 运行cURL，请求网页
        $res = curl_exec($curl);
        $error = curl_error($curl);
        // 关闭URL请求
        curl_close($curl);

        if(!empty($error)){
            return [
                'code' => curl_errno($curl),
                'msg'  => $error,
            ];
        }
        $resJson = json_decode( $res, true);
        if( empty($resJson)){
            return [
                'code' => 500,
                'msg'  => '解析失败返回值,请检查原因',
            ];
        }
        return $resJson;
    }

    /**
     * 验证签名
     *
     * @param array $_aryData
     * @param string $_strSign
     * @param string $_strSignKey	加密key
     * @return bool
     */
    public static function verifySign( $_aryData = "" , $_strSign = "" , $_strSignKey = "" )
    {
        $sign = self::sign( $_aryData , $_strSignKey );
        return $sign === $_strSign;
    }

    /**
     * 签名
     *
     * @param array $_aryData	需要签名的参数
     * @param string $_strSignKey	加密key
     * @return string 返回签名结果
     */
    public static function sign( $_aryData , $_strSignKey = "" )
    {
        // 将KEY添加到合并数据
        $_aryData[] = $_strSignKey;
        sort( $_aryData , SORT_STRING );
        // 合并数据
        $sign = implode( "_" , $_aryData );
        // 将字符串签名，获得签名结果
        $sign = md5($sign);
        return $sign;
    }

//end class
}
