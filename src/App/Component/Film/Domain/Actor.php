<?php

namespace App\Component\Film\Domain;

class Actor
{
    private $id;
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

}