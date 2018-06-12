<?php
// DIC configuration

use Latte\Engine;
use Ujpef\LatteView;

$container = $app->getContainer();

$container['view'] = function ($container) use ($settings) {
    $engine = new Engine();
    $engine->setTempDirectory(__DIR__ . '/../cache');

    $latteView = new LatteView($engine, __DIR__ . '/../templates/');
    return $latteView;
};

$container['db'] = function($container) use ($settings){
    $s = $settings['settings']['db'];
    $db = new PDO('pgsql:host='. $s['db_host'] . ';dbname=' . $s['db_name'],
            $s['db_user'],
            $s['db_pass']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
    return $db;
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
