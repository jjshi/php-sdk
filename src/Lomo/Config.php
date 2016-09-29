<?php
namespace Lomo;

final class Config
{
    const SDK_VER = '0.0.1';

    const BLOCK_SIZE = 4194304; //4*1024*1024 分块上传块大小，该参数为接口规格，不能修改

    const API_HOST = 'http://api.lomo.com';            // 数据处理操作Host
    const WALLET_HOST = 'http://api.lomo.com';            // 数据处理操作Host

    private $upHost;                                    // 上传Host
    private $upHostBackup;                              // 上传备用Host

    public function __construct(Zone $z = null)         // 构造函数，默认为zone0
    {
        if ($z === null) {
            $z = Zone::zone0();
        }
        $this->upHost = $z->upHost;
        $this->upHostBackup = $z->upHostBackup;
    }

    public function getUpHost()
    {
        return $this->upHost;
    }

    public function getUpHostBackup()
    {
        return $this->upHostBackup;
    }
}
