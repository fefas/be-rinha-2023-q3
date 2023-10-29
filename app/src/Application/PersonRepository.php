<?php

namespace Fefas\BeRinha2023\App\Application;

interface PersonRepository
{
    /** @throws NicknameAlreadyTaken */
    public function save(Person $createPerson): void;
    public function get(string $id): ?Person;
}
