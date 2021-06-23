<?php
namespace app\plugins\gold_coin;


use app\service\SqlconsoleService;

// 这里可以添加自定义事件，执行一些额外操作
// 系统已经定义好 钩子
class Event{

    public function Install($params = [])
    {
        // 这里可以在Install的方法添加
         // sql运行
         $install_sql = APP_PATH.'plugins'.DS.'gold_coin'.DS.'install.sql';
         if(file_exists($install_sql))
         {
             SqlconsoleService::Implement(['sql'=>file_get_contents($install_sql)]);
         }
    }

}


