<?php

namespace app\plugins\gold_coin\service;



use app\service\PluginsService;
use think\Db;
use app\service\UserService;
use app\plugins\gold_coin\service\GoldExchangeService;
use app\plugins\gold_coin\service\GoldCoinService;

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

    // 获取金币账户列表
    public static function CoinAccountList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('PluginsGoldCoin')->field($field)->where($where)
        ->limit($m,$n)->order($order_by)->select();

        if(!empty($data))
        {
            $gold_coin_status_list = GoldCoinService::$gold_coin_status_list;
            foreach($data as &$v)
            {
                // 用户信息
                $v['user'] = UserService::GetUserViewInfo($v['user_id']);

                // 状态
                $v['status_name'] = (isset($v['status']) 
                    && isset($gold_coin_status_list[$v['status']])) 
                    ? $gold_coin_status_list[$v['status']]['name'] : '未知';
                
                // 时间 
                $v['add_time_time'] = empty($v['add_time']) ? '' : date('Y-m-d H:i:s', $v['add_time']);
                $v['upd_time_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);

    }


    // 金币账户总数
    public static function GoldCoinTotal($where = [])
    {
        return (int) Db::name('PluginsGoldCoin')->where($where)->count();
    }

    // 钱包条件
    public static function GoldCoinWhere($params = [])
    {
        $where = [];

        // 用户
        if(!empty($params['keywords']))
        {
            $user_ids = Db::name('User')->where('username|nickname|mobile|email', '=', $params['keywords'])->column('id');

            if(!empty($user_ids))
            {
                $where[] = ['user_id', 'in', $user_ids];
            }else{
                // 无数据条件，避免用户搜索条件没有数据造成的错觉
                $where[] = ['id', '=', 0];
            }
        }


        // 状态
        if(isset($params['status']) && $params['status'] > -1)
        {
            $where[] = ['status', '=', $params['status']];
        }

        return $where;
    }

    // 兑换列表 
    public static function ExchangeList($params= [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];
        $user_type = isset($params['user_type']) ? $params['user_type'] : '';

        // 获取数据列表
        $data = Db::name('PluginsGoldCoinMoney')->field($field)
        ->where($where)->limit($m, $n)->order($order_by)->select();

        if(!empty($data))
        {
            
            $common_gender_list = lang('common_gender_list');
            foreach($data as &$v)
            {
                
                // 用户信息
                $v['user'] = ($user_type == 'user') ? null : UserService::GetUserViewInfo($v['user_id']);

                // 兑换状态
                $v['status_name'] = isset($v['status']) ? 
                GoldExchangeService::$Excharge_status_list[$v['status']]['name'] : '';
            
                // 创建时间
                $v['add_time_time'] = empty($v['add_time']) ? '' : date('Y-m-d H:i:s', $v['add_time']);
            
                // 更新时间
                $v['upd_time_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);

    }



    // 兑换列表总数
    public static function ExchangeTotal($where = [])
    {
        return (int) Db::name('PluginsGoldCoinMoney')->where($where)->count();
    }


    //  兑换列表条件
    public static function ExchangeWhere($params = [])
    {
        $where = [];

        // 用户id
        if(!empty($params['user']))
        {
            $where[] = ['user_id', '=', $params['user']['id']];
        }

        // 关键字根据用户筛选
        if(!empty($params['keywords']))
        {
            if(empty($params['user']))
            {
                $user_ids = Db::name('User')->where('username|nickname|mobile|email', '=', $params['keywords'])->column('id');
                if(!empty($user_ids))
                {
                    $where[] = ['user_id', 'in', $user_ids];
                } else {
                    // 无数据条件，走单号条件
                    $where[] = ['cash_no', 'like', '%'.$params['keywords'].'%'];
                }
            }
        }

        // id
        if(!empty($params['id']))
        {
            $where[] = ['id', '=', intval($params['id'])];
        }
        // 订单号
        if(!empty($params[' ']))
        {
            $where[] = ['cash_no', '=', trim($params['orderno'])];
        }

        // 状态
        if(isset($params['status']) && $params['status'] > -1)
        {
            $where[] = ['status', '=', $params['status']];
        }

        return $where;

    }


    // 用户金币账户日志明细列表
    public static function GoldCoinLogList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('PluginsGoldCoinLog')->field($field)->where($where)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            $business_type_list = GoldCoinService::$business_type_list;
            $operation_type_list = GoldCoinService::$operation_type_list;

            foreach($data as &$v)
            {
                // 用户信息
                $v['user'] = UserService::GetUserViewInfo($v['user_id']);
                
                // 业务类型
                $v['business_type_name'] = (isset($v['business_type']) && isset($business_type_list[$v['business_type']])) ? $business_type_list[$v['business_type']]['name'] : '未知';

                // 操作类型
                $v['operation_type_name'] = (isset($v['operation_type']) && isset($operation_type_list[$v['operation_type']])) ? $operation_type_list[$v['operation_type']]['name'] : '未知';

                // 操作原因
                $v['msg'] = empty($v['msg']) ? '' : str_replace("\n", '<br />', $v['msg']);

                // 创建时间
                $v['add_time_time'] = empty($v['add_time']) ? '' : date('Y-m-d H:i:s', $v['add_time']);
            }

        }
        
        return DataReturn('处理成功', 0, $data);

    }


    // 金币日志总数
    public static  function GoldCoinLogTotal($where = [])
    {
        return (int) Db::name('PluginsGoldCoinLog')->where($where)->count();
    }


    // 金币日志明细条件
    public static function GoldCoinLogWhere($params = [])
    {
        $where = [];

        // id
        if(!empty($params['id']))
        {
            $where[] = ['id', '=', intval($params['id'])];
        }

        // 用户id
        if(!empty($params['user']))
        {
            $where[] = ['user_id', '=', $params['user']['id']];
        }

        // 用户
        if(!empty($params['keywords']))
        {
            $user_ids = Db::name('User')->where('username|nickname|mobile|email', '=', $params['keywords'])->column('id');
            if(!empty($user_ids))
            {
                $where[] = ['user_id', 'in', $user_ids];
            } else {
                // 无数据条件，避免用户搜索条件没有数据造成的错觉
                $where[] = ['id', '=', 0];
            }
        }
        
        // 业务类型
        if(isset($params['business_type']) && $params['business_type'] > -1)
        {
            $where[] = ['business_type', '=', $params['business_type']];
        }

        // 操作类型
        if(isset($params['operation_type']) && $params['operation_type'] > -1)
        {
            $where[] = ['operation_type', '=', $params['operation_type']];
        }

        
        return $where;
    }


    // 挖矿列表
    public static function GoldDigList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('PluginsGoldCoinDig')->field($field)->where($where)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            $dig_status_list = GoldDigService::$gold_dig_status_list;
            foreach($data as &$v)
            {
                // 用户信息
                $v['user'] = UserService::GetUserViewInfo($v['user_id']);

                // 状态
                $v['status_name'] = (isset($v['status']) && isset($dig_status_list[$v['status']])) ? $dig_status_list[$v['status']]['name'] : '未知';

                // 时间
                $v['add_time_time'] = empty($v['add_time']) ? '' : date('Y-m-d H:i:s', $v['add_time']);
                $v['dig_time_time'] = empty($v['dig_time']) ? '无' : date('Y-m-d H:i:s', $v['dig_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    // 获取用户挖矿的记录
    public static function GoldDigTotal($where = [])
    {
        return (int) Db::name('PluginsGoldCoinDig')->where($where)->count();
    }

    // 用户挖矿记录的筛选条件
    public static function GoldDigWhere($params = [])
    {
        $where = [];

        // 用户id
        if(!empty($params['user']))
        {
            $where[] = ['user_id', '=', $params['user']['id']];
        }

        // id 挖矿编号
        if(!empty($params['id']))
        {
            $where[] = ['id', '=', intval($params['id'])];
        }
        

        // 用户
        if(!empty($params['keywords']))
        {
            $user_ids = Db::name('User')->where('username|nickname|mobile|email', '=', $params['keywords'])->column('id');
            if(!empty($user_ids))
            {
                $where[] = ['user_id', 'in', $user_ids];
            } else {
                // 无数据条件，避免用户搜索条件没有数据造成的错觉
                $where[] = ['id', '=', $params['keywords']];
            }
        }

        // 状态
        if(isset($params['status']) && $params['status'] > -1)
        {
            $where[] = ['status', '=', $params['status']];
        }

        return $where;
    }

}





