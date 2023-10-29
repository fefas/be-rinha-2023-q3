<?php

use Fefas\BeRinha2023\App\UserInterface\Http\PersonController;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->set(PersonController::class)
        ->tag('controller.service_arguments');
};
