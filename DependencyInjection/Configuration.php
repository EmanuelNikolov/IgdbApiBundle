<?php

namespace EN\IgdbApiBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('en_igdb_api');

        $rootNode
          ->children()
            ->scalarNode('base_url')->end()
            ->scalarNode('api_key')->end()
          ->end()
        ->end();

        return $treeBuilder;
    }
}