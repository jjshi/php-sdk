<?php
namespace Lomo;

use Lomo;

final class Auth
{
    private $accessKey;
    private $secretKey;

    public function __construct($accessKey, $secretKey)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }
    /**
     * 签名
     *
     * @param array $_aryData	需要签名的参数
     * @param string $_strSignKey	需要签名的密匙
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

    /**
     * 验证签名
     *
     * @param array $_aryData		传输数据
     * @param string $_strSign		传输加密串
     * @param string $_strSignKey	本地存储密匙
     * @return bool
     */
    public static function verifySign( $_aryData = [] , $_strSign = "" , $_strSignKey = "" )
    {
        $sign = self::sign( $_aryData , $_strSignKey );
        return $sign === $_strSign;
    }
}
