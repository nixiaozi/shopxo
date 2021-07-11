<?php
namespace app\plugins\gold_coin\index;

use app\plugins\gold_coin\index\Common;
use app\plugins\gold_coin\service\GoldCoinBaseService;
use app\plugins\gold_coin\service\GoldCoinService;

class Dig extends Common
{
    public function __construct()
    {
        parent::__construct();
    }


    public function index($params = [])
    {
        // 参数
        $params['user'] = $this->user;

        // 分页
        $number = 10;

        


        // 参数
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/gold_coin/index/dig/index');
    }

    public function DoDig($params = [])
    {
        // 参数
        $params['user'] = $this->user;


        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/gold_coin/index/dig/DoDig');

    }

}

?>