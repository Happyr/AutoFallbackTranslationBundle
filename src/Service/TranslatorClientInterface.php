<?php

namespace Happyr\AutoFallbackTranslationBundle\Service;

interface TranslatorClientInterface
{
    public function translate($string, $from, $to);
}
