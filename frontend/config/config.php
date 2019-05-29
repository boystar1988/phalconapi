<?php
/*
 * Modified: preppend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(dirname(__DIR__))));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/frontend');

$localConfig = include "config-local.php";
$routeConfig = include "routes.php";

return new \Phalcon\Config(array_merge([
    'application' => [
        'appDir'         => APP_PATH . '/',
        'helpersDir'     => BASE_PATH . '/common/helpers/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => BASE_PATH . '/common/models/',
        'servicesDir'    => APP_PATH . '/services/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => BASE_PATH . '/common/library/',
        'cacheDir'       => APP_PATH . '/runtime/cache/',
        'baseUri'        => '/phalconapi/',
        'debug' => [
            'state' => false,
            'path' => APP_PATH . '/runtime/debug/{YmdH}.log'
        ],
        'error' => [
            'path' => APP_PATH . '/runtime/error/{YmdH}.log'
        ]
    ],
    'route' => $routeConfig
],$localConfig));
