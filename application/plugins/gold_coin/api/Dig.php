<?php

namespace app\plugins\gold_coin\api;

use app\plugins\gold_coin\api\Common;
use app\plugins\gold_coin\service\GoldCoinService;
use app\plugins\gold_coin\service\GoldDigService;
use app\service\UserService;
use think\Db;

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
        $theUser = UserService::UserInfo('id',$theDig['user_id']);
        if($theDig!= null)
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


            Db::startTrans(); // 可以使用多重的事务
            // 首先更新用户账户信息
            $ret = GoldCoinService::UserGoldCoinUpdate($theDig['user_id'],$getCoin,2); // 这个业务是挖矿的

            if($ret['data']!=0){
                Db::rollback();
                throw new \Exception("挖矿失败");
            }

            // 更新条目信息
            $digData=[
                'dig_gold' => $getCoin,
                'status' => 1,
                'msg' => '用户获取金币'.'[挖矿方法:挖矿]'.'[用户获得金币'.$getCoin.'个]',
                'dig_time' =>time(),
            ];

            if(!Db::name('PluginsGoldCoinDig')
                ->where(['id'=>$params['dig_id']])->update($digData))
            {
                Db::rollback();
                throw new \Exception("挖矿失败");
            }

            // 处理成功
            Db::commit();
            
            // 需要添加一个可执行的url的方法
            $url = PluginsHomeUrl('gold_coin', 'Dig', 'dodig',$params); // 需要加入参数
            // $url=""; // 如果不需要前端访问外部页，则这里需要为空
            $result =['url'=>$url,'coin_number' =>$getCoin];
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


    }

}
