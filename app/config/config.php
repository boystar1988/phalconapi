<?php
/*
 * Modified: preppend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

include "constant.php";
$localConfig = include "config-local.php";

return new \Phalcon\Config(array_merge([
    'application' => [
        'appDir'         => APP_PATH . '/',
        'helpersDir'     => APP_PATH . '/helpers/',
        'listenersDir'   => APP_PATH . '/listeners/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'servicesDir'    => APP_PATH . '/services/',
        'tasksDir'       => APP_PATH . '/tasks/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',
        'baseUri'        => '/phalconapi/',
        'debug' => [
            'state' => false,
            'path' => APP_PATH . '/runtime/debug/{YmdH}.log'
        ],
        'error' => [
            'path' => APP_PATH . '/runtime/error/{YmdH}.log'
        ]
    ],
],$localConfig));
