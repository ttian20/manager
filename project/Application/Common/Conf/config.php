<?php
$common = array(
    'DB_TYPE' => 'mysql', // 数据库类型
    #'DB_HOST' => 'localhost', // 服务器地址
    'DB_HOST' => '121.40.158.144', // 服务器地址
    'DB_NAME' => 'production', // 数据库名
    'DB_USER' => 'admin', // 用户名
    'DB_PWD' => 'txg19831210', // 密码
    'DB_PORT' => '3306', // 端口
    'DB_PREFIX' => '', // 数据库表前缀
    'DB_CHARSET' => 'utf8', // 数据库编码默认采用utf8

    'URL_MODEL' => 2,
    'URL_HTML_SUFFIX' => '',

    'TMPL_ENGINE_TYPE' => 'Smarty', //模版引擎配置
    'TMPL_ENGINE_CONFIG' => array(
         'left_delimiter' => '<{',
         'right_delimiter' => '}>',
    ),

    //默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => 'Layouts:dispatch_jump',
    //默认成功跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => 'Layouts:dispatch_jump',

    'SITE' => 'http://api.aymoo.com/',

    'URL_ROUTER_ON' => true,
);

return $common;
