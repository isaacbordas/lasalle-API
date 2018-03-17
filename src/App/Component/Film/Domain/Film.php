<?php

namespace App\Component\Film\Domain;

use App\Component\Film\Domain\Exception\{InvalidFilmNameException, InvalidFilmDescriptionException};

class Film
{
    private $id;
    private $name;
    private $description;
    private $actor;

    public function __construct(string $name, string $description, Actor $actor)
    {
        $this->validateName($name);
        $this->validateDescription($description);

        $this->name = filter_var($name, FILTER_SANITIZE_STRING);
        $this->description = filter_var($description, FILTER_SANITIZE_STRING);
        $this->actor = $actor;
    }

    private function validateName(string $name): void
    {
        if ($name === '') throw InvalidFilmNameException::empty();
    }

    private function validateDescription(string $description): void
    {
        if ($description === '') throw InvalidFilmDescriptionException::empty();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
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

    public function getActor(): string
    {
        return $this->actor->getName();
    }

    public function setActor(Actor $actor)
    {
        $this->actor = $actor;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'actor' => $this->getActor()
        ];
    }

}
