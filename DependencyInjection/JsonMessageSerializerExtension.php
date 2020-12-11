<?php

declare(strict_types=1);

namespace webignition\JsonMessageSerializerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class JsonMessageSerializerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $serviceConfigurationPaths = [
            'services.yaml',
        ];

        if ($this->isInSelfTestEnvironment($container)) {
            $serviceConfigurationPaths[] = 'services_test.yaml';
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        foreach ($serviceConfigurationPaths as $serviceConfigurationPath) {
            $path = __DIR__ . '/../Resources/config/' . $serviceConfigurationPath;

            if (file_exists($path)) {
                $loader->load($serviceConfigurationPath);
            }
        }
    }

    private function isInSelfTestEnvironment(ContainerBuilder $container): bool
    {
        if ('test' !== $container->getParameter('kernel.environment')) {
            return false;
        }

        $kernelProjectDirectory = realpath($container->getParameter('kernel.project_dir'));
        $bundleDirectory = realpath(__DIR__ . '/..');

        return $kernelProjectDirectory === $bundleDirectory;
    }
}
