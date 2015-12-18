<?php

namespace Happyr\AutoFallbackTranslationBundle\Translator;

interface TranslatorClientInterface
{
    public function translate($string, $from, $to);
}