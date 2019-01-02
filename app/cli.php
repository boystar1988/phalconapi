<?php
use Phalcon\DI\FactoryDefault\CLI as CliDI;
use Phalcon\CLI\Console as ConsoleApp;

$di = new CliDI();
defined('APPLICATION_PATH')|| define('APPLICATION_PATH', realpath(dirname(__FILE__)));
$loader = new \Phalcon\Loader();
$loader->registerDirs(array(APPLICATION_PATH . '/tasks'));
$loader->register();

if(is_readable(APPLICATION_PATH . '/config/config.php')) {
    $config = include APPLICATION_PATH . '/config/config.php';
    $di->set('config', $config);
    /**
     * Database connection is created based in the parameters defined in the configuration file
     */
    $di->set('db', function () {
        $config = $this->getConfig();

        $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
        $connection = new $class([
            'host'     => $config->database->host,
            'username' => $config->database->username,
            'password' => $config->database->password,
            'dbname'   => $config->database->dbname,
            'charset'  => $config->database->charset
        ]);

        return $connection;
    });
}
$console = new ConsoleApp();
$console->setDI($di);

/*** Process the console arguments*/
$arguments = array();
$params = array();

foreach($argv as $k => $arg) {
    if($k == 1) {
        $arguments['task'] = $arg;
    } elseif($k == 2) {
        $arguments['action'] = $arg;
    } elseif($k >= 3) {
        $params[] = $arg;
    }
}
if(count($params) > 0) {
    $arguments['params'] = $params;
}
// define global constants for the current task and action
define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));
try {
    // handle incoming arguments
    $console->handle($arguments);
}catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
    exit(255);
}