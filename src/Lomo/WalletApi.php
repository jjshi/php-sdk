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
        $ret = curl_errno($curl);
        $error = curl_error($curl);
        if($ret !== 0){
            return [
                'code' => $ret,
                'msg'  => $error,
            ];
        }
        // 关闭URL请求
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        // 处理数据,没有返回,或者curl报错的情况下直接返回,如果钱包有返回,直接返回。
        if($code !=200 || empty($res) || substr($res,0,15) === '<!DOCTYPE html>'){
            $msg = !empty( self::$statusTexts[$code] ) ? self::$statusTexts[$code] : 'Maintenance' ;
            return [
                'code' => $code,
                'msg'  => $msg,
            ];
        }
        return json_decode((string) $res, true, 512);
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
        $ret = curl_errno($curl);
        $error = curl_error($curl);
        if($ret !== 0){
            curl_close($curl);
            return [
                'code' => $ret,
                'msg'  => $error,
            ];
        }
        // 关闭URL请求
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        // 处理数据,没有返回,或者curl报错的情况下直接返回,如果钱包有返回,直接返回。
        if($code !=200 || empty($res) || substr($res,0,15) === '<!DOCTYPE html>'){
            $msg = !empty( self::$statusTexts[$code] ) ? self::$statusTexts[$code] : 'Maintenance' ;
            return [
                'code' => $code,
                'msg'  => $msg,
            ];
        }
        return json_decode((string) $res, true, 512);
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
    private static function parseHeaders($raw)
    {
        $headers = array();
        $headerLines = explode("\r\n", $raw);
        foreach ($headerLines as $line) {
            $headerLine = trim($line);
            $kv = explode(':', $headerLine);
            if (count($kv) >1) {
                $headers[$kv[0]] = trim($kv[1]);
            }
        }
        return $headers;
    }
    /** @var array Mapping of status codes to reason phrases */
    private static $statusTexts = array(
        -1 => 'Maintenance',
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Reserved for WebDAV advanced collections expired proposal',
        426 => 'Upgrade required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates (Experimental)',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    );

//end class
}
