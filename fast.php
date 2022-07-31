<?php

use Workerman\Connection\ConnectionInterface;
use Workerman\Protocols\Http\Request;
use Workerman\Worker;

require_once __DIR__ . "/vendor/autoload.php";

$worker = new Worker("http://0.0.0.0:2345");

$worker->onMessage = function (ConnectionInterface $connection, Request $request) {
    $connection->send("Powered by Fast&Invoke");
    sleep(2);
};

Worker::runAll();
