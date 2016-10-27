<?php

namespace LeonnLeite\MoipBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('moip');
        $rootNode->children()
                        ->arrayNode('credential')
                            ->isRequired()
                            ->children()
                                ->scalarNode('key')
                                    ->defaultValue('')
                                ->end()
                                ->scalarNode('token')
                                        ->isRequired()
                                ->end()
                            ->end()
                        ->end()
                        ->scalarNode('production')
                            ->defaultFalse()
                        ->end()
                        ->scalarNode('authentication_mode')
                            ->defaultValue('Basic')
                            ->validate()
                                ->ifNotInArray(['Basic', 'OAuth'])
                                ->thenInvalid('Authentication mode is only Basic or OAuth')
                            ->end()
                        ->end()
                    ->end();

        return $treeBuilder;
    }

    public function getAliasName()
    {
    }
}
