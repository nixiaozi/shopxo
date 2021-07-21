<?php
namespace app\plugins\gold_coin\service;


use think\Db;
use app\plugins\gold_coin\service\GoldCoinBaseService;

class GoldExchangeService
{
    // 金币兑现状态
    public static $Excharge_status_list = [
        0 => ['value' => 0, 'name' => '审核中', 'checked' => true],
        2 => ['value' => 2, 'name' => '审核通过'],
        -1 => ['value' => -1, 'name' => '已取消'],
        -2 => ['value' => -2, 'name' => '审核拒绝'],
    ];

    // 通过你的对现金额获取你实际得到的金额
    public static function TheRealGetMoney($money)
    {
        $inputMoney = $money;
        $Digit = 0;
        
        while($inputMoney>=100)
        {
            $inputMoney=$inputMoney/10;
            $Digit++;
        }

        return $money - pow(10,$Digit);
    }


    public static function ExchangeCreate($params=[])
    {
        // 数据验证

        if(empty($params['verify']))
        {
            return DataReturn('图片验证码为空', -10);
        }

        $verify_params = array(
            'key_prefix' => 'user_login',
            'expire_time'   => MyC('common_verify_expire_time'),
        );
        $verify = new \base\Verify($verify_params);
        if(!$verify->CheckExpire())
        {
            return DataReturn('验证码已过期', -11);
        }
        if(!$verify->CheckCorrect($params['verify']))
        {
            return DataReturn('验证码错误', -12);
        }

        // 以上进行输入验证 以下添加新的内容
        
        // 开始处理
        $gold_coin = GoldCoinService::UserGoldCoin($params['user']['id']);
        $money = PriceNumberFormat($params['money']); // 获取要兑现的金额

        Db::startTrans();

        $data=[
            'cash_no'           => date('YmdHis').GetNumberCode(6),
            'user_id'           => $params['user']['id'],
            'gold_coin_id'      => $gold_coin['data']['id'],
            'gold_out'          => $money,
            'integral_out'      => $money,
            'money_in'          => $money,
            'money_real'        => self::TheRealGetMoney($money),
            'status'            => 0,
            'add_time'          =>time(),
        ];
        Db::name('PluginsGoldCoinMoney')->insert($data);

        // 提交事务
        Db::commit();
        return DataReturn('操作成功', 0);   


    }



}


?>