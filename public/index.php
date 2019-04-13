<?php
//ini_set("display_errors",'on');
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ALL);

use Phalcon\Di\FactoryDefault;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
//调试模式 打开时抛出详细错误信息，关闭时提示友好信息并记录到日志
define('APP_DEBUG', false);
//项目名称
define('PROJECT_NAME', 'phalconapi');

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
    getContent($di,APP_DEBUG ? $e->getMessage() : $errTips);
}
