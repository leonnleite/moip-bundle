<?php

namespace Tear\MoipBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('moip');
        $rootNode   ->children()
                        ->arrayNode('credential')
                            ->children()
                                ->scalarNode('key')  
                                    ->isRequired()
                                ->end()
                                ->scalarNode('token')  
                                        ->isRequired()
                                ->end()
                            ->end()
                        ->end()
                        ->scalarNode('environment')
                            ->isRequired()
                            ->validate()
                                ->ifNotInArray(array('test','production'))
                                ->thenInvalid('Invalid environment! accepted variables:[test/production]')
                            ->end()
                        ->end()
                    ->end() ;
                
        return $treeBuilder;
    }

    public function getAliasName() {
        
    }
}
