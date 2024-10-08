<?php

namespace cccdl\tencent_cloud_face\Core;

use cccdl\tencent_cloud_face\Exception\cccdlException;
use cccdl\tencent_cloud_face\Traits\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class App
{
    use Request;


    /**
     * @var string
     */
    public string $appId;
    /**
     * @var string
     */
    public string $secret;
    /**
     * @var string
     */
    public string $license;
    /**
     * @var string
     */
    public string $nonce;
    public $accessToken = null;
    public $version = '1.0.0';
    private Client $client;

    public function __construct($appid, $secret, $license)

    {
        $this->appId = $appid;
        $this->secret = $secret;
        $this->license = $license;
        $this->nonce = $this->generateRandomString(32);
    }


    /**
     * 获取 Access Token
     * 文档地址：https://cloud.tencent.com/document/product/1007/37304
     * @return array
     * @throws GuzzleException
     * @throws cccdlException
     */
    public function getAccessToken(): array
    {
        //请求access_token
        $url = sprintf(
            'https://kyc1.qcloud.com/api/oauth2/access_token?appId=%s&secret=%s&grant_type=client_credential&version=1.0.0',
            $this->appId,
            $this->secret
        );

        return $this->get($url);
    }

    /**
     * 获取Nonce Ticket
     * @param $userId
     * @return array
     * @throws GuzzleException
     * @throws cccdlException
     */
    public function getNonceTicket($userId): array
    {
        if ($this->accessToken == null) {
            $this->accessToken = $this->getAccessToken()['access_token'];
        }
        //请求NONCE
        $url = sprintf(
            'https://kyc1.qcloud.com/api/oauth2/api_ticket?appId=%s&access_token=%s&type=NONCE&version=1.0.0&user_id=%d',
            $this->appId,
            $this->accessToken,
            $userId
        );

        return $this->get($url);
    }


    /**
     * 获取SIGN Ticket
     * @return mixed
     * @throws GuzzleException
     * @throws cccdlException
     */
    public function getSignTicket()
    {
        if ($this->accessToken == null) {
            $this->accessToken = $this->getAccessToken()['access_token'];
        }

        //请求NONCE
        $url = sprintf(
            'https://kyc1.qcloud.com/api/oauth2/api_ticket?appId=%s&access_token=%s&type=SIGN&version=1.0.0',
            $this->appId,
            $this->accessToken,
        );

        return $this->get($url);
    }

    /**
     * 获取FaceId
     * @return mixed
     * @throws GuzzleException
     * @throws cccdlException
     */
    public function getFaceId($param)
    {
        //请求NONCE
        $url = sprintf(
            'https://kyc1.qcloud.com/api/server/getfaceid?orderNo=%s',
            $param['orderNo'],
        );

        $signTicket = $this->getSignTicket();
        $data[] = $signTicket['tickets'][0]['value'];
        $data[] = $param['userId'];
        $param['sign'] = $this->getSign($data);
        $param['appId'] = $this->appId;
        $param['version'] = $this->version;
        $param['nonce'] = $this->nonce;

        return $this->post($url, $param);
    }

    /**
     * 获取FaceId
     * @return mixed
     * @throws GuzzleException
     * @throws cccdlException
     */
    public function queryfacerecord($param)
    {
        //请求NONCE
        $url = sprintf(
            'https://kyc1.qcloud.com/api/v2/base/queryfacerecord?orderNo=%s',
            $param['orderNo'],
        );

        $signTicket = $this->getSignTicket();

        $data[] = $signTicket['tickets'][0]['value'];
        $data[] = $param['orderNo'];

        $param['sign'] = $this->getSign($data);
        $param['appId'] = $this->appId;
        $param['version'] = $this->version;
        $param['nonce'] = $this->nonce;

        return $this->post($url, $param);
    }

    /**
     * 根据长度生成随机字符串
     * @param $len
     * @return string
     */
    private static function generateRandomString($len)
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle($str), 0, $len);
    }

    /**
     * @param $data
     * @return string
     */
    public function getSign($data): string
    {
        $data[] = $this->nonce;
        $data[] = $this->appId;
        $data[] = $this->version;

        //将 appId、userId、version 连同 ticket、nonce 共五个参数的值进行字典序排序
        sort($data, SORT_STRING);

        //拼接字符串
        $str = implode('', $data);

        return sha1($str);

    }


}