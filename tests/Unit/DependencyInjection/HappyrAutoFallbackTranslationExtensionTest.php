<?php

namespace Happyr\AutoFallbackTranslationBundle\Tests\Unit\DependencyInjection;

use Happyr\AutoFallbackTranslationBundle\DependencyInjection\HappyrAutoFallbackTranslationExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class HappyrAutoFallbackTranslationExtensionTest extends AbstractExtensionTestCase
{
    protected function getMinimalConfiguration()
    {
        return ['enabled' => true];
    }

    public function testServicesRegisteredAfterLoading()
    {
        $this->load();

        $this->assertContainerBuilderHasService('happyr.translation.auto_fallback_translator', 'Happyr\AutoFallbackTranslationBundle\Translator\FallbackTranslator');
    }

    protected function getContainerExtensions()
    {
        return [
            new HappyrAutoFallbackTranslationExtension(),
        ];
    }
}
