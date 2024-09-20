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
        $this->client = new Client([
            'timeout' => self::$timeOut,
        ]);

        $response = $this->client->get($url);

        if ($response->getStatusCode() != 200) {
            throw new cccdlException('请求失败: ' . $response->getStatusCode());
        }

        return json_decode($response->getBody(), true);
    }

    /**
     * @throws GuzzleException
     * @throws cccdlException
     */
    public function post($url, $param)
    {
        $this->client = new Client([
            'timeout' => self::$timeOut,
        ]);

        $response = $this->client->post($url, ['json' => $param]);

        if ($response->getStatusCode() != 200) {
            throw new cccdlException('请求失败: ' . $response->getStatusCode());
        }

        return json_decode($response->getBody(), true);
    }

}