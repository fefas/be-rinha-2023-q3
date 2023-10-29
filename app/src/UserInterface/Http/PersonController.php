<?php

namespace Fefas\BeRinha2023\App\UserInterface\Http;

use Fefas\BeRinha2023\App\Application\ArgumentCannotBeNull;
use Fefas\BeRinha2023\App\Application\InvalidArgumentType;
use Fefas\BeRinha2023\App\Application\NicknameAlreadyTaken;
use Fefas\BeRinha2023\App\Application\Person;
use Fefas\BeRinha2023\App\Application\PersonRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class PersonController
{
    public function __construct(
        private PersonRepository $personRepository,
    ) {
    }

    public function post(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        try {
            $createPerson = Person::newFromRequestData($data);
            $this->personRepository->save($createPerson);
        } catch (ArgumentCannotBeNull|NicknameAlreadyTaken) {
            return new Response(status: 422);
        } catch (InvalidArgumentType) {
            return new Response(status: 400);
        }

        return new Response(status: 201, headers: ['Location' => "/pessoas/{$createPerson->id}"]);
    }

    public function get(string $id): Response
    {
        $person = $this->personRepository->get($id);

        if (null === $person) {
            return new Response(status: 404);
        }

        return new JsonResponse($person->toResponseData());
    }
}
