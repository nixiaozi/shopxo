<?php
namespace app\plugins\gold_coin\service;


use think\Db;
use app\plugins\gold_coin\service\GoldCoinBaseService;

class GoldExchangeService
{
    // 金币兑现状态
    public static $Excharge_status_list = [
        0 => ['value' => 0, 'name' => '未提交', 'checked' => true],
        1 => ['value' => 1, 'name' => '审核中'],
        2 => ['value' => 2, 'name' => '审核通过'],
        -1 => ['value' => -1, 'name' => '已取消'],
        -2 => ['value' => -2, 'name' => '审核拒绝'],
    ];

    

}


?>