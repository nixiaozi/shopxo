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
namespace app\plugins\weixinwebauthorization\service;

use think\Db;
use app\service\UserService;
use app\service\PluginsService;

/**
 * 授权 - 服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AuthService
{
    // 来源页面地址 key
    public static $request_callback_url = 'plugins_weixinwebauth_request_callback_view_url';

    // 系统中需要回调的地址 key
    public static $pay_callback_view_url = 'plugins_weixinwebauth_pay_callback_view_url';

    /**
     * 授权
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public static function Auth($params = [])
    {
        $ret = PluginsService::PluginsData('weixinwebauthorization');
        if($ret['code'] == 0)
        {
            // 参数校验
            if(empty($ret['data']['appid']))
            {
                return DataReturn('appid未配置', -1);
            }

            // 回调地址
            $redirect_uri = urlencode(PluginsHomeUrl('weixinwebauthorization', 'auth', 'callback'));

            // 授权方式
            $auth_type = (isset($ret['data']['auth_type']) && $ret['data']['auth_type'] == 1) ? 'snsapi_userinfo' : 'snsapi_base';

            // 授权code
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$ret['data']['appid'].'&redirect_uri='.$redirect_uri.'&response_type=code&scope='.$auth_type.'&state=login#wechat_redirect';
            return DataReturn('操作成功', 0, $url);
        }
        return $ret;
    }

    /**
     * 回调
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public static function Callback($params = [])
    {
        // 参数校验
        if(empty($params['code']))
        {
            return DataReturn('授权code为空', -1);
        }

        // 远程获取access_token
        $ret = self::RemoteAccessToken($params);
        if($ret['code'] != 0)
        {
            return DataReturn($ret['msg'], -1);
        }

        // 获取插件配置
        $base = PluginsService::PluginsData('weixinwebauthorization');
        if($base['code'] == 0 && isset($base['data']['auth_type']) && $base['data']['auth_type'] == 1)
        {
            // 获取用户信息
            $ret = self::RemoteUserInfo($ret['data']);
            if($ret['code'] != 0)
            {
                return DataReturn($ret['msg'], -1);
            }
        }

        // 处理用户数据
        return self::WeixinAuthBind($ret['data']);
    }

    /**
     * 获取用户信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-24
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public static function RemoteUserInfo($params = [])
    {
        // 参数校验
        if(empty($params['access_token']))
        {
            return DataReturn('access_token为空', -1);
        }
        if(empty($params['openid']))
        {
            return DataReturn('openid为空', -1);
        }

        // 获取用户详细信息
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$params['access_token'].'&openid='.$params['openid'].'&lang=zh_CN';
        $data = json_decode(CurlGet($url), true);
        if(empty($data) || empty($data['openid']))
        {
            if(empty($data['errmsg']))
            {
                return DataReturn('获取用户信息失败', -100);
            } else {
                return DataReturn($data['errmsg'], -100);
            }
        }
        return DataReturn('获取成功', 0, $data);
    }

    /**
     * 远程获取access_token
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-24
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public static function RemoteAccessToken($params = [])
    {
        $ret = PluginsService::PluginsData('weixinwebauthorization');
        if($ret['code'] == 0)
        {
            // 参数校验
            if(empty($ret['data']['appid']))
            {
                return DataReturn('appid未配置', -1);
            }
            if(empty($ret['data']['secret']))
            {
                return DataReturn('secret未配置', -1);
            }
            if(empty($params['code']))
            {
                return DataReturn('code授权码为空', -1);
            }

            // 获取access_token
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$ret['data']['appid'].'&secret='.$ret['data']['secret'].'&code='.$params['code'].'&grant_type=authorization_code';
            $data = json_decode(CurlGet($url), true);
            if(empty($data) || empty($data['access_token']))
            {
                if(empty($data['errmsg']))
                {
                    return DataReturn('获取access_token失败', -100);
                } else {
                    return DataReturn($data['errmsg'], -100);
                }
            }
            return DataReturn('获取成功', 0, $data);

        }
        return $ret;
    }

    /**
     * 微信解绑
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-05-26T00:56:04+0800
     * @param   [array]          $params [输入参数]
     */
    public static function WeixinUnbind($params = [])
    {
        $user = UserService::LoginUserInfo();
        if(!empty($user))
        {
            $data = [
                'weixin_web_openid' => '',
                'upd_time'          => time(),
            ];
            if(Db::name('User')->where(['id'=>$user['id']])->update($data))
            {
                if(UserService::UserLoginRecord($user['id']))
                {
                    return DataReturn('解绑成功', 0);
                }
            }
            return DataReturn('解绑失败', -100);
        }
        return DataReturn('未登录，不能操作', -1);
    }

    /**
     * 微信绑定
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-03
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function WeixinAuthBind($params = [])
    {
        // openid
        if(empty($params['openid']))
        {
            return DataReturn('用户openid为空', -1);
        }

        // 1.7.0新增用户unionid字段
        $unionid = self::UserUnionid($params);

        // 获取登录用户
        $user = UserService::LoginUserInfo();
        if(!empty($user))
        {
            // 是否已绑定
            if(!empty($user['weixin_web_openid']))
            {
                if($params['openid'] == $user['weixin_web_openid'])
                {
                    // 当用户与微信用户已绑定，但是unionid为空则处理更新
                    self::UserUnionidBind($params, $user);

                    // 用户登录session纪录
                    if(UserService::UserLoginRecord($user['id']))
                    {
                        // 用户cookie设置
                        self::SetUserCookie($user['id']);
                        return DataReturn('登录成功', 0);
                    }
                }
                return DataReturn('该帐号已绑定微信，请先解绑', -1);
            }

            // 绑定
            $data = [
                'weixin_web_openid' => $params['openid'],
                'upd_time'          => time()
            ];

            // 1.7.0新增用户unionid字段
            if(!empty($unionid))
            {
                $data['weixin_unionid'] = $unionid;
            }

            // 更新用户表绑定当前微信用户
            if(Db::name('User')->where(['id'=>$user['id']])->update($data))
            {
                // 用户登录session纪录
                if(UserService::UserLoginRecord($user['id']))
                {
                    // 用户cookie设置
                    self::SetUserCookie($user['id']);
                    return DataReturn('绑定成功', 0);
                }
            }
            return DataReturn('openid绑定失败', -100);
        }

        // openid登录
        $user = UserService::UserInfo('weixin_web_openid', $params['openid']);
        if(!empty($user))
        {
            // 当用户与微信用户已绑定，但是unionid为空则处理更新
            self::UserUnionidBind($params, $user);

            // 用户登录
            $ret = UserService::UserLoginHandle($user['id'], $params);
            if($ret['code'] == 0)
            {
                // 用户cookie设置
                self::SetUserCookie($user['id']);
            }
            return $ret;
        }

        // 1.7.0新增用户unionid字段
        if(!empty($unionid))
        {
            // unionid登录
            $user = UserService::UserInfo('weixin_unionid', $unionid);
            if(!empty($user))
            {
                // 更新用户表绑定当前微信用户
                $data = [
                    'weixin_web_openid' => $params['openid'],
                    'upd_time'          => time()
                ];
                if(Db::name('User')->where(['id'=>$user['id']])->update($data))
                {
                    // 用户登录
                    $ret = UserService::UserLoginHandle($user['id'], $params);
                    if($ret['code'] == 0)
                    {
                        // 用户cookie设置
                        self::SetUserCookie($user['id']);
                    }
                    return $ret;
                }
                return DataReturn('unionid绑定失败', -100);
            }
        }

        // 用户名
        $username = empty($params['nickname']) ? '微信-'.RandomString(6) : $params['nickname'].'-'.RandomString(6);
        if(mb_strlen($username, 'utf-8') > 18)
        {
            $username = mb_substr($username, 0, 18);
        }

        // 游客数据
        $salt = GetNumberCode(6);
        $data = [
            'weixin_web_openid' => $params['openid'],
            'username'          => $username,
            'nickname'          => empty($params['nickname']) ? '' : $params['nickname'],
            'gender'            => empty($params['sex']) ? 0 : ((isset($params['sex']) && $params['sex'] == 1) ? 2 : 1),
            'province'          => empty($params['province']) ? '' : $params['province'],
            'city'              => empty($params['city']) ? '' : $params['city'],
            'avatar'            => empty($params['headimgurl']) ? '' : $params['headimgurl'],
            'status'            => 0,
            'salt'              => $salt,
            'pwd'               => LoginPwdEncryption($username, $salt),
            'add_time'          => time(),
            'upd_time'          => time(),
        ];

        // 1.7.0新增用户unionid字段
        if(!empty($unionid))
        {
            $data['weixin_unionid'] = $unionid;
        }

        // 数据添加
        $ret = UserService::UserInsert($data, $params);
        if($ret['code'] == 0)
        {
            // 用户登录session纪录
            if(UserService::UserLoginRecord($ret['data']['user_id']))
            {
                // 用户cookie设置
                self::SetUserCookie($ret['data']['user_id']);
                return DataReturn('登录成功', 0, $ret['data']);
            }
        }
        return DataReturn('登录失败', -100);
    }

    /**
     * 当用户与微信用户已绑定，但是unionid为空则处理更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-10-11T23:32:15+0800
     * @desc     description
     * @param    [array]                    $params [输入参数]
     * @param    [array]                    $user   [当前用户信息]
     */
    private static function UserUnionidBind($params = [], $user = [])
    {
        // 1.7.0新增用户unionid字段
        $unionid = self::UserUnionid($params);
        if(!empty($unionid) && empty($user['weixin_unionid']) && !empty($user['id']))
        {
            Db::name('User')->where(['id'=>$user['id']])->update(['weixin_unionid'=>$unionid, 'upd_time'=>time()]);
        }
    }

    /**
     * 获取当前用户unionid
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-10-11T23:20:40+0800
     * @desc     description
     * @param    [array]                    $params [输入参数]
     */
    private static function UserUnionid($params = [])
    {
        // 1.7.0新增用户unionid字段
        if(!empty($params['unionid']) && str_replace('v', '', APPLICATION_VERSION) >= '1.7.0')
        {
            return $params['unionid'];
        }
        return '';
    }

    /**
     * 用户登录cookie设置
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-26
     * @desc    description
     * @param   [int]          $user_id [用户id]
     */
    public static function SetUserCookie($user_id)
    {
        // 获取用户信息
        $user = Db::name('User')->where(['id'=>intval($user_id)])->field('id,token,username,nickname,mobile,email,avatar')->find();
        if(!empty($user))
        {
            // 用户信息处理
            $user = UserService::UserHandle($user);

            // 没有token则生成
            if(empty($user['token']))
            {
                // 生成用户token
                if(method_exists(new UserService(), 'CreatedUserToken'))
                {
                    $user['token'] = UserService::CreatedUserToken($user_id);
                } else {
                    $user['token'] = md5(md5($user_id.time()).rand(100, 1000000));
                }

                // 更新用户token
                $data = [
                    'token'     => $user['token'],
                    'upd_time'  => time(),
                ];
                if(!Db::name('User')->where(['id'=>$user_id])->update($data))
                {
                    return false;
                }
            }

            // 设置cookie数据
            cookie('user_info', json_encode($user, JSON_UNESCAPED_UNICODE));
            return true;
        }
        return false;
    }
}
?>