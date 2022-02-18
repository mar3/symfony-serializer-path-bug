<?php

namespace App;

class Car
{
    public int $id;
    public string $name;
    public Owner $owner;

    /**
     * @var Part[]
     */
    public array $parts;
}