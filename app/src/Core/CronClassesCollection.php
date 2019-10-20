<?php

declare(strict_types=1);

namespace App\Core;

class CronClassesCollection
{

    private $collection = [];

    public function push(string $classname): string
    {
        $this->collection[] = $classname;
        return $classname;
    }

    public function pop(): ?string
    {
        return array_pop($this->collection);
    }
}
