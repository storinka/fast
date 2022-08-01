<?php

use App\Meta\DeclareMethod;
use Invoke\Invoke;
use Spiral\Tokenizer\ClassLocator;
use Symfony\Component\Finder\Finder;

$invoke = Invoke::create();

$methodsFinder = (new Finder())->files()->in([__DIR__ . '/../methods']);
$methodsClassLocator = new ClassLocator($methodsFinder);

foreach ($methodsClassLocator->getClasses() as $methodReflectionClass) {
    $methodNameAttribute = $methodReflectionClass->getAttributes(DeclareMethod::class)[0];

    if ($methodNameAttribute) {
        $methodNameAttributeInstance = $methodNameAttribute->newInstance();

        $invoke->setMethod(
            $methodNameAttributeInstance->getName(),
            $methodReflectionClass->getName()
        );
    }
}
