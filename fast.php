<?php

use Invoke\Container;
use Invoke\Invoke;
use Workerman\Connection\ConnectionInterface;
use Workerman\Protocols\Http\Request;
use Workerman\Worker;

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/bootstrap/init.php";

startTasks();

$httpWorker = new Worker("http://0.0.0.0:8080");
$httpWorker->name = 'fast';
$httpWorker->count = (int)shell_exec("nproc") * 4;
$httpWorker->onMessage = function (ConnectionInterface $connection, Request $request) {
    $methodName = trim(trim($request->path()), "/");
    $params = json_decode($request->rawBody(), true);

    if (empty($methodName)) {
        $connection->send("Powered by Fast&Invoke");
    } else {
        try {
            $result = Container::get(Invoke::class)
                ->serve(Container::get(Invoke::class), [
                    "name" => "$methodName",
                    "params" => $params,
                ]);

            $connection->send(json_encode([
                "result" => $result,
            ]));
        } catch (Throwable $exception) {
            $connection->send(json_encode([
                "error" => $exception::class,
                "message" => $exception->getMessage(),
            ]));
        }
    }
};

Worker::runAll();
