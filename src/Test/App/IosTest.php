<?php

namespace cccdl\tencent_cloud_face\Test\App;

use cccdl\tencent_cloud_face\Core\App\Ios;
use cccdl\tencent_cloud_face\Test\Config;
use PHPUnit\Framework\TestCase;

class IosTest extends TestCase
{
    public function testIos()
    {
        $config = Config::getConfig();
        $ios = new Ios($config['WBappid'], $config['secret'], $config['license']);
        $ios->getAccessToken();
    }
}