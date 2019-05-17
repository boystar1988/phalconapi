<?php
//ini_set("display_errors",'on');
error_reporting(E_ALL);
ini_set('date.timezone','Asia/Shanghai');

version_compare(PHP_VERSION, '7.0.0', '>') || die('Require PHP > 7.0.0 !');
extension_loaded('phalcon7') || die('Please open the Phalcon7 extension !');

//目录常量
define('BASE_PATH', dirname(dirname(__DIR__)));
define('APP_PATH', BASE_PATH . '/backend');
//调试模式 打开时抛出详细错误信息，关闭时提示友好信息并记录到日志
define('APP_DEBUG', true);
//系统常量
include BASE_PATH."/common/config/constant.php";
//异常处理
require APP_PATH.'/exceptions/handle.php';

try {

    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new Phalcon\Di\FactoryDefault();

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

    echo json_encode(['code'=>API_NOT_FOUND,'msg'=>ERR_NOT_FOUND_TIPS]);

} catch (\Exception $e) {

    logger($e->getMessage());

    getContent(APP_DEBUG ? $e->getMessage() : ERR_TIPS);

}
