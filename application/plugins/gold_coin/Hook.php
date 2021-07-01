<?php
namespace app\plugins\gold_coin;

use think\Controller;

/**
 * 金币 - 钩子入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Hook extends Controller
{
    // 应用响应入口
    public function run($params = [])
    {
        // // 是否控制器钩子
        // // is_backend 当前为后端业务处理
        // // hook_name 钩子名称
        // if(isset($params['is_backend']) && $params['is_backend'] === true && !empty($params['hook_name']))
        // {
        //     // 参数一   描述
        //     // 参数二   0 为处理成功, 负数为失败
        //     // 参数三   返回数据
        //     return DataReturn('返回描述', 0);

        // // 默认返回视图
        // } else {
        //     return 'hello world!';
        // }
        
        // 根据不同的钩子名称来执行钩子句柄
        if(!empty($params['hook_name'])){
            switch($params['hook_name']){
                 // 用户中心左侧导航
                 case 'plugins_service_users_center_left_menu_handle' :
                    $ret = $this->UserCenterLeftMenuHandle($params);
                    break;

                // 顶部小导航右侧-我的商城
                case 'plugins_service_header_navigation_top_right_handle' :
                    $ret = $this->CommonTopNavRightMenuHandle($params);
                    break;

                // 用户注册
                case 'plugins_service_user_register_end' :
                    // $ret = WalletService::UserWallet($params['user_id']);
                    break;

                default :
                    $ret = '';
            }
        }

    }


    public function UserCenterLeftMenuHandle($params = [])
    {
        $params['data']['property']['item'][] = [
            'name'      =>  '我的金币',
            'url'       =>  PluginsHomeUrl('gold_coin', 'GoldCoin', 'index'),
            //'contains'  =>  ['gold_coinindex', 'rechargeindex', 'cashindex', 'cashauthinfo', 'cashcreateinfo'],
            'is_show'   =>  1,
            'icon'      =>  'am-icon-btc',  // 这里添加的是 一个我们需要的页面
        ];
    }

    public function CommonTopNavRightMenuHandle($params = [])
    {
        array_push($params['data'][1]['items'], [
            'name'  => '我的金币',
            'url'   => PluginsHomeUrl('gold_coin', 'GoldCoin', 'index'),
            'is_show'   =>  1,
        ]);
    }

}
?>