<?php
return [
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => 'root',
        'tablePrefix' => 'pha_',
        'dbname'      => 'phalcon',
        'charset'     => 'utf8',
        'options' => [
            \PDO::ATTR_STRINGIFY_FETCHES => false,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ],
    'beanstalk' => [
        'host' => '127.0.0.1',
        'port' => 11300
    ]
];
