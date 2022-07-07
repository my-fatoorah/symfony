<?php

namespace MyFatoorah\SymfonyBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class MyFatoorahSymfonyExtension extends Extension {

    public function load(array $configs, ContainerBuilder $container) {

        error_log('11111111111111111111111111111111111111111111111111111111111111111111111111111111111');
        $loader = new YamlFileLoader(
                $container,
                new FileLocator(__DIR__ . '/../../config')
        );
        $loader->load('services.yaml');
    }

}
