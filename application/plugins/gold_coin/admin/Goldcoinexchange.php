<?php
namespace app\plugins\gold_coin\admin;

use app\plugins\gold_coin\service\GoldCoinBaseService;
use app\plugins\gold_coin\service\GoldCoinService;
use app\plugins\gold_coin\service\GoldExchangeService;
use app\plugins\wallet\service\WalletService;
use app\service\IntegralService;
use think\Controller;

class Goldcoinexchange extends Controller
{
    public function index($params = [])
    {
        
        // 分页
        $number = MyC('admin_page_number', 10, true);

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
            'url'       =>  PluginsAdminUrl('gold_coin', 'goldcoinexchange', 'index'),
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
            $data = GoldCoinBaseService::ExchangeList($data_params);
            $this->assign('data_list', $data['data']);
        } else {
            $this->assign('data_list', []);
        }

        // 静态数据
        $this->assign('Excharge_status_list',GoldExchangeService::$Excharge_status_list);

        // 参数
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/gold_coin/admin/goldcoinexchange/index');
    }

    public function auditinfo($params = [])
    {
        $data = [];
        if(!empty($params['id']))
        {
            $data_params = array(
                'm'         => 0,
                'n'         => 1,
                'where'     => ['id'=>intval($params['id'])],
            );
            $ret = GoldCoinBaseService::ExchangeList($data_params);
            if(!empty($ret['data'][0]))
            {
                // 用户金币
                $user_goldcoin = GoldCoinService::UserGoldCoin($ret['data'][0]['user_id']);
                // 用户积分
                $user_integral = IntegralService::UserIntegral($ret['data'][0]['user_id']);


                if($user_goldcoin['code'] == 0)
                {
                    $data = $ret['data'][0];
                    $this->assign('user_goldcoin', $user_goldcoin['data']);
                    $this->assign('user_integral', $user_integral['integral']);
                }else{
                    $this->assign('msg', '用户钱包与用户积分系统异常');
                }
            }else {
                $this->assign('msg', '数据不存在或已删除');
            }
        }else {
            $this->assign('msg', '参数id有误');
        }

        
        $this->assign('data', $data);
        return $this->fetch('../../../plugins/view/gold_coin/admin/goldcoinexchange/auditinfo');
    }

    public function audit($params = [])
    {
        return GoldExchangeService::ExchangeAudit($params);
    }
}
?>