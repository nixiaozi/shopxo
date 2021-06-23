# 账户金币表
CREATE TABLE IF NOT EXISTS `{PREFIX}plugins_gold_coin`(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
    `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
    `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态（）',
    `total_coin` int(10) NOT NULL DEFAULT '0' COMMENT '账户获得的所有金币',
    `current_coin` int(10) NOT NULL DEFAULT '0' COMMENT '账户当前的金币余额',
    `used_coin` int(10) NOT NULL DEFAULT '0' COMMENT '账户已经使用的金币',
    `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
    `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_id` (`user_id`) USING BTREE,
    KEY `status` (`status`)
)ENGINE=InnoDB DEFAULT CHARSET={CHARSET} ROW_FORMAT=DYNAMIC COMMENT='金币 - 应用';


# 金币日志
CREATE TABLE IF NOT EXISTS `{PREFIX}plugins_gold_coin_log`(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
    `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
    `gold_coin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '金币账户id',
    `business_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '业务类型（0系统, 1充值, 2挖矿_随机, 3挖矿_审核产品）',
    `operation_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '操作类型（ 0减少, 1增加）',
    `operation_coin` int(10) NOT NULL DEFAULT '0' COMMENT '操作数量',
    `original_coin` int(10) NOT NULL DEFAULT '0' COMMENT '原始数量',
    `latest_coin` int(10) NOT NULL DEFAULT '0' COMMENT '最新数量',
    `msg` char(200) NOT NULL DEFAULT '' COMMENT '变更说明',
    `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
    PRIMARY KEY (`id`),
    KEY `gold_coin_id` (`gold_coin_id`),
    KEY `user_id` (`user_id`)
)ENGINE=InnoDB DEFAULT CHARSET={CHARSET} ROW_FORMAT=DYNAMIC COMMENT='金币日志 - 应用';


# 金币变现
CREATE TABLE IF NOT EXISTS `{PREFIX}plugins_gold_coin_money`(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
    `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
    `gold_coin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '金币账户id',
    `cash_no` char(60) NOT NULL DEFAULT '' COMMENT '提现单号',
    `gold_out` int(10) NOT NULL DEFAULT '0' COMMENT '消耗金币数',
    `integral_out` int(10) NOT NULL DEFAULT '0' COMMENT '消耗积分数',
    `money_in` int(10) NOT NULL DEFAULT '0' COMMENT '获得金额数',
    `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0待审核, 1审核通过, 2审核拒绝）',
    `msg` char(200) NOT NULL DEFAULT '' COMMENT '描述（用户可见）',
    `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
    `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
    PRIMARY KEY (`id`),
    KEY `gold_coin_id` (`gold_coin_id`),
    KEY `user_id` (`user_id`)
)ENGINE=InnoDB DEFAULT CHARSET={CHARSET} ROW_FORMAT=DYNAMIC COMMENT='金币 -- 变现';

# 金币获取
CREATE TABLE IF NOT EXISTS `{PREFIX}plugins_gold_coin_dig`(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
    `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
    `gold_coin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '金币账户id',
    `dig_module` char(60) NOT NULL DEFAULT '' COMMENT '挖矿所使用的模块',
    `dig_action` char(60) NOT NULL DEFAULT '' COMMENT '挖矿所使用的方法',
    `dig_params` longtext  COMMENT '挖矿所使用的参数 json格式 【这里可以附加用户添加的参数】',
    `dig_gold` int(10) NOT NULL DEFAULT '0' COMMENT '挖到的金币数',
    `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0可挖矿, 1待审核, 2已挖掘）',
    `msg` char(200) NOT NULL DEFAULT '' COMMENT '描述（用户可见）',
    PRIMARY KEY (`id`),
    KEY `gold_coin_id` (`gold_coin_id`),
    KEY `user_id` (`user_id`)
)ENGINE=InnoDB DEFAULT CHARSET={CHARSET} ROW_FORMAT=DYNAMIC COMMENT='金币 -- 获取';








