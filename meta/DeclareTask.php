<?php

namespace App\Meta;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class DeclareTask
{
    /**
     * @param float $interval
     */
    public function __construct(protected float $interval)
    {
    }

    /**
     * @return float
     */
    public function getInterval(): float
    {
        return $this->interval;
    }
}
