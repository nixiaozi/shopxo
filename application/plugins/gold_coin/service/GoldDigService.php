<?php
namespace  app\plugins\gold_coin\service;



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
            // 这里需要直接添加一个采矿记录
            $config = GoldCoinBaseService::BaseConfig();




        }


    }


}



?>