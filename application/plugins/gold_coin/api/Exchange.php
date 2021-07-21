<?php

namespace app\plugins\gold_coin\api;

use app\plugins\gold_coin\api\Common;
use app\plugins\gold_coin\service\GoldExchangeService;


class Exchange extends Common
{
    public function __construct()
    {
        parent::__construct();

        // 是否登录
        $this->IsLogin();
    }

    // 这里通过想要提现的金额获取最终真正得到的金额
    // 获取数的当前位数n，然后按照最小的n-1位数作为手续费
    public function TheRealGetMoney($params=[])
    {
        $inputMoney = $params['id'];
        $result = GoldExchangeService::TheRealGetMoney($inputMoney);

        return DataReturn("实际金额",0,$result);
    }

}
?>