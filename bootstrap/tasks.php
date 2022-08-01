<?php

use App\Meta\DeclareTask;
use Invoke\Container;
use Spiral\Tokenizer\ClassLocator;
use Symfony\Component\Finder\Finder;
use Workerman\Timer;

$activeTimers = [];

function startTasks(): void
{
    $tasksFinder = (new Finder())->files()->in([__DIR__ . '/../tasks']);
    $tasksClassLocator = new ClassLocator($tasksFinder);

    foreach ($tasksClassLocator->getClasses() as $taskReflectionClass) {
        $taskNameAttribute = $taskReflectionClass->getAttributes(DeclareTask::class)[0];

        if ($taskNameAttribute) {
            $taskNameAttributeInstance = $taskNameAttribute->newInstance();

            $taskInstance = Container::make($taskReflectionClass->getName());

            $worker = new \Workerman\Worker();
            $worker->name = $taskReflectionClass->getName();

            $worker->onWorkerStart = function () use ($taskInstance, $taskNameAttributeInstance) {
                Timer::add($taskNameAttributeInstance->getInterval(), function () use ($taskInstance) {
                    $taskInstance->handle();
                });
            };
        }
    }
}
