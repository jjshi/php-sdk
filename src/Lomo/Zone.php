<?php
namespace Lomo;

final class Zone
{
    public $upHost;
    public $upHostBackup;

    public function __construct($upHost, $upHostBackup)
    {
        $this->upHost = $upHost;
        $this->upHostBackup = $upHostBackup;
    }

    public static function zone0()
    {
        return new self('http://z.lomo.com', 'http://upload.lomo.com');
    }

    public static function zone1()
    {
        return new self('http://z1.lomo.com', 'http://z1.lomo.com');
    }
}
