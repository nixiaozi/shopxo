<?php
namespace app\plugins\gold_coin\index;

use app\plugins\gold_coin\index\Common;
use app\plugins\gold_coin\service\GoldCoinBaseService;
use app\plugins\gold_coin\service\GoldCoinService;
use app\plugins\gold_coin\service\GoldExchangeService;
use app\service\SeoService;

class Exchange extends Common
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
        $where = GoldCoinBaseService::ExchangeWhere($params);

        // 获取总数
        $total = GoldCoinBaseService::ExchangeTotal($where);

        // 分页
        $page_params = array(
            'number'    =>  $number,
            'total'     =>  $total,
            'where'     =>  $params,
            'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
            'url'       =>  PluginsHomeUrl('wallet', 'cash', 'index'),
        );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'm'         => $page->GetPageStarNumber(),
            'n'         => $number,
            'where'     => $where,
        );
        $data = GoldCoinBaseService::ExchangeList($data_params);
        $this->assign('data_list', $data['data']);

        // 静态数据
        $this->assign('exchange_status_list', GoldExchangeService::$Excharge_status_list);

        // 浏览器名称
        $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('金币兑现 - 我的钱包', 1));


        // 参数
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/gold_coin/index/exchange/index');
    }


}



?>