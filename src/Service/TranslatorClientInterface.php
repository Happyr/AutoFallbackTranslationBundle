<?php

namespace Happyr\AutoFallbackTranslationBundle\Service;

interface TranslatorClientInterface
{
    /**
     * @param string $string text to translate
     * @param string $from from what locale
     * @param string $to to what locale
     *
     * @return string Return the translated string
     */
    public function translate($string, $from, $to);
}
