<?php
namespace app\plugins\gold_coin\index;

use app\plugins\gold_coin\index\Common;
use app\plugins\gold_coin\service\GoldCoinBaseService;
use app\plugins\gold_coin\service\GoldCoinService;
use app\plugins\gold_coin\service\GoldDigService;
use app\service\SeoService;

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

        // 条件
        $where = GoldCoinBaseService::GoldDigWhere($params);

        // 获取总数
        $total = GoldCoinBaseService::GoldDigTotal($where);


        // 分页
        $page_params = array(
            'number'    =>  $number,
            'total'     =>  $total,
            'where'     =>  $params,
            'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
            'url'       =>  PluginsHomeUrl('gold_coin', 'Dig', 'index'),
        );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'm'         => $page->GetPageStarNumber(),
            'n'         => $number,
            'where'     => $where,
        );

        $data = GoldCoinBaseService::GoldDigList($data_params);

        $this->assign('data_list', $data['data']);

        // 浏览器名称
        $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('挖矿明细 - 我的钱包', 1));
        


        // 参数
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/gold_coin/index/dig/index');
    }

    // 这个是测试的外部 url 访问的内容。
    public function DoDig($params = [])
    {
        // 参数
        $params['user'] = $this->user;

        // 这个页面的主要目的是给 系统api 发送一个通知，后台接到通知后就修改状态
        $goldcoin = GoldDigService::MethodOfDigCoin();
        $ret = GoldDigService::DoReceiveNotify($params['dig_id'],$params['check_code'],$goldcoin);
        

        $this->assign('ret', $ret);
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/gold_coin/index/dig/DoDig');

    }

}

?>