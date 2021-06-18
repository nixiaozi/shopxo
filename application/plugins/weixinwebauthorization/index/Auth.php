<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\plugins\weixinwebauthorization\index;

use think\Controller;
use app\plugins\weixinwebauthorization\service\AuthService;

/**
 * 微信网页授权 - 授权处理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Auth extends Controller
{
    /**
     * 用户解绑
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-05-26T00:55:08+0800
     * @param    array                    $params [description]
     */
    public function Unbind($params = [])
    {
        return AuthService::WeixinUnbind($params);
    }

    /**
     * 授权
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function Index($params = [])
    {
        $ret = AuthService::Auth($params);
        if($ret['code'] == 0)
        {
            return redirect($ret['data']);
        } else {
            if(APPLICATION == 'web')
            {
                $this->assign('msg', $ret['msg']);
                return $this->fetch('public/tips_error');
            } else {
                die($ret['msg']);
            }
        }
    }

    /**
     * 授权回调
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function Callback($params = [])
    {
        $ret = AuthService::Callback($params);
        if($ret['code'] == 0)
        {
            // 授权成功检查是否存在需要回调的页面url地址
            $key = AuthService::$pay_callback_view_url;
            $url = session($key);
            if(empty($url))
            {
                // 来源 url 地址
                $key = AuthService::$request_callback_url;
                $url = session($key);
                if(empty($url))
                {
                    $url = __MY_URL__;
                }
            }
            session($key, null);
            return redirect($url);
        } else {
            if(APPLICATION == 'web')
            {
                $this->assign('msg', $ret['msg']);
                return $this->fetch('public/tips_error');
            } else {
                die($ret['msg']);
            }
        }
    }
}
?>