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

        


    }



}
