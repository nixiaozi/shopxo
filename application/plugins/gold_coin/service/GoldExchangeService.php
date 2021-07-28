<?php
namespace app\plugins\gold_coin\service;


use think\Db;
use app\plugins\gold_coin\service\GoldCoinService;
use app\plugins\wallet\service\WalletService;
use app\service\IntegralService;
use app\service\MessageService;
use base\Uploader;
use think\db\Where;

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

    public static function ExchangeAudit($params=[])
    {
        // 参数验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '提现id有误',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'msg',
                'checked_data'      => '180',
                'error_msg'         => '备注最多 180 个字符',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'type',
                'checked_data'      => ['agree', 'refuse'],
                'error_msg'         => '操作类型有误，同意或拒绝操作出错',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取要兑现的数据
        $exchange = Db::name('PluginsGoldCoinMoney')->find(intval($params['id']));
        if(empty($exchange))
        {
            return DataReturn('兑换数据不存在或已删除', -10);
        }

        // 状态
        if($exchange['status'] != 0)
        {
            return DataReturn('状态不可操作['.self::$Excharge_status_list[$exchange['status']]['name'].']', -11);
        }

        // 兑换金额处理
        $exchange_money =PriceNumberFormat($exchange['money_real']); 
        // 用户金币
        $user_goldcoin = GoldCoinService::UserGoldCoin($exchange['user_id'])['data']['current_coin'];
        // 用户积分
        $user_integral = IntegralService::UserIntegral($exchange['user_id'])['integral'];

        if( $exchange_money <= 0.00 || $exchange_money > $user_goldcoin 
            || $exchange_money > $user_integral)
        {
            return DataReturn('打款金额有误,已经超出了可兑现范围', -12);
        }


        // 获取用户钱包
        $wallet = WalletService::UserWallet($exchange['user_id']);
        if(empty($wallet))
        {
            return DataReturn('用户钱包不存在或已删除', -20);
        }

        
        // 是否发送消息
        $is_send_message = (isset($params['is_send_message']) && $params['is_send_message'] == 1) ? 1 : 0;

        // 开始处理
        Db::startTrans();


        // 需要修改的提现信息更新
        $exchange_data = [
            'status'    => $params['type'] == 'agree'?2:-2,
            'msg'       => empty($params['msg']) ?
                ($params['type'] == 'agree'?'恭喜你，你的兑现申请已通过':'对不起，你的兑现申请被拒绝' ): $params['msg'],
            'upd_time'  => time()
        ];

        DB::name('PluginsGoldCoinMoney')->where(['id' =>$exchange['id']])->update($exchange_data);

        // 需要修改减少用户积分，添加用户积分日志
        $user_integral = Db::name('User')->where(['id'=>$exchange['user_id']])->value('integral');
        if($user_integral<$exchange['integral_out'] || 
            !Db::name('User')->where(['id'=>$exchange['user_id']])->setDec('integral', $exchange['integral_out']))
        {
            Db::rollback();
            return DataReturn('用户积分抵扣失败,检查积分是否剩余足够', -10);
        }

        if(!IntegralService::UserIntegralLogAdd($exchange['user_id'],$user_integral,$exchange['integral_out'],'用户兑现抵扣积分'))
        {
            // 如果为False 就表示添加不成功
            Db::rollback();
            return DataReturn('用户积分抵扣失败,请检查后重试', -10);
        }

        // 需要减少用户金币， 并添加用户金币日志
        $goldcoin = GoldCoinService::UserGoldCoin($exchange['user_id']);
        $coindata = [
            'current_coin' => $goldcoin['data']['current_coin']- $exchange['gold_out'],
            'used_coin' => $goldcoin['data']['used_coin']+ $exchange['gold_out'],
            'upd_time'  => time(),
        ];
        if(!Db::name('PluginsGoldCoin') ->
            where(['id'=>$goldcoin['data']['id']])->update($coindata))
        {
            Db::rollback();
            return DataReturn('金币账户操作失败', -100);
        }

        $coinlog_data = [
            'user_id'           => $goldcoin['data']['user_id'],
            'gold_coin_id'         => $goldcoin['data']['id'],
            'business_type'     => 1,
            'operation_type'    => 0,
            'operation_coin'   => $exchange['gold_out'],
            'original_coin'    => $goldcoin['data']['current_coin'],
            'latest_coin'      => $coindata['current_coin'],
            'msg'  => '金币账户兑现'.' [ 用户金币减少'.$exchange['gold_out'].'个 ]'
        ];
        if(!GoldCoinService::GoldCoinLogInsert($coinlog_data))
        {
            Db::rollback();
            return DataReturn('用户金币账户日志添加失败', -101);
        }

        // 需要增加用户钱包余额，并且添加 用户钱包日志
        $wallet = Db::name('PluginsWallet')->where(['user_id' =>$exchange['user_id'] ])->find();
        $userwalletdata = [
            'normal_money'      => $wallet['normal_money'] + $exchange['money_real'],
        ];

        if(!Db::name('PluginsWallet')->where(['user_id' =>$exchange['user_id']])->update($userwalletdata))
        {
            Db::rollback();
            return DataReturn('操作失败', -100);
        }

        // 添加日志
        $walletlog_data = [
            'user_id'           => $wallet['user_id'],
            'wallet_id'         => $wallet['id'],
            'business_type'     => 0,
            'operation_type'    => 1,
            'money_type'        => 0,
            'operation_money'   => PriceNumberFormat($exchange['money_real']),
            'original_money'    => $wallet['normal_money'],
            'latest_money'      => $userwalletdata['normal_money'],
        ];
        $walletlog_data['msg'] = '金币兑现钱包余额'.' [ 增加金额'.$walletlog_data['operation_money'].'元 ]';
        if(!WalletService::WalletLogInsert($walletlog_data))
        {
            Db::rollback();
            return DataReturn('钱包日志添加失败', -101);
        }

        
        // 消息通知
        if($is_send_message == 1)
        {
            MessageService::MessageAdd($exchange['user_id'], '金币兑现审核', $exchange_data['msg'], GoldCoinBaseService::$business_type_name, 1);
        }


        // 处理成功
        Db::commit();
        return DataReturn('操作成功', 0);



    }

    public static function UserCheckExchangeNum($user_id)
    {
        $where[] =[
            ['user_id','=', $user_id],
            ['status', '>=', 0],
        ];

        return Db::name('PluginsGoldCoinMoney')->where($where)->count();
    }

}


?>