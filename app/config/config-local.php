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
    ]
];
