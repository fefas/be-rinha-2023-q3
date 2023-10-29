<?php

use Fefas\BeRinha2023\App\Application\PersonRepository;
use Fefas\BeRinha2023\App\UserInterface\Http\PersonController;
use Fefas\BeRinha2023\App\Infrastructure\PdoPersonRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->set(PersonController::class)
        ->tag('controller.service_arguments');

    $services
        ->set(PersonRepository::class)
        ->class(PdoPersonRepository::class);
};
