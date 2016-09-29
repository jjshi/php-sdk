<?php
namespace Lomo;

final class Auth
{
    private $accessKey = '';
    private $secretKey = '';

    public function __construct($accessKey, $secretKey)         // 构造函数，默认为zone0
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }
    /**
     * 返回 accessKey
     *
     * @return string 返回accessKey
     */
    public function getAccessKey()
    {
        return $this->accessKey;
    }

    /**
     * 签名
     *
     * @param array $_aryData	需要签名的参数
     * @return string 返回签名结果
     */
    public function sign( $_aryData )
    {
        // 将KEY添加到合并数据
        $_aryData[] = $this->secretKey;
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
     * @return bool
     */
    public function verifySign( $_aryData = [] , $_strSign = ""  )
    {
        $sign = $this->sign( $_aryData );
        return $sign === $_strSign;
    }
}
