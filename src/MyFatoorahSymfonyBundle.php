<?php

namespace MyFatoorah\SymfonyBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use MyFatoorah\SymfonyBundle\DependencyInjection\UnconventionalExtensionClass;

class MyFatoorahSymfonyBundle extends Bundle {

//    public function getPath(): string {
//        error_log('22222222222222222222222222222222222222222222222222222222222222222222222222222222222');
//        return \dirname(__DIR__);
//    }
//
////    public function getContainerExtension() {
////        error_log('33333333333333333333333333333333333333333333333333333333333333333333333333333333333');
////
////        return new UnconventionalExtensionClass();
////    }
//    
//    public function getAlias(){
//        error_log('44444444444444444444444444444444444444444444444444444444444444444444444444444444444');
//        $extension = $this->createContainerExtension();
////        error_log('5555555' . $extension->getAlias() . '5555555');
//        error_log('44444444444444444444444444444444444444444444444444444444444444444444444444444444444');
//        return 'myfatoorah_symfony';
//    }

//    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void {
//        // load an XML, PHP or Yaml file
//        $container->import('../config/services.yaml');
//
//        // you can also add or replace parameters and services
//        $container->parameters()
//                ->set('myfatoorah_symfony.phrase', $config['phrase'])
//        ;
//
//        if ($config['scream']) {
//            $container->services()
//                    ->get('myfatoorah_symfony.printer')
//                    ->class(ScreamingPrinter::class)
//            ;
//        }
//    }

}
