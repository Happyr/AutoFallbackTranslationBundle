<?php

namespace Happyr\AutoFallbackTranslationBundle\Service;

use Happyr\AutoFallbackTranslationBundle\Translator\TranslatorClientInterface;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Component\Translation\TranslatorInterface;

class FallbackTranslator implements TranslatorInterface, TranslatorBagInterface
{
    /**
     * @var TranslatorInterface|TranslatorBagInterface
     */
    private $symfonyTranslator;

    /**
     * @var TranslatorClientInterface
     */
    private $translatorService;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     *
     * @param $defaultLocale
     * @param TranslatorInterface $symfonyTranslator
     * @param TranslatorClientInterface $translatorService
     */
    public function __construct($defaultLocale, TranslatorInterface $symfonyTranslator, TranslatorClientInterface $translatorService)
    {
        $this->symfonyTranslator = $symfonyTranslator;
        $this->translatorService = $translatorService;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function trans($id, array $parameters = array(), $domain  = null, $locale = null)
    {
        if (!$domain) {
            $domain = 'messages';
        }

        $catalogue = $this->getCatalogue($locale);
        if ($catalogue->defines($id, $domain)) {
            return $this->symfonyTranslator->trans($id, $parameters, $domain, $locale);
        }

        $locale = $catalogue->getLocale();
        if ($locale === $this->defaultLocale) {
            // we cant do anything...
            return $id;
        }

        $orgString = $this->symfonyTranslator->trans($id, $parameters, $domain, $this->defaultLocale);

        return $this->translatorService->translate($orgString, $this->defaultLocale, $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function transChoice($id, $number, array $parameters = array(), $domain  = null, $locale = null)
    {
        if (!$domain) {
            $domain = 'messages';
        }

        $catalogue = $this->getCatalogue($locale);
        if ($catalogue->defines($id, $domain)) {
            return $this->symfonyTranslator->transChoice($id, $number, $parameters, $domain, $locale);
        }

        $locale = $catalogue->getLocale();
        if ($locale === $this->defaultLocale) {
            // we cant do anything...
            return $id;
        }

        $orgString = $this->symfonyTranslator->transChoice($id, $number, $parameters, $domain, $this->defaultLocale);

        return $this->translatorService->translate($orgString, $this->defaultLocale, $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        $this->symfonyTranslator->setLocale($locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->symfonyTranslator->getLocale();
    }

    /**
     * {@inheritdoc}
     */
    public function getCatalogue($locale = null)
    {
        return $this->symfonyTranslator->getCatalogue($locale);
    }

    /**
     * Passes through all unknown calls onto the translator object.
     */
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->symfonyTranslator, $method), $args);
    }
}