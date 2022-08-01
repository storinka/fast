<?php

namespace App\Meta;

use Cycle\ORM\EntityManager;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select;
use Cycle\ORM\Transaction\StateInterface;
use Invoke\Container;

abstract class Record
{
    public function save(bool $saveChildren = true): StateInterface
    {
        $manager = new EntityManager(self::getORM());

        return $manager->persist($this, $saveChildren)->run();
    }

    public function delete(): StateInterface
    {
        $manager = new EntityManager(self::getORM());

        return $manager->delete($this)->run();
    }

    public static function select(): Select
    {
        return self::getORM()->getRepository(static::class)->select();
    }

    public static function getORM(): ORMInterface
    {
        return Container::get(ORMInterface::class);
    }
}
