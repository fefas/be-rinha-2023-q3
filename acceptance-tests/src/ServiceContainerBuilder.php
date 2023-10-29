<?php

namespace Fefas\BeRinha2023\AcceptanceTests;

use Bauhaus\ServiceResolver;
use Bauhaus\ServiceResolverSettings;
use Psr\Container\ContainerInterface as PsrContainer;

final class ServiceContainerBuilder
{
    public static function instance(): PsrContainer
    {
        $settings = ServiceResolverSettings::new()
            ->withDiscoverableNamespaces(__NAMESPACE__);

        return ServiceResolver::build($settings);
    }
}
