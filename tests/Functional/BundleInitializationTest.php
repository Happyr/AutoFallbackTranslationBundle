<?php

namespace Happyr\AutoFallbackTranslationBundle\Tests\Functional;

use Happyr\AutoFallbackTranslationBundle\Translator\FallbackTranslator;
use Happyr\Mq2phpBundle\Service\ConsumerWrapper;
use Happyr\Mq2phpBundle\Service\MessageSerializerDecorator;

class BundleInitializationTest extends BaseTestCase
{
    public function testRegisterBundle()
    {
        static::bootKernel();
        $container = static::$kernel->getContainer();
        $this->assertTrue($container->has('happyr.translation.auto_fallback_translator'));
        $trans = $container->get('happyr.translation.auto_fallback_translator');
        $this->assertInstanceOf(FallbackTranslator::class, $trans);
    }
}
