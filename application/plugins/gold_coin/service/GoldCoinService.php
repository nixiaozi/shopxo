<?php
namespace app\plugins\gold_coin\service;

use app\plugins\gold_coin\service\GoldCoinBaseService; 
use think\Db; 
use app\service\MessageService;


class GoldCoinService
{

    // 操作类型
    public static $operation_type_list = [
        0 => ['value' => 0, 'name' => '减少', 'checked' => true],
        1 => ['value' => 1, 'name' => '增加'],
    ];

    // 业务类型 —— 这个是否可以使用初始化方法从数据库中获得数据
    public static $business_type_list = [
        // 0 => ['value' => 0, 'name' => '系统', 'checked' => true],
        1 => ['value' => 1, 'name' => '兑现'],
        2 => ['value' => 2, 'name' => '获取-挖矿'],
        3 => ['value' => 3, 'name' => '获取-产品销售'],
        4 => ['value' => 3, 'name' => '获取-产品审核'],
    ]; 


    // 金币账户状态
    public static $gold_coin_status_list = [
        0 => ['value' => 0, 'name' => '正常', 'checked' => true],
        1 => ['value' => 1, 'name' => '异常'],
        2 => ['value' => 2, 'name' => '已注销'],
    ];

    public static function UserGoldCoin($user_id)
    {
        // 参数验证
        if(empty($user_id))
        {
            return  DataReturn('用户id有误', -1);
        }


        //  获取用户金币账户，不存在则创建
        $gold = Db::name("PluginsGoldCoin")->where(['user_id' => $user_id])->find();

        if(empty($gold))
        {
            $data = [
                'user_id'       => $user_id,
                'status'        => 0,
                'add_time'      => time(),
            ];

            $gold_coin_id = Db::name('PluginsGoldCoin')->insertGetId($data);

            if($gold_coin_id > 0)
            {
                return DataReturn('操作成功', 0, Db::name('PluginsGoldCoin')->find($gold_coin_id));
            } else {
                return DataReturn('金币账户添加失败', -100);
            }

        }else{
            return DataReturn('操作成功',0,$gold);
        }
    }

    // 用户钱包转态验证
    public static function UserGoldAccountStatusCheck($user_gold_account)
    {
        $gold_coin_error = '';
        if(isset($user_gold_account['status']))
        {
            if($user_gold_account['status'] != 0)
            {
                $gold_coin_error = array_key_exists($user_gold_account['status'], 
                    self::$gold_coin_status_list) ? 
                    '用户金币账户[ '.self::$gold_coin_status_list[$user_gold_account['status']]['name'].' ]' 
                    : '用户金币账户状态异常错误';
            }
        } else {
            $gold_coin_error = '用户金币账户异常错误';
        }

        if(!empty($gold_coin_error))
        {
            return DataReturn($gold_coin_error, -30);
        }
        return DataReturn('操作成功', 0, $user_gold_account);

    }

    // 添加金币账户日志 
    public static function GoldCoinLogInsert($params = [])
    {
        $data = [
            'user_id'           => isset($params['user_id']) ? intval($params['user_id']) : 0,
            'gold_coin_id'         => isset($params['gold_coin_id']) ? intval($params['gold_coin_id']) : 0,
            'business_type'     => isset($params['business_type']) ? intval($params['business_type']) : 0,
            'operation_type'    => isset($params['operation_type']) ? intval($params['operation_type']) : 0,
            'operation_coin'   => isset($params['operation_coin']) ? PriceNumberFormat($params['operation_coin']) : 0.00,
            'original_coin'    => isset($params['original_coin']) ? PriceNumberFormat($params['original_coin']) : 0.00,
            'latest_coin'      => isset($params['latest_coin']) ? PriceNumberFormat($params['latest_coin']) : 0.00,
            'msg'               => empty($params['msg']) ? '系统' : $params['msg'],
            'add_time'          => time(),
        ];
        return Db::name('PluginsGoldCoinLog')->insertGetId($data) > 0;
    }


    // 金币编辑
    // 这是一个后台直接编辑钱包状态的方法类，这个可以不用提供
    public static  function GoldCoinEdit($params = [])
    {
        // 这个是进行参数校验
        // 对于为整数的数字可以不同添加限制
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '金币账户id有误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'status',
                'checked_data'      => array_column(self::$gold_coin_status_list, 'value'),
                'error_msg'         => '金币账户状态有误',
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取金币账户
        $gold_coin_acc = Db::name("PluginsGoldCoin")->find(intval($params['id']));

        if(empty($gold_coin_acc))
        {
            return DataReturn('金币账户不存在或已删除', -10);
        }

        // 开启事务
        Db::startTrans();

        $data = [
            'status'        => intval($params['status']),
            'total_coin'  => empty($params['total_coin']) ? 0.00 : $params['total_coin'],
            'current_coin'  => empty($params['current_coin']) ? 0.00 : $params['current_coin'],
            'used_coin'    => empty($params['used_coin']) ? 0.00 : $params['used_coin'],
            'upd_time'      => time(),
        ];

        if(!Db::name('PluginsGoldCoin')->where(['id'=>$gold_coin_acc['id']])->update($data))
        {
            Db::rollback();
            return DataReturn('操作失败', -100);
        }

        // 插件基础配置
        $base = GoldCoinBaseService::BaseConfig(false);

        // 这里可以添加一些对于后台直接操作用户金币账户数据的方法！

        // 处理成功
        Db::commit();
        return DataReturn('操作成功', 0);

    }

    // 这里是用户修改自己金币账户的
    public  static function UserGoldCoinUpdate($user_id,$coin,$business_type,$msg_title = '金币账户变更')
    {
        // 获取用户金币账户
        $goldcoin = self::UserGoldCoin($user_id);
        if($goldcoin['code'] == 0)
        {
            // 金币账户存在才会执行以下操作
            Db::startTrans();

            // 金币数据
            $data = [
                'total_coin' => $goldcoin['data']['total_coin']+$coin,
                'current_coin' => $goldcoin['data']['current_coin']+$coin,
                'used_coin' => ($coin < 0)?$goldcoin['data']['used_coin']-$coin:$goldcoin['data']['used_coin'],
                'upd_time'  => time(),
            ];

            if(!Db::name('PluginsGoldCoin') ->
                where(['id'=>$goldcoin['data']['id']])->update($data))
            {
                Db::rollback();
                return DataReturn('金币账户操作失败', -100);
            }
            // 日志
            $log_data = [
                'user_id'           => $goldcoin['data']['user_id'],
                'gold_coin_id'         => $goldcoin['data']['id'],
                'business_type'     => $business_type,
                'operation_type'    => ($coin < 0)? 0 : 1,
                'operation_coin'   => $coin,
                'original_coin'    => $goldcoin['data']['current_coin'],
                'latest_coin'      => $data['current_coin'],
            ];

            $msg = ($log_data['operation_type'] == 1) ? '增加' : '减少';
            $log_data['msg'] = $msg_title.' [ 用户金币'.$msg.$log_data['operation_coin'].'个 ]';

            if(!self::GoldCoinLogInsert($log_data))
            {
                Db::rollback();
                return DataReturn('用户金币账户日志添加失败', -101);
            }

            // 消息通知
            MessageService::MessageAdd($goldcoin['data']['user_id'], '金币账户变更', 
                $log_data['msg'], GoldCoinBaseService::$business_type_name, 
                $goldcoin['data']['id']);

            
            // 处理成功
            Db::commit();
            return DataReturn('操作成功', 0);

        }

        return $goldcoin;

    }


}


?>