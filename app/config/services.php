<?php

use Phalcon\Mvc\Router;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

///**
// * Setting up the view component
// */
//$di->setShared('view', function () {
//    $config = $this->getConfig();
//
//    $view = new View();
//    $view->setDI($this);
//    $view->setViewsDir($config->application->viewsDir);
//
//    $view->registerEngines([
//        '.phtml' => PhpEngine::class
//
//    ]);
//
//    return $view;
//});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();
    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $connection = new $class($config->database->toArray());
    return $connection;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('cache', function () {
    $config = $this->getConfig();
    $frontend = new Phalcon\Cache\Frontend\Json(["lifetime" => 3600]);
    $cache = new Phalcon\Cache\Backend\Redis($frontend,$config->cache->toArray());
    return $cache;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('redis', function () {
    $config = $this->getConfig();
    $cache = new Phalcon\Cache\Frontend\Json(["lifetime" => 3600]);
    $redis = new Phalcon\Cache\Backend\Redis($cache,$config->redis->toArray());
    return $redis;
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

///**
// * Register the session flash service with the Twitter Bootstrap classes
// */
//$di->set('flash', function () {
//    return new Flash([
//        'error'   => 'alert alert-danger',
//        'success' => 'alert alert-success',
//        'notice'  => 'alert alert-info',
//        'warning' => 'alert alert-warning'
//    ]);
//});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

$di->set('router',function (){
    $config = $this->getConfig();
    $router = new Phalcon\Mvc\Router();
    $router->setUriSource(Router::URI_SOURCE_SERVER_REQUEST_URI);
//    $router->removeExtraSlashes(true);//处理结尾额外的斜杆
//    $router->notFound(["controller"=>'index','action'=>'error']);
    $routeRule = $config->route->toArray();
    foreach ($routeRule as $k=>$v){
        $router->add($v['path']??'',$v['action']??null , $v['method']??null )->setName($k);
    }
    return $router;
});

//注册派遣器
$di->set('dispatcher', function () {
    $dispatcher = new Phalcon\Mvc\Dispatcher();
//    $dispatcher->setDefaultNamespace('App\Controllers');
    return $dispatcher;
});

//队列
$di->setShared('queue',function ()use ($di){
    $config = $this->getConfig();
    $beanstalk = new Phalcon\Queue\Beanstalk(['host'=>$config->beanstalk->host,'port'=>$config->beanstalk->port]);
    $beanstalk->connect();
    $beanstalk->choose($config->beanstalk->tube);
    return $beanstalk;
});

$di->setShared('dbmap',function (){
    return include APP_PATH . "/config/dbmap.php";
});

$di->setShared('user',function () use($di){
    $service = new UserService();
    $service->di = $di;
    return $service;
});