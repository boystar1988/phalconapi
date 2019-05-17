<?php
return [
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => '127.0.0.1',
        'username'    => 'root',
        'password'    => 'root',
        'tablePrefix' => 'phal_',
        'dbname'      => 'phalcon',
        'charset'     => 'utf8',
        'options' => [
            \PDO::ATTR_STRINGIFY_FETCHES => false,
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_PERSISTENT => true, //持久连接
        ]
    ],
    'beanstalk' => [
        'host' => '127.0.0.1',
        'port' => 11300,
        'tube' => 'phalconapi',
    ],
    'cache' => [
        'cacheDir' => APP_PATH.'/cache/',
        'host' => '127.0.0.1',
        'port' => 6379,
        'auth' => '',
	   	'persistent' => false
    ]
];
