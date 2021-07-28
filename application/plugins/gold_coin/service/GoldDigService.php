<?php
namespace  app\plugins\gold_coin\service;


use app\service\PluginsService;
use think\Db;

class  GoldDigService
{
    public static $all_dig_func_key ="gold_coin_all_dig_func";

    public static $gold_dig_status_list =[
        0 => ['value' => 0, 'name' => '未挖矿', 'checked' => true],
        1 => ['value' => 1, 'name' => '已挖矿'],
        11 =>['value' => 11, 'name' => '已过期'],
        12 =>['value' => 12, 'name' => '已取消'],
    ];

    
    // 这里添加可用的挖矿方法
    public static function AddDigMethod($methodPlugin,$methodControll,$methodAction,$title)
    {
        // s

    }

    //  这里删除可用的挖矿方法
    public static function DelDigMethod($methodPlugin,$methodControll,$methodAction)
    {


    }

    // 插入可用的方法到当前使用的挖矿方法中
    public static function InsertDigMethod($methodPlugin,$methodControll,$methodAction)
    {
        
    }

    // 

    // 获取所有的挖矿方法
    public static function  GetAllDigMethod()
    {
        
    }

    // 获取可用的挖矿方法
    public static function  GetCurrentDigMethod()
    {
        
    }

    // 初始化添加挖矿条目
    public static function  UserDigInit($user_id)
    {
        $goldcoin = GoldCoinService::UserGoldCoin($user_id);
        if($goldcoin['code'] == 0)
        {
            // 这里需要直接添加一个采矿记录 ,并且
            $config = GoldCoinBaseService::BaseConfig();
            $the_dig_method = $config['data']['gold_got_methods'];
            // 获取此条目挖矿的url,这个最好通过执行第三方插件方法来或者，这个链接可以出现外部的url
            $selected_dig_method = $the_dig_method[array_rand($the_dig_method)];
            // $dig_url ='';
            $check_code =RandomString(18);
            

            $data = [
                'user_id' =>$user_id,
                'gold_coin_id' =>$goldcoin['data']['id'],
                'dig_url' => '',
                'dig_gold' => 0,
                'status' => 0,
                'check_code' =>$check_code,
                'add_time' => time(),
            ];
            
            $id = Db::name('PluginsGoldCoinDig') ->insertGetId($data);

            // 需要把新来的 id 带在需要加入的url中

            if($id==0)
            {
                throw new \Exception("插入挖矿条目失败！");
            }else{
                $params =[
                    'check_code' => $check_code,
                    'dig_id' => $id,
                ];
                $dig_result = PluginsService::PluginsControlCall($selected_dig_method['pluginsName'],$selected_dig_method['controllerName'],
                            $selected_dig_method['actionName'],'api',$params);

                $updateData=[
                    'dig_url' =>$dig_result['data']['url'],
                ];

                Db::name('PluginsGoldCoinDig')->where(['id'=>$id])->update($updateData);
                
                // test
                // $test = Db::getLastSql();

                $result=[
                    'dig_url'   => $dig_result['data']['url'],
                    'dig_id'    => $id,
                    // 'get_coin'  => $dig_result['data']['coin_number']
                ];

                return $result;

            }
            

        }


    }

    // 获取当前的挖矿条目
    public static function TheCurrentDig($dig_id)
    {
        return Db::name('PluginsGoldCoinDig')->where(['id'=>$dig_id])->find();

    }

    // 这个只是查看这个挖矿条目的状态
    public static function TheCurrentDigStatu($dig_id)
    {
        $dig = self::TheCurrentDig($dig_id);
        if($dig!=null){
            $result =[
                'status' => $dig['status'],
                'dig_gold' => $dig['dig_gold'],
            ];
            return DataReturn((isset($dig['status']) && isset(self::$gold_dig_status_list[$dig['status']])) 
                                ? self::$gold_dig_status_list[$dig['status']]['name'] : '未知',
                    0,$result);

        }else{
            return DataReturn("找不到数据",0,null);
        }

    }

    public static function DeletedGoldCoin($dig_id)
    {
        // 更新条目信息
        $digData=[
            'status' => 12,
            'msg' => '用户取消了挖矿操作'.'[用户没能获得金币]',
        ];

        !Db::name('PluginsGoldCoinDig')
            ->where(['id'=>$dig_id])->update($digData);
    }

    public static function UserCurrentDayDigNumber($user_id)
    {
        $today_time_start = strtotime(date('Y-m-d 00:00:00'));
        $today_time_end = time();

        $where[] =[
            ['user_id','=', $user_id],
            ['add_time', '>=', $today_time_start],
            ['add_time', '<=', $today_time_end],
        ];

        return Db::name('PluginsGoldCoinDig')->where($where)->count();
    }


    // 用来进行挖矿获取金币的操作,并且返回获取的金币数
    public static function MethodOfDigCoin()
    {
        // 
        $getCoin = 0;
        $randomInt = rand(0,100);
        if($randomInt < 21){
            $getCoin = 1;
        }else if($randomInt < 55){
            $getCoin = 2;
        }else if($randomInt < 83){
            $getCoin = 3;
        }else if($randomInt < 98){
            $getCoin = 6;
        }

        return  $getCoin;
    }

    // 接收到系统通知的处理方式
    public static function  DoReceiveNotify($dig_id,$dig_checkCode,$dig_coin)
    {
        // 这里添加
        $theDig = GoldDigService::TheCurrentDig($dig_id);
        if($theDig!= null)
        {
            if($theDig['status']==0 && $theDig['check_code']==$dig_checkCode)
            {
                // 未挖矿时才进行挖矿操作
                Db::startTrans();
                $theoldDig= GoldDigService::TheCurrentDig($dig_id);
                if($theoldDig['status']!=$theDig['status'])
                {
                    Db::rollback();
                    return DataReturn('挖矿成功',0,null);
                }

                $ret = GoldCoinService::UserGoldCoinUpdate($theDig['user_id'],$dig_coin,2);

                if($ret['data']!=0){
                    Db::rollback();
                    // throw new \Exception("挖矿失败！");
                    return DataReturn('挖矿失败',-1,null);
                }

                // 更新条目信息
                $digData=[
                    'dig_gold' => $dig_coin,
                    'status' => 1,
                    'msg' => '用户获取金币'.'[挖矿方法:挖矿]'.'[用户获得金币'.$dig_coin.'个]',
                    'dig_time' =>time(),
                ];

                if(!Db::name('PluginsGoldCoinDig')
                    ->where(['id'=>$dig_id])->update($digData))
                {
                    Db::rollback();
                    // throw new \Exception("挖矿失败！");
                    return DataReturn('挖矿失败',-1,null);
                }

                // 处理成功     
                Db::commit();
                return DataReturn('挖矿成功',0,null);
            }else{
                // 表示挖矿已完成，直接返回成功就好了
                return DataReturn('挖矿成功',0,null);
            }

        }else{
            // throw new \Exception("无效的挖矿条目！");
            return DataReturn('挖矿失败',-1,null);
        }

    }

}



?>