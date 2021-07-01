<?php
namespace app\plugins\gold_coin\index;

use app\plugins\gold_coin\index\Common;
use app\plugins\gold_coin\service\GoldCoinBaseService;
use app\plugins\gold_coin\service\GoldCoinService;
use app\service\SeoService;

// 必须要写成 首字母大写，其余小写的格式，因为 ucfirst 这个
class Goldcoin extends Common
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
        $where = GoldCoinBaseService::GoldCoinLogWhere($params);

        // 获取总数
        $total = GoldCoinBaseService::GoldCoinLogTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
                'url'       =>  PluginsHomeUrl('gold_coin', 'Goldcoin', 'index'),
            );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'm'         => $page->GetPageStarNumber(),
            'n'         => $number,
            'where'     => $where,
        );
        $data = GoldCoinBaseService::GoldCoinLogList($data_params);
        $this->assign('data_list', $data['data']);

        // 静态数据
        $this->assign('business_type_list', GoldCoinService::$business_type_list);
        $this->assign('operation_type_list', GoldCoinService::$operation_type_list);
        // $this->assign('money_type_list', WalletService::$money_type_list);

        // 浏览器名称
        $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('我的金币', 1));

        
        // 参数
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/gold_coin/index/gold_coin/index');
    }


}



?>