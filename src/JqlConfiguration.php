<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class JqlConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('jira');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('project')->isRequired()
                ->children()
                    ->scalarNode('name')->isRequired()->end()
                    ->scalarNode('issuePrefix')->isRequired()->end()
                ->end()
            ->end()
                ->arrayNode('conditions')->isRequired()->end()
                ->arrayNode('expressions')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
}
