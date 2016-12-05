# lomocoin Resource Storage SDK for PHP

## 安装

* 通过composer，这是推荐的方式，可以使用composer.json 声明依赖，或者运行下面的命令。SDK 包已经放到这里 [`lomocoin/php-sdk`][install-packagist] 。
```bash
$ composer require lomocoin/php-sdk
```
* 直接下载安装，SDK 没有依赖其他第三方库

## 运行环境

| lomocoin SDK版本 | PHP 版本 |
|:--------------------:|:---------------------------:|
|          1.x         |  cURL extension,   5.3 - 5.6,7.0 |
|          2.x         |  cURL extension,   5.3 - 5.6,7.0 |


## 常见问题

- $error保留了请求响应的信息，失败情况下ret 为none, 将$error可以打印出来，提交给我们。
- API 的使用 demo 可以参考 [单元测试](https://github.com/lomocoin/php-sdk/blob/master/tests)。

## 代码贡献

详情参考[代码提交指南](https://github.com/lomocoin/php-sdk/blob/master/CONTRIBUTING.md)。

## 贡献记录

- [所有贡献者](https://github.com/lomocoin/php-sdk/contributors)

## 联系我们
 
- 如果发现了bug，欢迎提交 [issue](https://github.com/lomocoin/php-sdk/issues)
- 如果有功能需求，欢迎提交 [issue](https://github.com/lomocoin/php-sdk/issues)
- 如果要提交代码，欢迎提交 pull request

## 代码许可

The MIT License (MIT).详情见 [License文件](https://github.com/lomocoin/php-sdk/blob/master/LICENSE).

[packagist]: http://packagist.org
[install-packagist]: https://packagist.org/packages/lomocoin/php-sdk
