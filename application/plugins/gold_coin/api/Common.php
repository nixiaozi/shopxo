<?php

namespace app\plugins\gold_coin\api;

use think\Controller;
use app\service\UserService;

class Common extends Controller
{
    // 用户信息
    protected $user;

    // 输入参数 post
    protected $data_post;

    // 输入参数 get
    protected $data_get;

    // 输入参数 request
    protected $data_request;


    public function __construct()
    {
        parent::__construct();

        // 用户信息
        $this->user = UserService::LoginUserInfo();

        // 输入参数
        $this->data_post = input('post.');
        $this->data_get = input('get.');
        $this->data_request = input();
    }
    
    protected function IsLogin()
    {
        if(empty($this->user))
        {
            exit(json_encode(DataReturn('登录失效，请重新登录', -400)));
        }
    }

}

?>