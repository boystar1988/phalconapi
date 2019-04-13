<?php

//调试模式 打开时抛出详细错误信息，关闭时提示友好信息并记录到日志
define('APP_DEBUG', false);

//项目名称
define('PROJECT_NAME', 'phalconapi');

//分页
defined('PAGE_NO_DEFAULT') || define("PAGE_NO_DEFAULT",1);
defined('PAGE_SIZE_DEFAULT') || define("PAGE_SIZE_DEFAULT",15);

//服务常量
defined('SUCCESS_CODE') || define("SUCCESS_CODE",0);
defined('FAIL_CODE') || define("FAIL_CODE",1);

//API接口常量
defined('API_SUCCESS_CODE') || define("API_SUCCESS_CODE",200);
defined('API_FAIL_CODE') || define("API_FAIL_CODE",1);

//提示
defined('ERR_TIPS') || define('ERR_TIPS','服务器繁忙，请稍后再试～');
defined('ERR_NOT_FOUND_TIPS') || define('ERR_NOT_FOUND_TIPS','未找到该接口');
defined('TIPS_SAVE_OK') || define("TIPS_SAVE_OK","保存成功");
defined('TIPS_SAVE_FAIL') || define("TIPS_SAVE_FAIL","保存失败");
defined('TIPS_DELETE_OK') || define("TIPS_DELETE_OK","删除成功");
defined('TIPS_DELETE_FAIL') || define("TIPS_DELETE_FAIL","删除失败");
defined('TIPS_NOT_FOUND_RECORD') || define("TIPS_NOT_FOUND_RECORD","未找到该记录");