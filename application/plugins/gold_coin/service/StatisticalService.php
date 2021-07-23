<?php
namespace app\plugins\gold_coin\service;

use think\Db;

class StatisticalService
{
    // 昨天日期
    private static $yesterday_time_start;
    private static $yesterday_time_end;

    // 今天日期
    private static $today_time_start;
    private static $today_time_end;

    public static function Init($params = [])
    {
        static $object = null;
        if(!is_object($object))
        {
            // 初始化标记对象，避免重复初始化
            $object = (object) [];

            // 昨天日期
            self::$yesterday_time_start = strtotime(date('Y-m-d 00:00:00', strtotime('-1 day')));
            self::$yesterday_time_end = strtotime(date('Y-m-d 23:59:59', strtotime('-1 day')));

            // 今天日期
            self::$today_time_start = strtotime(date('Y-m-d 00:00:00'));
            self::$today_time_end = time();
        }
    }

    public static function YesterdayTodayTotal($params = [])
    {
        // 扩展数据
        $ext_count = 0;

        // 操作类型
        if(!empty($params['type']))
        {
            switch($params['type'])
            {
                // 金币账户
                case 'goldcoin':
                    $table ='PluginsGoldCoin';

                    // 扩展数据
                    $ext_count = Db::name('User')->count(); // 这里就是表示用户数量与金币账户数量相同
                    break;
                //挖矿详细
                case 'goldcoindig':
                    $table = 'PluginsGoldCoinDig';
                    $ext_count = Db::name($table)->where(['status'=>0]) ->count();
                    break;
                // 兑现详细
                case 'goldcoinexchange':
                    $table = 'PluginsGoldCoinMoney';
                    $ext_count = Db::name($table)->where(['status'=>0]) ->count();
                    break;
                // 挖矿日志
                case 'goldcoinlog':
                    $table = 'PluginsGoldCoinLog';
                    break;

            }

        }

        // 总数
        $total_count = Db::name($table)->count();

        // 昨天
        $where = [
            ['add_time', '>=', self::$yesterday_time_start],
            ['add_time', '<=', self::$yesterday_time_end],
        ];
        $yesterday_count = Db::name($table)->where($where)->count();

        // 今天
        $where = [
            ['add_time', '>=', self::$today_time_start],
            ['add_time', '<=', self::$today_time_end],
        ];
        $today_count = Db::name($table)->where($where)->count();

        // 数据组装
        $result = [
            'total_count'       => $total_count,
            'yesterday_count'   => $yesterday_count,
            'today_count'       => $today_count,
            'ext_count'         => $ext_count,
        ];
        return DataReturn('处理成功', 0, $result);

    }


    public static function StatisticalData($params = [])
    {
        // 初始化
        self::Init($params);

        // 统计数据初始化
        $result = [
            'goldcoin' => [
                'title'             => '金币账户总数',
                'count'             => 0,
                'yesterday_count'   => 0,
                'today_count'       => 0,
                'right_count'       => 0,
                'right_title'       => '用户',
                'url'               => PluginsAdminUrl('gold_coin', 'goldcoin', 'index'),
            ],
            'goldcoindig' => [
                'title'             => '挖矿总数',
                'count'             => 0,
                'yesterday_count'   => 0,
                'today_count'       => 0,
                'right_count'       => 0,
                'right_title'       => '未挖矿',
                'url'               => PluginsAdminUrl('gold_coin', 'goldcoindig', 'index'),
            ],
            'goldcoinexchange' => [
                'title'             => '兑现总数',
                'count'             => 0,
                'yesterday_count'   => 0,
                'today_count'       => 0,
                'right_count'       => 0,
                'right_title'       => '待支付',
                'url'               => PluginsAdminUrl('gold_coin', 'goldcoinexchange', 'index'),
            ],
            'goldcoinlog' => [
                'title'             => '挖矿日志明细',
                'count'             => 0,
                'yesterday_count'   => 0,
                'today_count'       => 0,
                'url'               => PluginsAdminUrl('gold_coin', 'goldcoinlog', 'index'),
            ],
        ];
        $type_all = ['goldcoin', 'goldcoindig', 'goldcoinexchange', 'goldcoinlog'];
        foreach($type_all as $type)
        {
            $ret = self::YesterdayTodayTotal(['type'=>$type]);
            if($ret['code'] == 0)
            {
                $result[$type]['count'] = $ret['data']['total_count'];
                $result[$type]['yesterday_count'] = $ret['data']['yesterday_count'];
                $result[$type]['today_count'] = $ret['data']['today_count'];
                if(isset($result[$type]['right_count']) && isset($ret['data']['ext_count']))
                {
                    $result[$type]['right_count'] = $ret['data']['ext_count'];
                }
            }
        }
        return $result;

    }

}
?>