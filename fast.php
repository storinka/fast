<?php

use Workerman\Connection\ConnectionInterface;
use Workerman\Protocols\Http\Request;
use Workerman\Worker;

require_once __DIR__ . '/vendor/autoload.php';

$http_worker = new Worker('http://0.0.0.0:2345');

$http_worker->count = 4;

$http_worker->onMessage = function (ConnectionInterface $connection, Request $request) {
//    parse_str($request->queryString(), $queryData);
//    $bodyData = json_decode($request->rawBody(), true);

//    $data = array_merge($bodyData, $queryData);

    $connection->send("Powered by Fast&Invoke");
};

Worker::runAll();
