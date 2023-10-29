<?php

namespace Fefas\BeRinha2023\App\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class SymfonyKernel extends Kernel
{
    use MicroKernelTrait;

    private const DEBUG_ENV = 'dev';

    private function __construct()
    {
        $env = $_ENV['ENV'];
        $debug = self::DEBUG_ENV === $env;

        parent::__construct($env, $debug);

        $this->boot();
    }

    public static function booted(): self
    {
        return new self();
    }

    public function handleHttpCall(): void
    {
        $request = Request::createFromGlobals();

        $response = $this->handle($request);
        $response->send();

        $this->terminate($request, $response);
    }

    public function handleCliCall(): void
    {
        $app = new Application($this);
        $app->run(new ArgvInput());
    }

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();

        if ($this->isDebug()) {
            yield new TwigBundle();
            yield new WebProfilerBundle();
        }
    }

    public function getProjectDir(): string
    {
        return $_ENV['CODE_DIR'];
    }

    public function getConfigDir(): string
    {
        return "{$this->getProjectDir()}/config";
    }

    public function getCacheDir(): string
    {
        return "{$_ENV['CACHE_DIR']}/symfony/{$this->environment}";
    }

    public function getLogDir(): string
    {
        return $_ENV['LOGS_DIR'];
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import("{$this->getConfigDir()}/packages/*.yaml");
        $container->import("{$this->getConfigDir()}/services.php");
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import("{$this->getConfigDir()}/routes.yaml");
    }

}
