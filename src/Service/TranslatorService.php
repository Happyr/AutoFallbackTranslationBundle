<?php

namespace Happyr\AutoFallbackTranslationBundle\Service;

/**
 * This represent a third party translation service. Like Google, Bing etc.
 */
interface TranslatorService
{
    /**
     * @param string $string text to translate
     * @param string $from   from what locale
     * @param string $to     to what locale
     *
     * @return string Return the translated string
     */
    public function translate($string, $from, $to);
}
