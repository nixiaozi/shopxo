<?php
namespace app\plugins\gold_coin\index;

use app\plugins\gold_coin\service\GoldCoinService;
use think\Controller;
use app\service\UserService;
use app\plugins\gold_coin\service\GoldCoinBaseService;

class Common extends Controller
{
    protected $user;
    protected $user_gold_account;
    protected $plugins_base;

    public function __construct()
    {
        parent::__construct();

        // 用户信息
        $this->user = UserService::LoginUserInfo();

        
        // 登录校验
        if(empty($this->user))
        {
            if(IS_AJAX)
            {
                exit(json_encode(DataReturn('登录失效，请重新登录', -400)));
            } else {
                return $this->redirect('index/user/logininfo');
            }
        }

        // 用户金币账户
        $user_gold_account = GoldCoinService::UserGoldCoin($this->user['id']);

        $gold_account_error =  ($user_gold_account['code'] == 0) ? '' : $user_gold_account['msg'];

        $this->assign('gold_account_error', $gold_account_error);

        // 所有ajax请求校验用户金币账户状态
        if(IS_AJAX && !empty($gold_account_error))
        {
            exit(json_encode(DataReturn($gold_account_error, -50)));
        }


        $this->user_gold_account = $user_gold_account['data'];
        $this->assign('user_gold_account', $user_gold_account['data']);

        // 应用配置
        $plugins_base = GoldCoinBaseService::BaseConfig();
        $this->plugins_base = $plugins_base['data'];
        $this->assign('plugins_base', $this->plugins_base);


    }

}



?>