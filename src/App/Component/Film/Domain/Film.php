<?php

namespace App\Component\Film\Domain;

class Film
{
    private $id;
    private $name;
    private $description;
    private $actor;

    public function __construct(string $name, string $description, Actor $actor)
    {
        $this->name = $name;
        $this->description = $description;
        $this->actor = $actor;
    }

    public function getId(): int
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getActor() : string
    {
        return $this->actor->getName();
    }

}
