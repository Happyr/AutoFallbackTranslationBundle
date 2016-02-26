<?php

namespace Happyr\AutoFallbackTranslationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('happyr_auto_fallback_translation');

        $root->children()
            ->scalarNode('http_client')->cannotBeEmpty()->defaultValue('httplug.client')->end()
            ->scalarNode('message_factory')->cannotBeEmpty()->defaultValue('httplug.message_factory')->end()
            ->enumNode('translation_service')->values(array('google', 'foobar'))->defaultValue('google')->end()
            ->booleanNode('enabled')->defaultFalse()->end()
            ->scalarNode('default_locale')->defaultValue('en')->end()
            ->scalarNode('google_key')->defaultNull()->end()
        ->end();

        return $treeBuilder;
    }
}
