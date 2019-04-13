<?php
//ini_set("display_errors",'on');
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ALL);

use Phalcon\Di\FactoryDefault;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
//系统常量
include APP_PATH."/config/constant.php";
//异常处理
require APP_PATH.'/exceptions/handle.php';

try {

    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();

    /**
     * Read services
     */
    require APP_PATH . "/config/services.php";

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    require APP_PATH . '/config/loader.php';

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    $application->useImplicitView(false);

    $content = $application->handle()->getContent();

    //缓存结果1天
    $application->di->cache->save($requestSn,$content,86400);

    echo $content;

} catch (\Phalcon\Mvc\Dispatcher\Exception $e) {
    echo json_encode(['code'=>404,'msg'=>'Not Found']);
} catch (\Exception $e) {
    logger($e->getMessage());
    getContent(APP_DEBUG ? $e->getMessage() : $errTips);
}
