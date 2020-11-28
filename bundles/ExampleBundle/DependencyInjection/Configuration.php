<?php

namespace CommerceKitty\Bundle\ExampleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('example');

        $builder->getRootNode()
            ->children()
                ->arrayNode('example')
                    ->children()
                        ->scalarNode('one')->end()
                        ->scalarNode('two')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
