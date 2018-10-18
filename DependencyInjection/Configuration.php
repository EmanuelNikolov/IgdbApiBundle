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
            ->scalarNode('base_url')->cannotBeEmpty()->end()
            ->scalarNode('api_key')->cannotBeEmpty()->end()
            ->scalarNode('custom_wrapper')->defaultNull()->end()
            ->scalarNode('custom_parameter_builder')->defaultNull()->end()
          ->end()
        ->end();

        return $treeBuilder;
    }
}