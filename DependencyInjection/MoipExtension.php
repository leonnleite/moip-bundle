<?php

namespace Tear\MoipBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class MoipExtension extends Extension{
    //put your code here
    public function load(array $config, ContainerBuilder $container) {
        
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('moip.xml');
        
        $container->setParameter('payment.moip.credential', $config['credential']);
        $container->setParameter('payment.moip.environment', $config['environment']);
        
    }
}
