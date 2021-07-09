<?php

namespace app\plugins\gold_coin\api;

use app\plugins\gold_coin\api\Common;


class Dig extends Common
{
    public function __construct()
    {
        parent::__construct();

        // 是否登录
        $this->IsLogin();
    }


    // 用于生成挖矿序列号 
    public function ToDig($params = [])
    {
        // 参数
        $params = $this->data_post;
        $params['user'] = $this->user;
        $params['user_type'] = 'user';

        // 添加一个挖矿条目
        
        // 测试如何如何获取配置信息 test code 
        $result = MyC("gold_coin_all_dig_func"); // 获取全局配置

        // 测试如何获取配置信息

        return DataReturn('success', 0, ["dsf","gds"]);

    }



}
