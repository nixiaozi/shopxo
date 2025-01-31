<?php

namespace app\plugins\gold_coin\api;

use app\plugins\gold_coin\api\Common;
use app\plugins\gold_coin\service\GoldCoinBaseService;
use app\plugins\gold_coin\service\GoldDigService;

class Dig extends Common
{

    public function __construct()
    {
        parent::__construct();

        // 是否登录
        $this->IsLogin();
    }

    // 插件默认的挖矿处理方法
    

    // 执行挖金币的验证方法
    public function DigCoin($params = [])
    {   
        // 这里需要添加一个修改挖矿的内容，并且添加日志
        $theDig = GoldDigService::TheCurrentDig($params['dig_id']);
        // $theUser = UserService::UserInfo('id',$theDig['user_id']);
        if($theDig!= null)
        {
            // 需要添加一个可执行的url的方法
            $url = PluginsHomeUrl('gold_coin', 'Dig', 'dodig',$params); // 需要加入参数
            $result =['url'=>$url];
            return $result;

        }else{
            throw new \Exception("无效的挖矿条目！");
        }



    }

    // 用于生成挖矿数据的初始化 
    public function ToDig($params = [])
    {
        // 参数
        $params = $this->data_post;
        $params['user'] = $this->user;
        $params['user_type'] = 'user';

        // 验证是否已经达到今日挖矿限制
        $config = GoldCoinBaseService::BaseConfig();
        $can_dig_timer =array_key_exists('gold_digtime_perday',$config['data'])!=null
            ?$config['data']['gold_digtime_perday']:3;

        if($can_dig_timer<=GoldDigService::UserCurrentDayDigNumber($params['user']['id'])){
            return DataReturn("已达到当日挖矿最大限制".$can_dig_timer."次",-1);
        }



        // 添加一个挖矿条目
        $result = GoldDigService::UserDigInit($params['user']['id']);
        


        // 测试如何如何获取配置信息 test code 
        // $result = MyC("gold_coin_all_dig_func"); // 获取全局配置

        // 测试如何获取配置信息

        // 最后需要返回一个要跳转的url 以及生成的挖矿条目ID
        return DataReturn("完成挖矿",0,$result);

    }


    // 用于挖矿回调用来对产品进行用于挖矿数据条目的异步更新进行前端同步
    public function DigStatu($params = [])
    {

        return GoldDigService::TheCurrentDigStatu($params['id']);
    }


    public function  DigCoinNotify($params = [])
    {
        return GoldDigService::DoReceiveNotify($params['dig_id'],$params['check_code'],$params['dig_coin']);
    }

}
