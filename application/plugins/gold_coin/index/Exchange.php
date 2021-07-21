<?php
namespace app\plugins\gold_coin\index;

use app\plugins\gold_coin\index\Common;
use app\plugins\gold_coin\service\GoldCoinBaseService;
use app\plugins\gold_coin\service\GoldCoinService;
use app\plugins\gold_coin\service\GoldExchangeService;
use app\service\IntegralService;
use app\service\SeoService;
use think\Db;

class Exchange extends Common
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($params = [])
    {
        // 参数
        // $params['user'] = $this->user;

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
            'url'       =>  PluginsHomeUrl('gold_coin', 'exchange', 'index'),
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


    public function authinfo($params = [])
    {
        // 是否开启提现申请
        if(isset($this->plugins_base['is_enable_exchange']) && $this->plugins_base['is_enable_exchange'] == 0)
        {
            $this->assign('msg', '暂时关闭了提现申请');
            return $this->fetch('public/tips_error');
        }

        $params['user'] = $this->user;

        // 认证方式
        // $this->assign('check_account_list', CashService::UserCheckAccountList($this->user));

        // 需要获取后台配置的 最低金额和最高金额  用户的积分  和金币总数
        // 后台配置已经添加在 plugins_base 中
        $userGoldCoin = GoldCoinService::UserGoldCoin($params['user']['id']);
        $userIntegral = IntegralService::UserIntegral($params['user']['id']);

        $this->assign('user_current_coin',$userGoldCoin['data']['current_coin']);
        $this->assign('user_current_Integral',$userIntegral['integral']);
        $this->assign('can_give_money',$userGoldCoin['data']['current_coin']<$userIntegral['integral']?$userGoldCoin['data']['current_coin']:$userIntegral['integral']);


        // 浏览器名称
        $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('金币兑现申请 - 我的金币', 1));


        // 参数
        $this->assign('params', $params);
        return $this->fetch('../../../plugins/view/gold_coin/index/exchange/authinfo');
    }




    public function create($params = [])
    {
        // 获取当前用户并进行添加处理
        $params['user'] = $this->user;
        return GoldExchangeService::ExchangeCreate($params);
    }

    

}



?>