<?php
namespace app\plugins\gold_coin\admin;

use app\plugins\gold_coin\service\GoldCoinBaseService;
use app\plugins\gold_coin\service\GoldDigService;
use think\Controller;
use app\service\PluginsService;
use think\Db;

/**
 * 金币 - 后台管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Admin extends Controller
{
    // 构造方法
    public function __construct($params = [])
    {
        parent::__construct();
    }

    // 后台管理入口
    public function index($params = [])
    {
        // // 数组组装
        // $this->assign('data', ['hello', 'world!']);
        // $this->assign('msg', 'hello world! admin');
        // return $this->fetch('../../../plugins/view/gold_coin/admin/admin/index');
        
        // 获取插件的配置数据
        $ret = GoldCoinBaseService::BaseConfig(false);
        if($ret["code"]==0){
            $this->assign("data",$ret["data"]);

            // 统计数据 —— 可以使用一个服务添加
            

            // 输出管理页面
            return $this->fetch('../../../plugins/view/gold_coin/admin/admin/index');
        }else{
            return $ret['msg'];
        }


    }

    public function  saveinfo($params = []){
        $ret = GoldCoinBaseService::BaseConfig(false);
        if($ret["code"] == 0)
        {
            // 需要获取所有的内容
            $gold_all_dig = Db::name("Config")->where(["only_tag"=>GoldDigService::$all_dig_func_key])->find();
            $gold_all_dig = $gold_all_dig==null ? []:$gold_all_dig;
            
            
            
            // 是否
            $is_whether_list =  [
                0 => array('value' => 0, 'name' => '否'),
                1 => array('value' => 1, 'name' => '是', 'checked' => true),
            ];
            $this->assign('is_whether_list', $is_whether_list);

            $this->assign('data', $ret['data']);
            return $this->fetch('../../../plugins/view/gold_coin/admin/admin/saveinfo');
        }
        else{
            return $ret['msg'];
        }
    }



    public function  save($params=[])
    {
        // 暂时不需要进行任何操作，可以直接进行保存
        
        
        // 数据保存
        return PluginsService::PluginsDataSave(['plugins'=>'gold_coin', 'data'=>$params]);
    }
}

?>