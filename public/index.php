<?php
//ini_set("display_errors",'on');
use Phalcon\Di\FactoryDefault;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

function shutdown_function()
{
    $e = error_get_last();
    echo json_encode(['code'=>1,'msg'=>$e['message']]);
}
register_shutdown_function('shutdown_function');

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

    echo $application->handle()->getContent();

} catch (\Phalcon\Mvc\Dispatcher\Exception $e) {
    echo json_encode(['code'=>404,'msg'=>'Not Found']);
} catch (\Exception $e) {
    echo json_encode(['code'=>1,'msg'=>$e->getMessage()]);
}
