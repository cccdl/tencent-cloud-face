<?php

namespace cccdl\tencent_cloud_face\Test;

class Config
{
    public static function getConfig(): array
    {
        return [
            // 获取 WBappid
            // 文档：https://cloud.tencent.com/document/product/1007/49634
            'WBappid' => '请填写您的WBappid',

            // 获取 secret
            'secret' => '请填写您的secret',

            // 获取 license
            'license' => '请填写您的license',
        ];
    }
}