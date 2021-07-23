<?php
namespace app\plugins\gold_coin\admin;

use app\plugins\gold_coin\service\GoldCoinBaseService;
use app\plugins\gold_coin\service\GoldCoinService;
use think\Controller;

class Goldcoin extends Controller
{
    public function index($params = [])
    {
        // 分页
        $number = MyC('admin_page_number', 10, true);

        // 条件
        $where = GoldCoinBaseService::GoldCoinWhere($params);

        // 获取总数
        $total = GoldCoinBaseService::GoldCoinTotal($where);

        // 分页
        $page_params = array(
            'number'    =>  $number,
            'total'     =>  $total,
            'where'     =>  $params,
            'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
            'url'       =>  PluginsAdminUrl('gold_coin', 'Goldcoin', 'index'),
        );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        if($total > 0)
        {
            $data_params = array(
                'm'         => $page->GetPageStarNumber(),
                'n'         => $number,
                'where'     => $where,
            );
            $data = GoldCoinBaseService::CoinAccountList($data_params);
            $this->assign('data_list', $data['data']);
        } else {
            $this->assign('data_list', []);
        }

        // 静态数据
        $this->assign('gold_coin_status_list', GoldCoinService::$gold_coin_status_list);

        // 参数
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/gold_coin/admin/goldcoin/index');

    }

}

?>