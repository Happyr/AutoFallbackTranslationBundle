<?php

namespace Happyr\AutoFallbackTranslationBundle\Tests;

use Happyr\AutoFallbackTranslationBundle\Service\TranslatorClientInterface;
use Happyr\AutoFallbackTranslationBundle\Translator\FallbackTranslator;

class FallbackTranslatorTest extends \PHPUnit_Framework_TestCase
{
    public function testTranslateWithSubstitutedParameters()
    {
        $method = new \ReflectionMethod(FallbackTranslator::class, 'translateWithSubstitutedParameters');
        $method->setAccessible(true);

        $translator = $this->getMock(TranslatorClientInterface::class);
        $translator->method('translate')->will($this->returnArgument(0));

        $service = $this->getMockBuilder(FallbackTranslator::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTranslatorService'])
            ->getMock();
        $service->expects($this->any())->method('getTranslatorService')->willReturn($translator);

        // One parameter test
        $result = $method->invoke($service, 'abc bar abc', 'en', ['%foo%'=>'bar']);
        $this->assertEquals('abc bar abc', $result);

        // Two parameters test
        $result = $method->invoke($service, 'abc bar abc baz', 'en', ['%foo%'=>'bar', '%biz%'=>'baz']);
        $this->assertEquals('abc bar abc baz', $result);

        // Test with object
        $result = $method->invoke($service, 'abc object abc', 'en', ['%foo%'=>new Minor('object')]);
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

    function __toString()
    {
        return $this->name;
    }
}