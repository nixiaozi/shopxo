<?php
namespace  app\plugins\gold_coin\service;

use app\admin\controller\Plugins;
use app\service\PluginsService;
use think\Db;
use think\db\Where;

class  GoldDigService
{
    public static $all_dig_func_key ="gold_coin_all_dig_func";

    public static $gold_dig_status_list =[
        0 => ['value' => 0, 'name' => '未挖矿', 'checked' => true],
        1 => ['value' => 1, 'name' => '已挖矿'],
        -1 =>['value' => -1, 'name' => '已过期'],
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
                    'get_coin'  => $dig_result['data']['coin_number']
                ];

                return $result;

            }
            

        }


    }

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
                'status' => $dig['status']
            ];
            return DataReturn((isset($dig['status']) && isset(self::$gold_dig_status_list[$dig['status']])) 
                                ? self::$gold_dig_status_list[$dig['status']]['name'] : '未知',
                    0,$result);

        }else{
            return DataReturn("找不到数据",0,null);
        }

    }


}



?>