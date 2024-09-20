<?php

namespace cccdl\tencent_cloud_face\Test\App;

use cccdl\tencent_cloud_face\Core\App\Ios;
use cccdl\tencent_cloud_face\Exception\cccdlException;
use cccdl\tencent_cloud_face\Test\Config;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

require '../../../vendor/autoload.php';

class IosTest extends TestCase
{
    /**
     * @return void
     * @throws GuzzleException
     * @throws cccdlException
     */
    public function testGetAccessToken()
    {
        $config = Config::getConfig();
        $ios = new Ios($config['WBappid'], $config['secret'], $config['license']);
        $data = $ios->getAccessToken();
        $this->assertIsArray($data);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('msg', $data);
    }

    /**
     * @return void
     * @throws GuzzleException
     * @throws cccdlException
     */
    public function testGetNonceTicket()
    {
        $config = Config::getConfig();
        $ios = new Ios($config['WBappid'], $config['secret'], $config['license']);
        $data = $ios->getNonceTicket('5');
        $this->assertIsArray($data);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('msg', $data);
    }

    /**
     * @return void
     * @throws GuzzleException
     * @throws cccdlException
     */
    public function testGetSignTicket()
    {
        $config = Config::getConfig();
        $ios = new Ios($config['WBappid'], $config['secret'], $config['license']);
        $data = $ios->getSignTicket();
        $this->assertIsArray($data);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('msg', $data);
    }

    /**
     * @return void
     * @throws GuzzleException
     * @throws cccdlException
     */
    public function testGetFaceId()
    {
        $config = Config::getConfig();
        $ios = new Ios($config['WBappid'], $config['secret'], $config['license']);

        $userId = '5';
        $orderNo = $userId . time();

        $signTicket = $ios->getSignTicket();

        $data = $ios->getFaceId($orderNo, [
            'appId' => $ios->appId,
            'orderNo' => $orderNo,
            'name' => '姓名',
            'idNo' => '身份证号',
            'userId' => $userId,
            'version' => $ios->version,
            'sign' => $ios->getSign($signTicket['tickets'][0]['value'], $userId),
            'nonce' => $ios->nonce,
        ]);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('msg', $data);
    }
}