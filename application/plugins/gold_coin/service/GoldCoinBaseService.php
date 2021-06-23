<?php

namespace app\plugins\gold_coin\service;


use app\service\PluginsService;

class GoldCoinBaseService
{
    // 类型名称
    public static $business_type_name = "金币";

    public static function BaseConfig($is_cache = true)
    {
        $ret = PluginsService::PluginsData('gold_coin', '', $is_cache);
        if(!empty($ret['data']))
        {
            // 会员中心公告
            if(!empty($ret['data']['gold_user_center_notice']))
            {
                $ret['data']['gold_user_center_notice'] = explode("\n", $ret['data']['gold_user_center_notice']);
            }

        }
        return $ret;
    }

    public static function CoinAccountList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];




    }

}





