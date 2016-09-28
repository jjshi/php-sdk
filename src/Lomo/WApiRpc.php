<?php
namespace App\Libs\LomoWallet;

/**
 * 钱包API调用
 */
final class WApiRpc
{
	public static function get($url, array $headers = array())
	{
		$request = new Request('GET', $url, $headers);
		return self::sendRequest($request);
	}

	public static function post($url, $body, array $headers = array())
	{
		$request = new Request('POST', $url, $headers, $body);
		return self::sendRequest($request);
	}
	/**
	 * 接口
	 *
	 * @param string $_strRoute	路由
	 * @param array $_aryData 传递的参数
	 * @param string $_strSignKey 加密串
	 * @return array
	 * 			<pre>
	 * 					return array( 'ISOK'=>bool,'DATA'=>array(),'ERROR'=>'错误号' );
	 * 			</pre>
	 */
	public static function callApi( $_strRoute = "" , $_aryData = array() , $_strSignKey = "" , $_boolNeedLbsId = true )
	{
		//补时间戳数据
		empty( $_aryData['time'] ) && $_aryData['time'] = time();
		//对参数进行签名
		$_aryData['auth_code'] = self::sign( $_aryData , $_strSignKey );
		$aryParams = http_build_query($_aryData);
		$url = $_strRoute.'/'.$aryParams;

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
		$curl_err = curl_error($curl);

		$resJson = json_decode( $res, true);
		if( $resJson === false || !empty($curl_err)) {
			$aryReturn['is_ok'] = 0;
			$aryReturn['msg'] = !empty($curl_err) ? $curl_err : $resJson;
		} else {
			$aryReturn['code'] = $resJson['ISOK'];
			$aryReturn['data'] = $resJson['DATA'] ;
			$aryReturn['msg'] = $resJson['MSG'] ;
		}
		// 关闭URL请求
		curl_close($curl);
		return $aryReturn;
	}

	/**
	 * POST方式请求接口
	 *
	 * @param string $_strRoute	路由
	 * @param array $_aryData 传递的参数
	 * @param string $_strSignKey 加密串
	 * @return array
	 *			<pre>
	 *					return array( 'ISOK'=>bool,'DATA'=>array(),'ERROR'=>'错误号' );
	 *			</pre>
	 */
	public static function callPostApi( $_strRoute = "" , $_aryData = array() , $_strSignKey = ""  )
	{
		//补时间戳数据
		empty( $_aryData['time'] ) && $_aryData['time'] = time();

		//对参数进行签名
		$_aryData['auth_code'] = self::sign( $_aryData , $_strSignKey );
		$aryParams = http_build_query($_aryData);

		// 初始化一个 cURL 对象
		$curl = curl_init();
		// 设置你需要抓取的URL
		curl_setopt($curl, CURLOPT_URL, $_strRoute);
		// 设置header
		curl_setopt($curl, CURLOPT_HEADER, false);
		// 设置不验证
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		// 解决301的问题
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION , 1);

		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		// 使用POST方式提交
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $aryParams);
		// 运行cURL，请求网页
		$res = curl_exec($curl);

		AdminLogModel::create(array(
			'al_uid'			=> TokenMiddleware::$tokenData['tk_val']['id'],
			'al_curl_route'		=> $_strRoute,
			'al_curl_log'		=> $res,
			'al_data'			=> $aryParams
		));
		$curl_err = curl_error($curl);

		$resJson = json_decode( $res, true);
		if( $resJson === false || !empty($curl_err)) {
			$aryReturn['is_ok'] = 0;
			$aryReturn['msg'] = !empty($curl_err) ? $curl_err : $resJson;
		} else {
			$aryReturn['code'] = $resJson['ISOK'];
			$aryReturn['data'] = $resJson['DATA'] ;
			$aryReturn['msg'] = $resJson['MSG'] ;
		}
		// 关闭URL请求
		curl_close($curl);
		return $aryReturn;
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
	
//end class
}
