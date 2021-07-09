<?php
namespace app\plugins\gold_coin;

use app\service\ConfigService;
use app\service\SqlconsoleService;
use think\Db;

// 这里可以添加自定义事件，执行一些额外操作
// 系统已经定义好 钩子
class Event{

    const all_dig_func_key ="gold_coin_all_dig_func";

    public function Install($params = [])
    {
        // 这里可以在Install的方法添加
        
         // sql运行
         $install_sql = APP_PATH.'plugins'.DS.'gold_coin'.DS.'install.sql';
         if(file_exists($install_sql))
         {
             SqlconsoleService::Implement(['sql'=>file_get_contents($install_sql)]);
         }
         // 在配置文件中添加全局配置 ---- 需要添加所有的配置信息
        $all_dig_method = Db::name("Config")->where(["only_tag"=>self::all_dig_func_key])->find();
        if($all_dig_method == null)
        {
            $dig_methods_data=[
                'value' => [['pluginsName'=>'gold_coin','controllerName'=>'Dig','actionName'=>'DigCoin',
                    'descript'=>'随机获得金币']],
                'name' =>'挖矿方法',
                'describe' => '这里记录系统中所有可用的挖矿方法',
                'error_tips' => '不正确的挖矿方法',
                'type' => 'gold_coin',
                'only_tag' => self::all_dig_func_key,
                'upd_time' => time(),
            ];

            Db::name('Config')->json(['value'])->insert($dig_methods_data);

            // 安装之后需要更新缓存的配置信息
            ConfigService::ConfigInit(1);

        }
    
    }

    public function Uninstall($params = [])
    {
        // 卸载时需要删除全局配置的文件
        $all_dig_method = Db::name("Config")->where(["only_tag"=>self::all_dig_func_key])->find();
        if($all_dig_method != null)
        {
            Db::name("Config")->where(["only_tag"=>self::all_dig_func_key])->delete();
        }


            // 安装之后需要更新缓存的配置信息
            ConfigService::ConfigInit(1);
    }

}


