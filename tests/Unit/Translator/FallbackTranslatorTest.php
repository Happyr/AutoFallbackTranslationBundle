<?php

namespace Happyr\AutoFallbackTranslationBundle\Tests\Unit\Translator;

use Happyr\AutoFallbackTranslationBundle\Service\TranslatorService;
use Happyr\AutoFallbackTranslationBundle\Translator\FallbackTranslator;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class FallbackTranslatorTest extends \PHPUnit_Framework_TestCase
{
    public function testTranslateWithSubstitutedParameters()
    {
        $method = new \ReflectionMethod(FallbackTranslator::class, 'translateWithSubstitutedParameters');
        $method->setAccessible(true);

        $translator = $this->getMockBuilder(TranslatorService::class)->getMock();
        $translator->method('translate')->will($this->returnArgument(0));

        $service = $this->getMockBuilder(FallbackTranslator::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTranslatorService'])
            ->getMock();
        $service->expects($this->any())->method('getTranslatorService')->willReturn($translator);

        // One parameter test
        $result = $method->invoke($service, 'abc bar abc', 'en', ['%foo%' => 'bar']);
        $this->assertEquals('abc bar abc', $result);

        // Two parameters test
        $result = $method->invoke($service, 'abc bar abc baz', 'en', ['%foo%' => 'bar', '%biz%' => 'baz']);
        $this->assertEquals('abc bar abc baz', $result);

        // Test with object
        $result = $method->invoke($service, 'abc object abc', 'en', ['%foo%' => new Minor('object')]);
        $this->assertEquals('abc object abc', $result);
    }
}

class Minor
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name;
    }
}
