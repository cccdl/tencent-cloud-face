<?php

namespace cccdl\tencent_cloud_face\Traits;


use cccdl\tencent_cloud_face\Exception\cccdlException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

trait Request
{
    protected static $timeOut = 3;

    /**
     * @throws GuzzleException
     * @throws cccdlException
     */
    public function get($url)
    {
        $client = new Client([
            'timeout' => self::$timeOut,
        ]);

        $response = $client->get($url);

        if ($response->getStatusCode() != 200) {
            throw new cccdlException('请求失败: ' . $response->getStatusCode());
        }

        $arr = json_decode($response->getBody(), true);

        if (!isset($arr['code']) || $arr['code'] != 0) {
            throw new cccdlException('请求结果异常' . $response->getBody());
        }

        return $arr;
    }

}