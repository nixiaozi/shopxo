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

// 应用行为扩展定义文件
return array (
  'app_init' => 
  array (
  ),
  'app_begin' => 
  array (
  ),
  'module_init' => 
  array (
  ),
  'action_begin' => 
  array (
  ),
  'view_filter' => 
  array (
  ),
  'app_end' => 
  array (
  ),
  'log_write' => 
  array (
  ),
  'plugins_service_users_center_left_menu_handle' => 
  array (
    0 => 'app\\plugins\\wallet\\Hook',
  ),
  'plugins_service_header_navigation_top_right_handle' => 
  array (
    0 => 'app\\plugins\\wallet\\Hook',
  ),
  'plugins_service_user_register_end' => 
  array (
    0 => 'app\\plugins\\wallet\\Hook',
  ),
  'plugins_view_user_login_info_top' => 
  array (
    0 => 'app\\plugins\\weixinwebauthorization\\Hook',
  ),
  'plugins_view_user_reg_info' => 
  array (
    0 => 'app\\plugins\\weixinwebauthorization\\Hook',
  ),
  'plugins_view_header_navigation_top_left_end' => 
  array (
    0 => 'app\\plugins\\weixinwebauthorization\\Hook',
  ),
  'plugins_service_users_personal_show_field_list_handle' => 
  array (
    0 => 'app\\plugins\\weixinwebauthorization\\Hook',
  ),
  'plugins_service_system_begin' => 
  array (
    0 => 'app\\plugins\\weixinwebauthorization\\Hook',
  ),
);
?>