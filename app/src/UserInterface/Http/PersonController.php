<?php

namespace Fefas\BeRinha2023\App\UserInterface\Http;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class PersonController
{
    public function get(): void
    {
        throw new NotFoundHttpException();
    }
}
