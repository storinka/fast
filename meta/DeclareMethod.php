<?php

namespace App\Meta;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class DeclareMethod
{
    /**
     * @param string $name
     */
    public function __construct(protected string $name)
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
