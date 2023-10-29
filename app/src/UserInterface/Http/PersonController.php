<?php

namespace Fefas\BeRinha2023\App\UserInterface\Http;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final readonly class PersonController
{
    public function post(Request $request): void
    {
        $data = json_decode($request->getContent(), true);

        $nickname = $data['apelido'] ?? null;
        $name = $data['nome'];

        if ($nickname === null) {
            throw new UnprocessableEntityHttpException();
        }

        throw new BadRequestException();
    }

    public function get(): void
    {
        throw new NotFoundHttpException();
    }
}
