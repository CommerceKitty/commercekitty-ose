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
        $builder = new TreeBuilder('commerce_kitty_example');
        $builder->getRootNode()
            ->children()
                ->scalarNode('bar')->defaultValue('default')->end()
                ->arrayNode('foo')
                    ->children()
                        ->scalarNode('one')->defaultValue('default')->end()
                        ->scalarNode('two')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
