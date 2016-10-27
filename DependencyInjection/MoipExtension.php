<?php

namespace LeonnLeite\MoipBundle\DependencyInjection;


use Moip\Moip;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class MoipExtension extends Extension
{
    //put your code here
    public function load(array $config, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('moip.xml');

        $enviroment = Moip::ENDPOINT_SANDBOX;
        if ($config['production']) {
            $enviroment = Moip::ENDPOINT_PRODUCTION;
        }

        $authenticationClass = 'moip.authenticator.basic';
        if ($config['authentication_mode'] == 'OAuth') {
            $authenticationClass = 'moip.authenticator.oauth';
        }


        $container->setAlias('moip.authenticator', $authenticationClass);
        $container->setParameter('moip.credential.key', $config['credential']['key']);
        $container->setParameter('moip.credential.token', $config['credential']['token']);
        $container->setParameter('moip.environment', $enviroment);

    }
}
