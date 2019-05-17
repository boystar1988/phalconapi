<?php
//200
header("HTTP/1.0 200 OK");

$uri = explode('?',$_SERVER['REQUEST_URI'])[0]??'';

$requestSn = md5(PROJECT_NAME.$uri);

//错误日志
function logger($message)
{
    $errorType = APP_DEBUG ? 'debug' : 'error';
    file_put_contents(APP_PATH."/runtime/$errorType/".date("Ymd").'.log', "[".date('Y-m-d H:i:s')."] [".$_SERVER['REMOTE_ADDR'].'] '.$message.PHP_EOL, FILE_APPEND);
}

//异常处理
function shutdown_function()
{
    global $content;
    $e = error_get_last();
    if($e['message'] && !$content){
        logger($e['message']);
        getContent(APP_DEBUG ? $e['message'] : ERR_TIPS);exit;
    }
}
register_shutdown_function('shutdown_function');

//获取缓存
function getContent($msg)
{
    global $di,$uri,$requestSn;

    //保护路由，出错自动读取上一次缓存结果
    $protectRoute = require dirname(__DIR__) . "/config/protect-routes.php";

    if(in_array($uri,array_keys($protectRoute)) && in_array($di->request->getMethod(),$protectRoute[$uri]) && $di->cache->exists($requestSn)){
        echo $di->cache->get($requestSn);
    }else{
        echo json_encode(['code'=>1,'msg'=>$msg]);
    }
}
