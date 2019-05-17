<?php
/*
 * Modified: preppend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(dirname(__DIR__))));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/console');

$localConfig = include "config-local.php";

return new \Phalcon\Config(array_merge([
    'application' => [
        'appDir'         => APP_PATH . '/',
        'listenersDir'   => APP_PATH . '/listeners/',
        'tasksDir'       => APP_PATH . '/tasks/',
        'baseUri'        => '/phalconapi/',
        'debug' => [
            'state' => false,
            'path' => APP_PATH . '/runtime/debug/{YmdH}.log'
        ],
        'error' => [
            'path' => APP_PATH . '/runtime/error/{YmdH}.log'
        ]
    ],
    'socket'=>[
        'port' => 9505,
        'mode' => SWOOLE_PROCESS,
        'config'=>[
            'reactor_num'=>1,
            'task_worker_num'=>2,
            'worker_num'=>2,
//            'ssl_key_file'=>'',
//            'ssl_cert_file'=>'',
        ]
    ],
    'redis'=>[
        'host'=>'127.0.0.1',
        'port'=>6379,
        'timeout'=>0,
    ]
],$localConfig));
