<?php
return [
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => '123456',
        'tablePrefix' => 'pha_',
        'dbname'      => 'test',
        'charset'     => 'utf8',
        'options' => [
            \PDO::ATTR_STRINGIFY_FETCHES => false,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ],
    'beanstalk' => [
        'host' => '127.0.0.1',
        'port' => 11300
    ],
    'socket' => [
        'port'=>9701,
        'mode'=>SWOOLE_PROCESS,
        'config'=>[
            'reactor_num'=>1,
            'task_worker_num'=>8,
            'worker_num'=>8,
//            'ssl_key_file'=>'/usr/local/nginx/conf/cert/214865889070155.key',
//            'ssl_cert_file'=>'/usr/local/nginx/conf/cert/214865889070155.pem',
        ]
    ],
    'redis' => [
        'host' => '127.0.0.1',
        'port' => 6379,
        'timeout' => 0,
        'database' => 0,
        'password' => ''
    ]
];
