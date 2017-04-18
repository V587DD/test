<?php

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);

//给静态资源文件访问目录设置常量，方便后期维护
//Home分组
define('BOOTSTRAP_URL','/Public/bootstrap/');
define('CSS_URL','/Public/css/');
define('IMG_URL','/Public/images/');
define('JS_URL','/Public/js/');
define('FONTS_URL','/Public/fonts/');
define('AWESOME_URL','/Public/font-awesome/');
define('SCO_URL','/Public/sco/');
define('UEDITOR_URL','/Public/ueditor/');

//文件上传路径
define('FILE_UPLOADSS','/test/');
// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单