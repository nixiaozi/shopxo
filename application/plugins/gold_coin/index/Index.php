<?php
namespace app\plugins\gold_coin\index;

use think\Controller;

/**
 * 金币 - 前端独立页面入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Index extends Controller
{
    // 构造方法
    public function __construct($params = [])
    {
        parent::__construct();
    }

    // 前端页面入口
    public function index($params = [])
    {
        // 数组组装
        $this->assign('data', ['hello', 'world!']);
        $this->assign('msg', 'hello world! index');
        return $this->fetch('../../../plugins/view/gold_coin/index/index/index');
    }
}
?>