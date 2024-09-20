<?php

namespace cccdl\tencent_cloud_face\Core\App;

use cccdl\tencent_cloud_face\Exception\cccdlException;
use cccdl\tencent_cloud_face\Traits\Request;
use GuzzleHttp\Exception\GuzzleException;

class AppBase
{
    use Request;


    /**
     * @var mixed
     */
    private $appId;
    /**
     * @var mixed
     */
    private $secret;
    /**
     * @var mixed
     */
    private $license;
    /**
     * @var string
     */
    private $nonce;

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
     * 根据长度生成随机字符串
     * @param $len
     * @return string
     */
    private static function generateRandomString($len)
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle($str), 0, $len);
    }
}