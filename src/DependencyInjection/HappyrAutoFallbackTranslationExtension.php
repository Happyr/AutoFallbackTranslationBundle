<?php

namespace Happyr\AutoFallbackTranslationBundle\DependencyInjection;

use Happyr\AutoFallbackTranslationBundle\Service\GoogleTranslator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class HappyrAutoFallbackTranslationExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (!$config['enabled']) {
            return;
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        switch($config['translation_service']) {
            case 'google':
                $translatorServiceDef = $container->register('happyr.translation.auto.service', GoogleTranslator::class);
                $translatorServiceDef->addArgument($config['google_key']);
                break;
            default:
                throw new \RuntimeException('You must choose a translation service for AutoFallbackTranslatorBundle.');
        }

        $translatorServiceDef
            ->addMethodCall('setCachePool', [new Reference($config['cache_service'])])
            ->addMethodCall('setLogger', [new Reference('logger', ContainerInterface::IGNORE_ON_INVALID_REFERENCE)]);

        $container->findDefinition('happyr.translation.auto_fallback_translator')
            ->replaceArgument(0, $config['default_locale'])
            ->replaceArgument(2, $translatorServiceDef)
            ->setDecoratedService('translator', null, 10);
    }

}
