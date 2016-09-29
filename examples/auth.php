<?php
require_once __DIR__ . '/../autoload.php';

use Lomo\Auth;

$accessKey = 'Access_Key';
$secretKey = 'Secret_Key';

// 初始化签权对象。
$auth = new Auth($accessKey, $secretKey);
