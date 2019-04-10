<?php
//ini_set("display_errors",'on');
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ALL);

use Phalcon\Di\FactoryDefault;

define('PHALCON_DEBUG', true);
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

$errorType = PHALCON_DEBUG ? 'debug' : 'error';

$content = '';

function logger($errorType,$message)
{
    file_put_contents(APP_PATH."/runtime/$errorType/".date("Ymd").'.log', "[".date('Y-m-d H:i:s')."] [".$_SERVER['REMOTE_ADDR'].'] '.$message.PHP_EOL, FILE_APPEND);
}

function shutdown_function()
{
    global $errorType,$content;
    $e = error_get_last();
    if($e['message'] && !$content){
        echo json_encode(['code'=>1,'msg'=>PHALCON_DEBUG ? $e['message'] : '系统繁忙']);
        logger($errorType,$e['message']);
        exit;
    }
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

    $content = $application->handle()->getContent();

    echo $content;

} catch (\Phalcon\Mvc\Dispatcher\Exception $e) {
    echo json_encode(['code'=>404,'msg'=>'Not Found']);
} catch (\Exception $e) {
    echo json_encode(['code'=>1,'msg'=>PHALCON_DEBUG ? $e->getMessage() : '系统繁忙']);
    logger($errorType,$e->getMessage());
}
