<?php
$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->servicesDir,
        $config->application->modelsDir,
        $config->application->helpersDir,
        $config->application->libraryDir,
    ]
)->register();

$loader->registerNamespaces([
    'App\Library'       => $config->application->libraryDir,
    'App\Helpers'       => $config->application->helpersDir,
    'App\Controllers'   => $config->application->controllersDir,
    'App\Services'      => $config->application->servicesDir,
    'App\Models'        => $config->application->modelsDir,
]);